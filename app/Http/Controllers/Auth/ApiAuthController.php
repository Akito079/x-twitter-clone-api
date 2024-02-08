<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiAuthController extends Controller
{
    public function login(Request $request){
        $validators = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validators->fails()){
            return response()->json(['errors'=>$validators->errors()->all()],422);
        }

        $user = User::where('email',$request->email)->first();
        if($user){
            if(Hash::check($request->password,$user->password)){
                $token = $user->createToken('Sanctum login')->plainTextToken;
                return response()->json(['token'=>$token],200);
            }else{
                return response()->json(['message'=>'Incorrect Password'],404);
            }
        }else{
            return response()->json(['message'=>'User does not exist'],404);
        }
    }

    public function register(Request $request){
        $validators = Validator::make($request->all(),[
            'name' => 'required',
            'email' => 'required|unique:users',
            'nickName' => 'required',
            'password' => 'required',
            'passwordConfirm' => 'required|same:password',
        ]);

        if($validators->fails()){
            return response()->json(['errors'=>$validators->errors()->all()],422);
        }

        $user = User::create([
            'name' => $request->name,
            'nick_name' => $request->nickName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('Sanctum register')->plainTextToken;
        return response()->json(['token' => $token],200);
    }

    public function logout(Request $request){
        $token = $request->user()->tokens();
        $token->delete();
        return response()->json(['message'=>'Logged out successfully']);
    }
}
