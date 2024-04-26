<?php

namespace App\Http\Controllers\api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    public function newUser(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'username' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'Invalid email address'], 400);
        } else {
            $email = User::where('email', $request->email)->first();
            $username = User::where('username', $request->username)->first();

            if ($email) {
                return response()->json(['message' => 'Email already registered'], 409);
            } elseif ($username) {
                return response()->json(['message' => 'Username already taken'], 409);
            } else {
                try {
                    $user = User::create([
                        'id_user' => $this->makerUserID(),
                        'email' => $request->email,
                        'pass_hash' => Hash::make($request->password),
                        'username' => $request->username,
                        'creation_date' => Carbon::now(),
                        'id_user_type' => 1
                    ]);
                } catch (\Throwable $e) {
                    $data = [
                        'status' => 500,
                        'message' => "Database Error",
                        'error' => $e
                    ];
                    return response()->json($data, 500);
                };

                if ($user) {
                    $data = [
                        'status' => 200,
                        'message' => "User Account Created Successfully"
                    ];
                    return response()->json($data, 200);
                } else {
                    $data = [
                        'status' => 500,
                        'message' => "Error Creating Account"
                    ];
                    return response()->json($data, 500);
                }
                #return response()->json(['status' => 503,'message' => 'New User: Under Construction'], 418);
            }
        }
    }

    public function logIn(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|max:255',
            'password' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        } elseif (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'Invalid email address'], 400);
        } else {

            $user = User::where('email', $request->email)->first();

            if ($user && (Hash::check($request->password, $user->pass_hash))) {
                return response()->json(['message' => 'Login successful', 'user' => $user], 200);
            } else {
                return response()->json(['message' => 'Invalid credentials'], 401);
            }
        }
    }

    private function makerUserID()
    {
        $userID = Str::uuid();
        $userIDCheck = User::where('id', $userID)->first();
        if ($userIDCheck) {
            $userID = $this->makerUserID();
        }
        return $userID;
    }
}
