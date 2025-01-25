<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use App\User;
use App\Contact;
use App\OldContact;
use App\Notification;
use DataTables;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    
    public function create(Request $request)
    {
        
        $user_id = $request->user_id;

        if(empty($user_id)) {
            return response()->json(['message' => 'User id is required', 'status' => 400]);
        }

        $contact_id = $request->contact_id;

        if(empty($contact_id)) {
            return response()->json(['message' => 'Contact id is required', 'status' => 400]);
        }

        $description = $request->description;

        if(empty($description)) {
            return response()->json(['message' => 'Description is required', 'status' => 400]);
        }

        $note = new Note();

        $note->user_id = $user_id;
        $note->contact_id = $contact_id;
        $note->description = $description;
        $note->save();

        return response()->json(['message' => 'Note saved successfully', 'status' => 200]);
    }

    public function list(Request $request)
    {
        $contact_id = $request->contact_id;

        if(empty($contact_id)) {
            return response()->json(['message' => 'Contact id is required', 'status' => 400]);
        }

        $results        = Note::where('contact_id', '=', $contact_id)->get();
      
        if($results) {
            foreach ($results as $key => $result) {
                $userDetail = User::select('id', 'first_name', 'last_name')->where('id', '=', $result->user_id)->get();
                if(!empty($userDetail)) {
                    $result['user_id'] = $userDetail;
                }

                $contactDetail = Contact::select('id', 'contact_name')->where('id', '=', $result->contact_id)->get();

                if(!empty($contactDetail)) {
                    $result['contact_id'] = $contactDetail;
                }

                $result['type'] = 1;
            }
        }
        $user = Auth::user();
        
        $notificationList = Notification::where('user_id', $user->id)->where('contact_id', '=', $contact_id)->get();

        if($notificationList) {

            foreach ($notificationList as $key => $notification) {

                $createdDetail = User::select('id', 'first_name', 'last_name')->where('id', '=', $notification->createdBy)->get();
                if(!empty($createdDetail)) {
                    $notification['user_id'] = $createdDetail;
                }

                $contactDetail = Contact::where('id', '=', $notification->contact_id)->first();

                if(!empty($contactDetail)) {
                    $notification['contact_id'] = $contactDetail;
                }

                $oldContact = OldContact::where('id', $notification->old_contact_id)->first();
                if(!empty($oldContact)) {
                    $notification['oldContact'] = $oldContact;
                }

                $notification['type'] = 2;
            }
        }
        
        $finaRresults = array_merge($results->toArray(), $notificationList->toArray());
        $noteDetails = Datatables::of($finaRresults)->make(true);
		return response()->json(['message' => 'notes list', 'status' => 200, 'data' => $noteDetails]);
    }
    
    public function createNotification(Request $request)
    {
        
        $user_id = $request->user_id;

        if(empty($user_id)) {
            return response()->json(['message' => 'User id is required', 'status' => 400]);
        }

        $contact_id = $request->contact_id;

        if(empty($contact_id)) {
            return response()->json(['message' => 'Contact id is required', 'status' => 400]);
        }

        $description = $request->description;

        if(empty($description)) {
            return response()->json(['message' => 'Description is required', 'status' => 400]);
        }

        $users = User::all();

        if($users) {
            foreach ($users as $key => $user) {

                $notification = new Notification();

                $notification->user_id = $user->id;
                $notification->contact_id = $contact_id;
                $notification->description = $description;
                $notification->readMessage = 0;
                $notification->createdBy = $user_id;
                $notification->old_contact_id = $request->oldContactId;
                $notification->save();
            }
        } else {
            return response()->json(['message' => 'No users found', 'status' => 400,]);           
        }

        $user = Auth::user();
        $notificationLists = Notification::where('user_id', $user->id)->where('readMessage',0)->get();

        foreach ($notificationLists as $key => $notificationList) {
            $contactDetails = Contact::where('id', $notificationList->contact_id)->first();
            if(!empty($contactDetails)) {
                $notificationList['contact_data'] = $contactDetails;
            }
        }
        return response()->json(['message' => 'Notification saved successfully', 'status' => 200, 'data' => $notificationLists]);
    }

    public function readNotification(Request $request)
    {
        $user = Auth::user();
        if (!empty($user)) {
            $notifications = Notification::where('user_id', $user->id)->update(['readMessage' => 1]);
            $notificationLists = Notification::where('user_id', $user->id)->where('readMessage',0)->get();
            return response()->json(['message' => 'Notification read', 'status' => 200, 'data' => $notificationLists]);

        } else {
            return response()->json(['message' => 'Something went wrong', 'status' => 400]);
        }
    }

    public function readNotificationByID(Request $request)
    {
        $user = Auth::user();
        $contact_id = $request->contact_id;

        if (!empty($user) && !empty($contact_id)) {
            $notifications = Notification::where('user_id', $user->id)->where('contact_id', $contact_id)->update(['readMessage' => 1]);
            $notificationLists = Notification::where('user_id', $user->id)->where('readMessage',0)->get();
            return response()->json(['message' => 'Notification read', 'status' => 200, 'data' => $notificationLists]);

        } else {
            return response()->json(['message' => 'Something went wrong', 'status' => 400]);
        }
    }

    public function notificationList(Request $request)
    {

        $user = Auth::user();
        $results     = Notification::where('user_id', $user->id)->get();

        if($results) {
            foreach ($results as $key => $result) {
                
                $contact = Contact::where('id', $result->contact_id)->first();
                if(!empty($contact)) {
                    $result['contact'] = $contact;
                }

                $oldContact = OldContact::where('id', $result->old_contact_id)->first();
                if(!empty($oldContact)) {
                    $result['oldContact'] = $oldContact;
                }

            }

            $noteDetails = Datatables::of($results)
            ->editColumn(
                'createdBy',
                function ($results) {

                    $userDetail = User::select('id', 'first_name', 'last_name')->where('id', '=', $results->createdBy)->get()->toArray();

                    return $userDetail;

                }
            )->editColumn(
                'user_id',
                function ($results) {

                    $userDetail = User::select('id','first_name', 'last_name')->where('id', '=', $results->user_id)->get()->toArray();

                    return $userDetail;

                }
            )->editColumn(
                'contact_id',
                function ($results) {

                    $contactDetail = Contact::select('id','contact_name')->where('id', '=', $results->contact_id)->get()->toArray();

                    return $contactDetail;

                }
            )->escapeColumns([])->make(true);
            return response()->json(['message' => 'notes list', 'status' => 200, 'data' => $noteDetails]);
        } else {
            return response()->json(['message' => 'notes list', 'status' => 200, 'data' => []]);
        }
            
    }
}
