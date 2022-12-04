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
                "password" => "required|string"
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
            $user->token = $token;

            return ResponseFormatter::response(true, [
                $user,
            ], Response::HTTP_OK, 'Registrasi berhasil');
        } catch (Exception $error) {
            if (isset($error->validator)) {
                return ResponseFormatter::response(false, null, $error->status,  $error->validator->getMessageBag());
            }
            return ResponseFormatter::response(false, null, 500, "Ada yang salah");
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();
            return ResponseFormatter::response(true, null, Response::HTTP_OK, "User berhasil keluar");
        } catch (Exception $error) {
            return ResponseFormatter::response(false, null, 500, "Ada yang salah");
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
                return ResponseFormatter::response(false, null, 401, "email atau password salah");
            }
            $token = $user->createToken("apptani")->plainTextToken;
            $user->token = $token;

            return ResponseFormatter::response(true, [
                $user,
            ], Response::HTTP_OK, "Login berhasil");
        } catch (Exception $error) {
            if (isset($error->validator)) {
                return ResponseFormatter::response(false, null, $error->status, $error->validator->getMessageBag(),);
            }
            return ResponseFormatter::response(false, null, 500, "Ada yang salah");
        }
    }

    public function no_access()
    {
        return ResponseFormatter::response(false, null, 403, "No access");
    }
}
