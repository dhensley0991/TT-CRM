<?php

namespace App\Http\Controllers;

use App\User;
use App\Notification;
use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Services\Common;
use JWTAuth;
use Config;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid Credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
        $user = Auth::user('id', 'first_name', 'last_name', 'email', 'user_type', 'phone');
        $notifications = Notification::where('user_id', $user->id)->where('readMessage', 0)->get();
        if($notifications) {

            foreach ($notifications as $key => $notificationList) {
                $contactDetails = Contact::where('id', $notificationList->contact_id)->first();
                if(!empty($contactDetails)) {
                    $notificationList['contact_data'] = $contactDetails;
                }
            }
        }
        return response()->json(['message' => 'Login Success', 'status' => 200, 'data' => compact('token', 'user', 'notifications')]);
        //return response()->json(['message' => 'Login Success', 'status' => 200, 'data' => compact('token', 'user')]);
    }


    public function getAuthenticatedUser()
    {
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['User not found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['Token expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['Token Invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['Token Absent'], $e->getStatusCode());

        }

        return response()->json(compact('user'));
    }


    public function userProfile(Request $request)
    {
        $user = Auth::user();
        if (!empty($user)) {
            $notifications = Notification::where('user_id', $user->id)->where('readMessage', 0)->get();
            if($notifications) {

                foreach ($notifications as $key => $notificationList) {
                    $contactDetails = Contact::where('id', $notificationList->contact_id)->first();
                    if(!empty($contactDetails)) {
                        $notificationList['contact_data'] = $contactDetails;
                    }
                }
            }
            return response()->json(['message' => 'User Info', 'status' => 200, 'data' => compact('user', 'notifications')]);
            //return response()->json(['message' => 'User Info', 'status' => 200, 'data' => $user]);

        } else {
            return response()->json(['message' => 'Something went wrong', 'status' => 400]);
        }
    }
}
