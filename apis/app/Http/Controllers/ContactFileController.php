<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Note;
use App\Contact;
use App\User;
use App\ContactFile;
use DataTables;
use File;

class ContactFileController extends Controller
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

        $file_name = $request->file_name;

        if(empty($file_name)) {
            return response()->json(['message' => 'File name is required', 'status' => 400]);
        }

        $description = $request->description;

        if(empty($description)) {
            return response()->json(['message' => 'Description is required', 'status' => 400]);
        }

        $contactFile = new ContactFile();

        $contactFile->contact_id = $contact_id;
        $contactFile->user_id = $user_id;
        $contactFile->file_name = $file_name;
        $contactFile->description = $description;

        $contactFile->save();


        if(!empty($request->file('file'))) {

            $file = $request->file('file');
            $uploadedPath = public_path('/uploads/contactFile/');

            if (!File::isDirectory($uploadedPath)) {
                File::makeDirectory($uploadedPath, 0775, true);
            }

            $profilefile = $file->getClientOriginalName();
            $file->move($uploadedPath, $profilefile);
            $contactFile->file = $profilefile;
            $contactFile->save();
        }

        return response()->json(['message' => 'File saved successfully', 'status' => 200]);
    }

    public function list(Request $request)
    {

        $contact_id = $request->contact_id;

        if(empty($contact_id)) {
            return response()->json(['message' => 'Contact id is required', 'status' => 400]);
        }

        $results        = ContactFile::where('contact_id', '=', $contact_id)->get();

        foreach ($results as $key => $result) {
            if(!empty($result->file) ) {
                    $result->file_document = asset('uploads/contactFile/'.$result->file.'?'.time());
            } 
        }

        $fileDetails = Datatables::of($results)
            ->editColumn(
                'user_id',
                function ($results) {

                    $userDetail = User::select('first_name', 'last_name')->where('id', '=', $results->user_id)->get()->toArray();

                    return $userDetail;

                }
            )->editColumn(
                'contact_id',
                function ($results) {

                    $contactDetail = Contact::select('contact_name')->where('id', '=', $results->contact_id)->get()->toArray();

                    return $contactDetail;

                }
            )->escapeColumns([])->make(true);

        return response()->json(['message' => 'File list', 'status' => 200, 'data' => $fileDetails]);
    }
}
