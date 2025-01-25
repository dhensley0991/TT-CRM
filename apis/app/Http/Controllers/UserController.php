<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use DataTables;
use App\Services\Common;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
	
  	public function addUser(Request $request)
    {
        // $id           = $request->user_id;
        if($request->input('password') != $request->input('confirm_password')) {
            return response()->json(['message' => 'Confirm password should be same as password', 'status' => 400]);
        }
        
        if(sizeof(User::where('email','=', $request->input('email'))->get()) > 0){
            return response()->json([
                'status' => 400,
                'message' => 'Username already exists.Enter unique username.',
                'data' => []
            ]);
        }

        if(sizeof(User::where('email_address','=', $request->input('email_address'))->get()) > 0){
            return response()->json([
                'status' => 400,
                'message' => 'Email address already exists.Enter unique email address.',
                'data' => []
            ]);
        }
        $user = new User();

        $user->first_name    = $request->input('first_name');
        $user->last_name     = $request->input('last_name');
        $user->email         = $request->input('email');
        $user->email_address = $request->input('email_address');
        $user->phone         = $request->input('phone');
        $user->user_type     = 2;
        $user->password      = bcrypt($request->input('password'));
        $user->save();

        if (!empty($user)) {
            return response()->json(['message' => 'User added successfully', 'status' => 200]);

        } else {
            return response()->json(['message' => 'User not added', 'status' => 400]);
        }
    }
    public function update(Request $request)
    {
        $id           = $request->user_id;
        if(empty($id)) {
            return response()->json(['message' => 'User id is required', 'status' => 400]);
        }
        $user = User::find($id);

        if (!empty($user)) {
          
            if(sizeof(User::where('email','=', $request->input('email'))->where('id', '!=',  $id)->get()) > 0){
                return response()->json([
                    'status' => 400,
                    'message' => 'Username already exists.Enter unique username.',
                    'data' => []
                ]);
            }

            if(sizeof(User::where('email_address','=', $request->input('email_address'))->where('id', '!=',  $id)->get()) > 0){
                return response()->json([
                    'status' => 400,
                    'message' => 'Email address already exists.Enter unique email address.',
                    'data' => []
                ]);
            }

            $user->first_name    = $request->input('first_name');
            $user->last_name     = $request->input('last_name');
            $user->email         = $request->input('email');
            $user->email_address = $request->input('email_address');
            $user->phone         = $request->input('phone');
            $user->update();

            return response()->json(['message' => 'User updated successfully', 'status' => 200]);

        } else {
            return response()->json(['message' => 'User not found', 'status' => 400]);
        }
    }

    public function list(Request $request)
    {
        $users = Datatables::of(User::all())->make(true);
        // $users = User::all();

        return response()->json(['message' => 'users list', 'status' => 200, 'data' => $users]);
    }

    public function getUserById(Request $request)
    {
        $id = $request->input("user_id");

        if(empty($id)) {
            return response()->json(['message' => 'User id is required', 'status' => 400]);
        }

        $userDetails = User::where('id', '=', $id)->first();
        if (!empty($userDetails)) {

            return response()->json(['message' => 'user details', 'status' => 200, 'data'=> $userDetails]);

        } else {
            return response()->json(['message' => 'user not found', 'status' => 400]);
        }
    }

    public function updatePassword(Request $request)
    {
        $id           = $request->user_id;
        if(empty($id)) {
            return response()->json(['message' => 'User id is required', 'status' => 400]);
        }

        if(empty($request->input('password'))) {
            return response()->json(['message' => 'Password is required', 'status' => 400]);
        }

        $user = User::find($id);

        if (!empty($user)) {

            $user->password      = bcrypt($request->input('password'));
            $user->update();

            return response()->json(['message' => 'User password updated successfully', 'status' => 200]);

        } else {
            return response()->json(['message' => 'User not found', 'status' => 400]);
        }
    }
}