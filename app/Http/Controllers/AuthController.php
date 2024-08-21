<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    //

    public function signup(Request $request)
    {

        /**validate incoming user request */
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:25',
        ]);


        /**check if validation fails */
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (User::where('email', $request->email)->first()) {
            return response()->json([
                'message' => ['Email has already been taken'],
            ], 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $response = new \stdClass();
        $response->user = $user;
        $response->message = 'User Created.';
        $response->success = true;

        return response()->json($response, 201);
    }

    public function login(Request $request)
    {

        /**validate incoming user request */
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|max:25',
        ]);

        /**check if validation fails */
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                "errors" => [
                    'Could not find any record for email.'
                ]
            ], 400);
        }




        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                "errors" => [
                    'Could not find any record for email.'
                ]
            ], 400);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'errors' => ['The provided credentials are incorrect.'],
            ], 400);
        }

        //create a verification otp for user

        // Generate a token for the user
        $token = $user->createToken('auth_token')->accessToken;



        $response = new \stdClass();
        $response->message = 'Login successful';
        $response->token = $token;
        $response->success = true;

        return response()->json($response, 200);
    }
}
