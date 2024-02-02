<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{
    //register
    public function register(Request $request)
    {
        $validators = Validator::make($request->all(), [
            "name" => ["required"],
            "nickName" => ["required"],
            "email" => ["required", "email", "unique:users"],
            "password" => ["required", "min:6"],
            "passwordConfirm" => ["required", "same:password"],
        ]);

        if ($validators->fails()) {
            return response()->json(['errors' => $validators->errors()->all()], 422);
        }

        $data = [
            "name" => $request->name,
            "nick_name" => $request->nickName,
            "email" => $request->email,
            "password" => Hash::make($request->password),
            "remember_token" => Str::random(10),
        ];
        $user = User::create($data);
        $token = $user->createToken("Laravel Password Grant Client")->accessToken;
        return response()->json(['token' => $token], 200);
    }

    //login
    public function login(Request $request)
    {
        $validators = Validator::make($request->all(), [
            "email" => ["required", "email"],
            "password" => ["required", "min:6"],
        ]);

        if ($validators->fails()) {
            return response()->json(['errors' => $validators->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Token')->accessToken;
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['message' => 'Password mismatch'], 422);
            }
        } else {
            return response()->json(['message' => 'User does not exist'], 422);
        }
    }

    //logout
    public function logout(Request $request)
    {
        $token = $request->user()->token();
        $token->revoke();
        return response()->json(['message' => 'You have logged out successfully'], 200);
    }
}
