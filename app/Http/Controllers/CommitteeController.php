<?php

namespace App\Http\Controllers;

use App\Http\Requests\JoinCommitteeRequest;
use App\Http\Requests\StoreCommitteeRequest;
use App\Models\Committee;
use App\Models\CommitteeMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Throwable;

use function PHPUnit\Framework\returnValue;

class CommitteeController extends Controller
{
    public function store(StoreCommitteeRequest $request){
   
       try{
           $owner = Auth::user();
           $validate_fields = $request->validated();
           $validate_fields['owner_id'] = $owner->id; 
          
        do {
            $code_no = mt_rand(100,999);
            $committee_name = $validate_fields['committee_name']; 
            $committee_code = substr(strtoupper($committee_name),0,3).$code_no; 
        } while (Committee::where('committee_code', $committee_code)->exists());

        $validate_fields['committee_code'] = $committee_code; 

        $committee = Committee::create($validate_fields);
        return response()->json([
                'status' => true,
                'message' => "Committes Created Successfully",
                'committee' => $committee,
                'owner' => $owner 
            ],201);
       }
       catch(Throwable $th){
            return response()->json([
                'status' => false,
                'message' => "Something wents wrong! Please try again later",
                'error' => $th->getMessage(),
            ],500);
       }


    }
    
    public function join(JoinCommitteeRequest $request){
        try{
            $member = Auth::user();
            $validate_fields = $request->validated();
            $validate_fields['user_id'] = $member->id;
            $committee = Committee::find($validate_fields['committee_id']);

            $member_exists = CommitteeMember::where(
                [
                    'user_id'=> $validate_fields['user_id'], 
                     'committee_id' => $validate_fields['committee_id']
                ]
                )->exists();

                if($member_exists){
                    return response()->json([
                        'status' => false,
                        'message' => "You're already Exists in this Commitee",
                    ],400);
                }   
            
            $member = CommitteeMember::create($validate_fields);
            return response()->json([
                'status' => true,
                'message' => "Congratulation You're a member of this Committee",
                'member' => $member,
                'committee' => $committee
            ],201);
            // return $member;
            // die;

            // if($validate_fields['user_id'] = $member->id && $validate_fields['committee_id']){
            //     return "youre already in this committee";
            //     die;
            // }

        }
        catch(Throwable $th){   
            return response()->json([
                'status' => false,
                'message' => "Something wents wrong, please try again later",
                'error' => $th->getMessage()
            ]);

        }
    }
}
