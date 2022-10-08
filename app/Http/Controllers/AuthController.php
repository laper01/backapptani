<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseFormatter;
use App\Models\Collector;
use Collator;
use Illuminate\Http\Response;

class AuthController extends Controller
{
    //fullname email phone_number password
    public function register(Request $request)
    {
        $field = $request->validate([
            "name" => "required|string",
            "phone_number" => "required|string",
            "email" => "required|string|unique:users,email",
            "password" => "required|string|confirmed"
        ]);

        $user = User::create([
            'name' => $field["name"],
            'phone_number' => $field['phone_number'],
            'email' => $field['email'],
            'password' => bcrypt($field['password'])
        ]);

        $collector = new Collector();
        $collector->user_id = $user->id;
        $collector->save();

        $token = $user->createToken("apptani")->plainTextToken;
        $response = [
            "user" => $user,
            "token" => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return [
            'message' => 'loggout'
        ];
    }
    // email password
    public function login(Request $request)
    {
        // return response('sss');
        $field = $request->validate([
            "email" => "required|string",
            "password" => "required|string"
        ]);

        // check email
        $user = User::where('email',$field['email'])->first();
        // return response($user);
        if(!$user || !Hash::check($field['password'], $user->password)){
            return response([
                "message" => "bad credential"
            ],401);
        }

        $token = $user->createToken("apptani")->plainTextToken;
        $response = [
            "user" => $user,
            "token" => $token
        ];

        return response($response, 201);
    }
}
