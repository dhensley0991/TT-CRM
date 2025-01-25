<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Account;
use App\Contact;
use App\User;
use DataTables;

class AccountController extends Controller
{
    public function create(Request $request)
    {
        $user_id = $request->user_id;

        if(empty($user_id)) {
            return response()->json(['message' => 'User id is required', 'status' => 400]);
        }

        $account                          = new Account();
        $account->user_id                 = $request->user_id;
        $account->account_name            = $request->account_name;
        $account->account_number          = $request->account_number;
        $account->address                 = $request->address;
        $account->address_two             = $request->address_two;
        $account->address_three           = $request->address_three;
        $account->city                    = $request->city;
        $account->state                   = $request->state;
        $account->zip                     = $request->zip;
        $account->country                 = $request->country;
        $account->province                = $request->province;
        $account->website                 = $request->website;
        $account->phone_number            = $request->phone_number;
        $account->fax_number              = $request->fax_number;
        $account->email                   = $request->email;
        $account->save();
        return response()->json(['message' => 'Account added successfully', 'status' => 200]);
    }

    public function updateAccount(Request $request)
    {
        $user_id = $request->user_id;

        if(empty($user_id)) {
            return response()->json(['message' => 'User id is required', 'status' => 400]);
        }

        $account_id = $request->account_id;

        if(empty($account_id)) {
            return response()->json(['message' => 'Account id is required', 'status' => 400]);
        }

        $account = Account::find($account_id);

        if(!empty($account)) {

            $account->user_id                 = $request->user_id;
            $account->account_name            = $request->account_name;
            $account->account_number          = $request->account_number;
            $account->address                 = $request->address;
            $account->address_two             = $request->address_two;
            $account->address_three           = $request->address_three;
            $account->city                    = $request->city;
            $account->state                   = $request->state;
            $account->zip                     = $request->zip;
            $account->country                 = $request->country;
            $account->province                = $request->province;
            $account->website                 = $request->website;
            $account->phone_number            = $request->phone_number;
            $account->fax_number              = $request->fax_number;
            $account->email                   = $request->email;
            $account->save();

            return response()->json(['message' => 'Account updated successfully', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Account not found', 'status' => 400]);
        }
    }

    public function getAccountById(Request $request)
    {
        $account_id = $request->account_id;

        if(empty($account_id)) {
            return response()->json(['message' => 'Account id is required', 'status' => 400]);
        }

        $accountDetails = Account::where('id', '=', $account_id)->first();
        $userDetails = User::select('first_name', 'last_name')->where('id', '=', $accountDetails->user_id)->get();
        $accountDetails->userDetails = $userDetails;


        if(!empty($accountDetails)) {
            return response()->json(['message' => 'account details', 'status' => 200, 'data'=> $accountDetails]);
        } else {
            return response()->json(['message' => 'Account not found', 'status' => 400]);
        }
    }

    public function list(Request $request)
    {
        $results        = Account::all();
        $accountDetails = Datatables::of($results)
            ->editColumn(
                'user_id',
                function ($results) {

                    $userDetail = User::select('first_name', 'last_name')->where('id', '=', $results->user_id)->get()->toArray();

                    return $userDetail;

                }
            )->escapeColumns([])->make(true);

        return response()->json(['message' => 'accounts list', 'status' => 200, 'data' => $accountDetails]);
    }

    public function deleteAccount(Request $request)
    {
        $account_id = $request->account_id;

        if(empty($account_id)) {
            return response()->json(['message' => 'Account id is required', 'status' => 400]);
        }

        $accountDetails = Account::find($account_id);

        if(!empty($accountDetails)) {
            $accountDetails->delete();
            $contacts = Contact::where('account_name', $account_id)->get();
            if(!empty($contacts)) {
                foreach ($contacts as $key => $contact) {
                    $contact->delete();
                }
            }
            return response()->json(['message' => 'Account deleted successfully', 'status' => 200]);
        } else {
            return response()->json(['message' => 'Acount not found', 'status' => 400]);
        }
    }
}
