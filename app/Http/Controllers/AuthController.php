<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ResponseFormatter;
use App\Models\Collector;
use Collator;
use Illuminate\Http\Response;
use Exception;

class AuthController extends Controller
{
    //fullname email phone_number password
    public function register(Request $request)
    {
        try {
            $field = $request->validate([
                "name" => "required|string",
                "phone_number" => "required|string|unique:users,phone_number",
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

            return ResponseFormatter::response(true, [
                'message' => 'Registration successful',
                "user" => $user,
                "token" => $token
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            if (isset($error->validator)) {
                return ResponseFormatter::response(false, [
                    'message' => 'Something went wrong',
                    'error' => $error->validator->getMessageBag(),
                ], $error->status);
            }
            return ResponseFormatter::response(false, [
                'message' => 'Something went wrong',
                'error' => $error,
            ], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();
            return ResponseFormatter::response(true, [
                'message' => 'User logout',
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            return ResponseFormatter::response(false, [
                'message' => 'Something went wrong',
                'error' => $error,
            ], 500);
        }
    }
    // email password
    public function login(Request $request)
    {
        try {
            // return response('sss');
            $field = $request->validate([
                "email" => "required|string",
                "password" => "required|string"
            ]);

            // check email
            $user = User::where('email', $field['email'])->first();
            // return response($user);
            if (!$user || !Hash::check($field['password'], $user->password)) {
                return response([
                    "message" => "bad credential"
                ], 401);
            }

            $token = $user->createToken("apptani")->plainTextToken;

            return ResponseFormatter::response(true, [
                'message' => 'Login successful',
                "user" => $user,
                "token" => $token
            ], Response::HTTP_OK);
        } catch (Exception $error) {
            if (isset($error->validator)) {
                return ResponseFormatter::response(false, [
                    'message' => 'Something went wrong',
                    'error' => $error->validator->getMessageBag(),
                ], $error->status);
            }
            return ResponseFormatter::response(false, [
                'message' => 'Something went wrong',
                'error' => $error,
            ], 500);
        }
    }

    public function no_access(){
        return ResponseFormatter::response(false, [
            'message' => 'No Access',
        ], 403);
    }
}
