<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
            'profileImage' => 'required|mimes:png,jpg,jpeg,webp',
            'nickName' => 'required',
            'password' => 'required',
            'passwordConfirm' => 'required|same:password',
        ]);

        if($validators->fails()){
            return response()->json(['errors'=>$validators->errors()->all()],422);
        }

        $file = $request->file('profileImage');
        $fileName = uniqid().$file->getClientOriginalName();
        $file->move(public_path()."/profileImages",$fileName);
        $user = User::create([
            'name' => $request->name,
            'nick_name' => $request->nickName,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_image' => $fileName,
        ]);

        $token = $user->createToken('Sanctum register')->plainTextToken;
        return response()->json(['token' => $token],200);
    }

    public function logout(Request $request){
        $token = $request->user()->tokens();
        $token->delete();
        return response()->json(['message'=>'Logged out successfully']);
    }

    public function updateProfile(Request $request){
        $validators = Validator::make($request->all(),[
            "name" => "required",
            "email" => "email|required|unique:users,email,".$request->user()->id,
            "nickName" => "required",
        ]);

        if($validators->fails()){
            return response()->json(['errors'=>$validators->errors()->all()],422);
        }

        $data = [
            "name" => $request->name,
            "email" => $request->email,
            "nick_name" => $request->nickName,
        ];
        $file = $request->file("profileImage");
        if($request->hasFile("profileImage")){
            $dbImage = User::where("id",$request->user()->id)->first();
            $dbImage = $dbImage->profile_image;
            File::delete(public_path() . "/profileImages/" . $dbImage);
            $fileName = uniqid() . $request->file('profileImage')->getClientOriginalName();
            $file->move(public_path() . "/profileImages", $fileName);
            $data["profile_image"] = $fileName;
        }
        User::where("id",$request->user()->id)->update($data);
        return response()->json(['message'=>'account update success'],200);
    }

    public function changePassword(Request $request){

        $validators = Validator::make($request->all(),[
            "oldPassword" => "required",
            "newPassword" =>"required",
            "confirmPassword" => "same:newPassword",
        ]);

        if($validators->fails()){
            return response()->json(['errors'=>$validators->errors()->all()],422);
        }

        $user = User::select('password')->where('id', $request->user()->id)->first();
        $dbHashValue = $user->password;
        if (Hash::check($request->oldPassword, $dbHashValue)) {
            User::where('id',$request->user()->id)->update([
                'password' => Hash::make(
                    $request->newPassword
                )
            ]);
            return response()->json(['message' => 'Password Changed'],200);
        }
        return response()->json(['message' => 'Password did not match'],422);
    }
}
