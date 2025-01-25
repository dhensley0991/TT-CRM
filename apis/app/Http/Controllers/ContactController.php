<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\Contact;
use App\OldContact;
use App\User;
use DataTables;
use App\Notification;
use Illuminate\Support\Facades\Auth;

class ContactController extends Controller
{
    public function create(Request $request)
    {
        $account_name = $request->account_name;

        if(empty($account_name)) {
            return response()->json(['message' => 'Account name is required', 'status' => 400]);
        }

        $contact                          = new Contact();
        $contact->user_id                 = $request->user_id;
        $contact->contact_name            = $request->contact_name;
        $contact->account_name            = $request->account_name;
        $contact->account_number          = $request->account_number;
        $contact->address                 = $request->address;
        $contact->address_two             = $request->address_two;
        $contact->address_three           = $request->address_three;
        $contact->city                    = $request->city;
        $contact->state                   = $request->state;
        $contact->zip                     = $request->zip;
        $contact->country                 = $request->country;
        $contact->province                = $request->province;
        $contact->website                 = $request->website;
        $contact->phone_number            = $request->phone_number;
        $contact->fax_number              = $request->fax_number;
        $contact->mailing_address         = $request->mailing_address;
        $contact->mailing_address_two     = $request->mailing_address_two;
        $contact->mailing_address_three   = $request->mailing_address_three;
        $contact->mail_city               = $request->mail_city;
        $contact->mail_state              = $request->mail_state;
        $contact->mail_zip                = $request->mail_zip;
        $contact->mail_country            = $request->mail_country;
        $contact->mail_province           = $request->mail_province;
        $contact->referral_source         = $request->referral_source;
        $contact->acc                     = $request->acc;
        $contact->platform                = $request->platform;
        $contact->trader_id               = $request->trader_id;
        $contact->comm_deal               = $request->comm_deal;
        $contact->locate_deal             = $request->locate_deal;
        $contact->platform_price          = $request->platform_price;
        $contact->program_member          = $request->program_member;
        $contact->cost_per_month          = $request->cost_per_month;
        $contact->shares_per_month        = $request->shares_per_month;
        $contact->field2                  = $request->field2;
        $contact->field3                  = $request->field3;
        $contact->field4                  = $request->field4;
        $contact->field5                  = $request->field5;
      	$contact->options_approval       = $request->options_approval;
        $contact->future_approval        = $request->future_approval;
        $contact->opt_cont_comm_pric     = $request->opt_cont_comm_pric;
        $contact->options_platform       = $request->options_platform;
        $contact->option_level_appr      = $request->option_level_appr;
        $contact->future_comm_pric       = $request->future_comm_pric;
        $contact->future_level_app       = $request->future_level_app;
        $contact->future_plat            = $request->future_plat;
        $contact->future_clear_firm      = $request->future_clear_firm;
        $contact->time_limit              = $request->time_limit;

        $contact->save();
       /* $results        = Contact::all();
        foreach($results as $result){
                $getAllReferral = 0;
                $getAllReferral = Contact::where('referral_source', $result->id)->get();
                $contact = Contact::find($result->id);
                if(!empty($contact)) {
                    $contact->total_referral = count($getAllReferral);
                    $contact->update();
                }
        }

        $results = Contact::orderBy('total_referral', 'DESC')->orderBy('contact_name', 'ASC')->get();
        foreach($results as $key => $result){
            $contact = Contact::find($result->id);
            if(!empty($contact)) {
                $contact->category_referral = $key + 1;
                $contact->update();
            }
        } */
      $referralContact = Contact::where('id', $request->referral_source)->get();
        if(!empty($referralContact)) {
            foreach($referralContact as $result) {
                $result->total_referral = (int)($result->total_referral) + 1;
                $result->update();
            }
            
        }

        $results = Contact::orderBy('total_referral', 'DESC')->orderBy('contact_name', 'ASC')->select('id', 'category_referral')->get();
      foreach($results as $key => $result){
            $result->category_referral = $key + 1;
            $result->update();
        }
        return response()->json(['message' => 'Contact added successfully', 'status' => 200]);
    }

    public function updateContact(Request $request)
    {
        $user_id = $request->user_id;

        if(empty($user_id)) {
            return response()->json(['message' => 'User id is required', 'status' => 400]);
        }

        $contact_id = $request->contact_id;

        if(empty($contact_id)) {
            return response()->json(['message' => 'Contact id is required', 'status' => 400]);
        }

        $contact = Contact::find($contact_id);

        if(!empty($contact)) {
			
            $oldContact  = new OldContact();

                $oldContact->contact_id              = $contact->id;
                $oldContact->user_id                 = $contact->user_id;
                $oldContact->contact_name            = json_encode(['old_value' => $contact->contact_name, 'new_value' => $request->contact_name]);
                $oldContact->account_name            = json_encode(["old_value" => $contact->account_name, "new_value" => $request->account_name]);
                $oldContact->account_number          = json_encode(["old_value" => $contact->account_number, "new_value" => $request->account_number]);
                $oldContact->address                 = json_encode(["old_value" => $contact->address, "new_value" => $request->address]);
                $oldContact->address_two             = json_encode(["old_value" => $contact->address_two, "new_value" => $request->address_two]);
                $oldContact->address_three           = json_encode(["old_value" => $contact->address_three, "new_value" => $request->address_three]);
                $oldContact->city                    = json_encode(["old_value" => $contact->city, "new_value" => $request->city]);
                $oldContact->state                   = json_encode(["old_value" => $contact->state, "new_value" => $request->state]);
                $oldContact->zip                     = json_encode(["old_value" => $contact->zip, "new_value" => $request->zip]);
                $oldContact->country                 = json_encode(["old_value" => $contact->country, "new_value" => $request->country]);
                $oldContact->province                = json_encode(["old_value" => $contact->province, "new_value" => $request->province]);
                $oldContact->website                 = json_encode(["old_value" => $contact->website, "new_value" => $request->website]);
                $oldContact->phone_number            = json_encode(["old_value" => $contact->phone_number, "new_value" => $request->phone_number]);
                $oldContact->fax_number              = json_encode(["old_value" => $contact->fax_number, "new_value" => $request->fax_number]);
                $oldContact->mailing_address         = json_encode(["old_value" => $contact->mailing_address, "new_value" => $request->mailing_address]);
                $oldContact->mailing_address_two     = json_encode(["old_value" => $contact->mailing_address_two, "new_value" => $request->mailing_address_two]);
                $oldContact->mailing_address_three   = json_encode(["old_value" => $contact->mailing_address_three, "new_value" => $request->mailing_address_three]);
                $oldContact->mail_city               = json_encode(["old_value" => $contact->mail_city, "new_value" => $request->mail_city]);
                $oldContact->mail_state              = json_encode(["old_value" => $contact->mail_state, "new_value" => $request->mail_state]);
                $oldContact->mail_zip                = json_encode(["old_value" => $contact->mail_zip, "new_value" => $request->mail_zip]);
                $oldContact->mail_country            = json_encode(["old_value" => $contact->mail_country, "new_value" => $request->mail_country]);
                $oldContact->mail_province           = json_encode(["old_value" => $contact->mail_province, "new_value" => $request->mail_province]);
                $oldContact->referral_source         = json_encode(["old_value" => $contact->referral_source, "new_value" => $request->referral_source]);
                $oldContact->acc                     = json_encode(["old_value" => $contact->acc, "new_value" => $request->acc]);
                $oldContact->platform                = json_encode(["old_value" => $contact->platform, "new_value" => $request->platform]);
                $oldContact->trader_id               = json_encode(["old_value" => $contact->trader_id, "new_value" => $request->trader_id]);
                $oldContact->comm_deal               = json_encode(["old_value" => $contact->comm_deal, "new_value" => $request->comm_deal]);
                $oldContact->locate_deal             = json_encode(["old_value" => $contact->locate_deal, "new_value" => $request->locate_deal]);
                $oldContact->platform_price          = json_encode(["old_value" => $contact->platform_price, "new_value" => $request->platform_price]);
                $oldContact->program_member          = json_encode(["old_value" => $contact->program_member, "new_value" => $request->program_member]);
                $oldContact->cost_per_month          = json_encode(["old_value" => $contact->cost_per_month, "new_value" => $request->cost_per_month]);
                $oldContact->shares_per_month        = json_encode(["old_value" => $contact->shares_per_month, "new_value" => $request->shares_per_month]);
                $oldContact->time_limit              = json_encode(["old_value" => $contact->time_limit, "new_value" => $request->time_limit]);
                $oldContact->category_referral       = json_encode(["old_value" => $contact->category_referral, "new_value" => $request->category_referral]);
                $oldContact->total_referral          = json_encode(["old_value" => $contact->total_referral, "new_value" => $request->total_referral]);
                $oldContact->field2                  = json_encode(["old_value" => $contact->field2, "new_value" => $request->field2]);
                $oldContact->field3                  = json_encode(["old_value" => $contact->field3, "new_value" => $request->field3]);
                $oldContact->field4                  = json_encode(["old_value" => $contact->field4, "new_value" => $request->field4]);
                $oldContact->field5                  = json_encode(["old_value" => $contact->field5, "new_value" => $request->field5]);
          		$oldContact->options_approval        = json_encode(["old_value" => $contact->options_approval, "new_value" => $request->options_approval]);
                $oldContact->future_approval         = json_encode(["old_value" => $contact->future_approval, "new_value" => $request->future_approval]);
            	$oldContact->opt_cont_comm_pric      = json_encode(["old_value" => $contact->opt_cont_comm_pric, "new_value" => $request->opt_cont_comm_pric]);
            	$oldContact->options_platform        = json_encode(["old_value" => $contact->options_platform, "new_value" => $request->options_platform]);
            	$oldContact->option_level_appr       = json_encode(["old_value" => $contact->option_level_appr, "new_value" => $request->option_level_appr]);
            	$oldContact->future_comm_pric        = json_encode(["old_value" => $contact->future_comm_pric, "new_value" => $request->future_comm_pric]);
            	$oldContact->future_level_app        = json_encode(["old_value" => $contact->future_level_app, "new_value" => $request->future_level_app]);
            	$oldContact->future_plat             = json_encode(["old_value" => $contact->future_plat, "new_value" => $request->future_plat]);
            	$oldContact->future_clear_firm       = json_encode(["old_value" => $contact->future_clear_firm, "new_value" => $request->future_clear_firm]);
                $oldContact->save();
          
            $contact->user_id                 = $request->user_id;
            $contact->contact_name            = $request->contact_name;
            $contact->account_name            = $request->account_name;
            $contact->account_number          = $request->account_number;
            $contact->address                 = $request->address;
            $contact->address_two             = $request->address_two;
            $contact->address_three           = $request->address_three;
            $contact->city                    = $request->city;
            $contact->state                   = $request->state;
            $contact->zip                     = $request->zip;
            $contact->country                 = $request->country;
            $contact->province                = $request->province;
            $contact->website                 = $request->website;
            $contact->phone_number            = $request->phone_number;
            $contact->fax_number              = $request->fax_number;
            $contact->mailing_address         = $request->mailing_address;
            $contact->mailing_address_two     = $request->mailing_address_two;
            $contact->mailing_address_three   = $request->mailing_address_three;
            $contact->mail_city               = $request->mail_city;
            $contact->mail_state              = $request->mail_state;
            $contact->mail_zip                = $request->mail_zip;
            $contact->mail_country            = $request->mail_country;
            $contact->mail_province           = $request->mail_province;
            $contact->referral_source         = $request->referral_source;
            $contact->acc                     = $request->acc;
            $contact->platform                = $request->platform;
            $contact->trader_id               = $request->trader_id;
            $contact->comm_deal               = $request->comm_deal;
            $contact->locate_deal             = $request->locate_deal;
            $contact->platform_price          = $request->platform_price;
            $contact->program_member          = $request->program_member;
            $contact->cost_per_month          = $request->cost_per_month;
            $contact->shares_per_month        = $request->shares_per_month;
            $contact->time_limit              = $request->time_limit;
            $contact->field2                  = $request->field2;
            $contact->field3                  = $request->field3;
            $contact->field4                  = $request->field4;
            $contact->field5                  = $request->field5;
          	$contact->options_approval       = $request->options_approval;
            $contact->future_approval        = $request->future_approval;
            $contact->opt_cont_comm_pric     = $request->opt_cont_comm_pric;
            $contact->options_platform       = $request->options_platform;
            $contact->option_level_appr      = $request->option_level_appr;
            $contact->future_comm_pric       = $request->future_comm_pric;
            $contact->future_level_app       = $request->future_level_app;
            $contact->future_plat            = $request->future_plat;
            $contact->future_clear_firm      = $request->future_clear_firm;
            $contact->save();
			/*
            $results        = Contact::all();
            foreach($results as $result){
                    $getAllReferral = 0;
                    $getAllReferral = Contact::where('referral_source', $result->id)->get();
                    $contact = Contact::find($result->id);
                    if(!empty($contact)) {
                        $contact->total_referral = count($getAllReferral);
                        $contact->update();
                    }
            }

            $results = Contact::orderBy('total_referral', 'DESC')->orderBy('contact_name', 'ASC')->get();
           
          foreach($results as $key => $result){
                $contact = Contact::find($result->id);
                if(!empty($contact)) {
                    $contact->category_referral = $key + 1;
                    $contact->update();
                }
            }
            */
          $referralContact = Contact::where('id', $request->referral_source)->get();
            if(!empty($referralContact)) {
                foreach($referralContact as $result) {
                    $result->total_referral = (int)($result->total_referral) + 1;
                    $result->update();
                }
                
            }
            $results = Contact::orderBy('total_referral', 'DESC')->orderBy('contact_name', 'ASC')->select('id', 'category_referral')->get();
            foreach($results as $key => $result){
                $result->category_referral = $key + 1;
                $result->update();
            }
            //return response()->json(['message' => 'Contact updated successfully', 'status' => 200]);
          	  return response()->json(['message' => 'Contact updated successfully', 'status' => 200, 'data' => $oldContact->id]);
        } else {
            return response()->json(['message' => 'Contact not found', 'status' => 400]);
        }
    }

    public function getContactById(Request $request)
    {
        $contact_id = $request->contact_id;

        if(empty($contact_id)) {
            return response()->json(['message' => 'Contact id is required', 'status' => 400]);
        }

        $contactDetails = Contact::where('id', '=', $contact_id)->first();
        $userDetails = User::select('first_name', 'last_name')->where('id', '=', $contactDetails->user_id)->get();
        $contactDetails->userDetails = $userDetails;

        if(!empty($contactDetails)) {
            return response()->json(['message' => 'contact details', 'status' => 200, 'data'=> $contactDetails]);
        } else {
            return response()->json(['message' => 'Contact not found', 'status' => 400]);
        }
    }

    public function list(Request $request)
    {
     /*
      $results        = Contact::all();
      
      foreach ($results as $key => $result) {

            $oldContact = OldContact::where('contact_id', $result->id)->get();
            if($oldContact) {

                $result['oldRecord'] = $oldContact;
                $user = Auth::user();
                foreach ($result['oldRecord'] as $key => $data) {
                    $notifications = Notification::where('contact_id', $data->contact_id)->where('user_id', $user->id)->where('readMessage',0)->get();
                    if(!empty($notifications)) {
                        $data['notifications'] = $notifications;
                    } else {
                        $data['notifications'] = [];
                    }
                }

            } else {
                $result['oldRecord'] = [];
            }

        }
        $contactDetails = Datatables::of($results)
      		
            ->editColumn(
                'user_id',
                function ($results) {

                    $userDetail = User::select('first_name', 'last_name')->where('id', '=', $results->user_id)->get()->toArray();

                    return $userDetail;

                }
            )->editColumn(
                'account_name',
                function ($results) {

                    $accountDetail = Account::select('id','account_name')->where('id', '=', $results->account_name)->get()->toArray();

                    return $accountDetail;

                }
            )->escapeColumns([])->make(true);

        return response()->json(['message' => 'contacts list', 'status' => 200, 'data' => $contactDetails]);
        */
        $offset  = $request->get('start')? $request->get('start'): 0;
        $limit   = $request->get('length')? $request->get('length'): 1000000;
        $search  = $request->search? $request->search['value']: '';
        $counts  = Contact::count();
        $filtered  = Contact::where(
            function($query) use ($search) {
                if(!empty($search)) {
                    $query->where('contact_name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('acc', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
                }
                
        })->count();

        $contactDetails = Contact::where(
            function($query) use ($search) {
                if(!empty($search)) {
                    $query->where('contact_name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('acc', 'like', '%' . $search . '%')
                    ->orWhere('phone_number', 'like', '%' . $search . '%');
                }
                
        })->orderBy('total_referral', 'DESC')->orderBy('category_referral', 'ASC')->offset($offset)->limit($limit)->get();
		// ->select('id', 'contact_name', 'account_name', 'acc', 'phone_number', 'total_referral', 'category_referral', 'updated_at', 'user_id')
        foreach ($contactDetails as $key => $result) {

            $oldContact = OldContact::where('contact_id', $result->id)->get();
            if($oldContact) {

                $result['oldRecord'] = $oldContact;
                $user = Auth::user();
                foreach ($result['oldRecord'] as $key => $data) {
                    $notifications = Notification::where('contact_id', $data->contact_id)->where('user_id', $user->id)->where('readMessage',0)->get();
                    if(!empty($notifications)) {
                        $data['notifications'] = $notifications;
                    } else {
                        $data['notifications'] = [];
                    }
                }

            } else {
                $result['oldRecord'] = [];
            }

            $userDetail = User::select('first_name', 'last_name')->where('id', '=', $result->user_id)->get()->toArray();
            $result['user_id'] = $userDetail;

            $accountDetail = Account::select('id','account_name')->where('id', '=', $result->account_name)->get()->toArray();
            $result['account_name'] = $accountDetail;            

        }

        $finalResult['original']['data'] = $contactDetails;
        $finalResult['original']['recordsTotal'] = $counts;
        $finalResult['original']['recordsFiltered'] = $filtered;
      	return response()->json(['message' => 'contacts list', 'status' => 200, 'data' => $finalResult]);
    }

    public function deleteContact(Request $request)
    {
        $contact_id = $request->contact_id;

        if(empty($contact_id)) {
            return response()->json(['message' => 'Contact id is required', 'status' => 400]);
        }

        $contactDetails = Contact::find($contact_id);

        if(!empty($contactDetails)) {
            $contactDetails->delete();
            $notifications = Notification::where('contact_id', $contact_id)->get();
            if(!empty($notifications)) {
                foreach ($notifications as $key => $notification) {
                    $notification->delete();
                }
            }

            $oldContacts = OldContact::where('contact_id', $contact_id)->get();
            if(!empty($oldContacts)) {
                foreach ($oldContacts as $key => $oldContact) {
                    $oldContact->delete();
                }
            }
            return response()->json(['message' => 'Contact deleted successfully', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Acontact not found', 'status' => 400]);
        }
    }
  public function listByAccId(Request $request)
    {
        $id = $request->id ? $request->id: '';
        $contacts = Contact::where('account_name', '=', $id)->get();
        return response()->json(['message' => 'Contacts list', 'status' => 200, 'data' => $contacts]);
    }
}
