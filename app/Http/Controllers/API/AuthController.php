<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        try{
            $validateUser = Validator::make(
                $request->all(),
                [
                    'username' => 'required|string',
                    'email' => 'required|email|unique:users,email',
                    'phone_no' => 'required|digits_between:11,13',
                    'password' => 'required|string|min:8|max:15|confirmed',
                    "password_confirmation" => "required",
                ]
            );
    
            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => "Validation Error",
                    'error' => $validateUser->errors()->first()
                ],401);
            }
            $user=User::create([
                'username' => $request->username,
                'email' => $request->email,
                'phone_no' => $request->phone_no,
                'password' => $request->password,
            ]);
            // create token for signup
            $registrationToken = $user->createToken("Signup Token")->plainTextToken;
            return response()->json([
                'status' => true,
                'message' => "Account Created Successfully",
                'token' => $registrationToken,
                'user' => $user
            ],201);
        }catch(Throwable $th){
            return response()->json([
                'status' => false,
                'message' => "Server down! Please try again later",
                'error' => $th->getMessage(),
            ],500);
        }
    }
    public function login(Request $request)           // login
    {
        try {
            $validateUser = Validator::make(
                $request->all(),
                [
                    'email' => 'required|email',
                    'password' => 'required'
                ]
            );
            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => "Authentication Fails",
                    'error' => $validateUser->errors()->first()
                ],404);
            }
            if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                $authUser = Auth::user();
                return response()->json([
                    'status' => true,
                    'message' => "Logged In Successfully",
                    'token' => $authUser->createToken("Login Token")->plainTextToken,
                    'user' => $authUser,
                ], 200);   
            }else{
                return response()->json([
                    'status' => false,
                    'message' => "Invalid Credentials, Password or email doesn't match",
                ],401);
            }
        } catch (Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => "Server down! Please try again later",
                'error' => $th->getMessage(),
            ],500);
        }
    }
    public function logout(Request $request)
    {
        try{    
            $user = $request->user();
            $user->tokens()->delete();
            return response()->json([
                'status' => true, 
                'message' => "You've been Logged out Successfully"
            ],200);
        }
        catch(Throwable $th){
            return response()->json([
                'status' => false,
                'message' => "Server down! Please try again later",
                'error' => $th->getMessage(),
            ],500);
        }
    }
}
