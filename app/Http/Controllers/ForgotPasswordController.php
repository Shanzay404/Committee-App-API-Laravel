<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ForgotPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        try{
            $validateEmail=Validator::make(
                $request->all(),
                [
                    'email' => 'required|email|exists:users,email'
                ]
            );
            if($validateEmail->fails()){
                return response()->json([
                    'status' => false,
                    'message' => "Authentication Error",
                    'error' => $validateEmail->errors()->first()
                ],404);
            }
            $otp=mt_rand(10000,99999);
            $save_otp=DB::table('password_resets')->insert([
                'email' => $request->email,
                'otp' => $otp,
                'created_at' => Carbon::now(),
            ]);
            // send otp to user email
            Mail::raw('Your Reset Password OTP is: '.$otp, function ($message) use ($request) {
                $message->to($request->email)
                ->subject('Committee App: Reset Password');
            });
            
            return response()->json([
                'status' => true,
                'message' => "OTP has been sent to your registered email",
                'otp' => $otp
            ],201);
            
        }
        catch(Throwable $th){
            return response()->json([
                'status' => false,
                'message' => "Server Down try again later",
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    // verify otp
    public function verify_otp(Request $request)
    {
        try{
            $validateData=Validator::make(
                $request->all(),
                [
                    'email' => "required|email|exists:users,email",
                    'otp' => 'required|numeric|digits:5'
                ]
            );
            if($validateData->fails()){
                return response()->json([
                    'status' => false,
                    'message' => "Validation Error",
                    'errors' => $validateData->errors()->first()
                ],401);
            }
            $otp_exists = DB::table('password_resets')
                        ->where([
                            'email'=>$request->email,
                            'otp'=>$request->otp,
                        ])->first();
            if(!$otp_exists){
                return response()->json([
                    'status' => false,
                    'message' => "Please Enter a valid OTP",
                ],404);     
            }
            $otp_created_at = Carbon::parse($otp_exists->created_at);
            if($otp_created_at->diffInMinutes(Carbon::now()) > 1){
                return response()->json([
                    'status' => false,
                    'message' => "Otp expired, please request a new one",
                ],410);     
            }
            return response()->json([
                'status' => True,
                'message' => 'OTP verified Successfully'
            ],200);

        }catch(Throwable $th){
            return response()->json([
                'status' => false,
                'message' => "Server Down Please try Later",
                'errors' => $th->getMessage()
            ],500);
        }
    }
    // change password
    public function change_password(Request $request)
    {
        try{

            $validateData=Validator::make(
                $request->all(),
            [
                'email' => "required|email|exists:users,email",
                'password' =>  'required|string|min:8|max:15|confirmed',
            ]);
            if($validateData->fails()){
                return response()->json([
                    'status' => false,
                    'message' => "Validation Error",
                    'errors' => $validateData->errors()->first()
                ],401);
            }
            $user=User::where(['email' => $request->email])->first();
            $user->update(['password' => $request->password]);
            return response()->json([
                'status' => true,
                'message' => "Password has been changed Successfully",
            ],200);
        }
        catch(Throwable $th){
            return response()->json([
                'status' => false,
                'message' => "Server Down Please try Later",
                'errors' => $th->getMessage()
            ],500);
        }
    }
}
