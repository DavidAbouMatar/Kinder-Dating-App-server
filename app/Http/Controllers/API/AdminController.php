<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\UserPicture;
use App\Models\UserMessage;

use Auth;
use JWTAuth;

class AdminController extends Controller
{
    function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

		if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

		try{
			if(!$token = JWTAuth::attempt($validator->validated())){
				return response()->json(["error" => "Invalid Credentials"], 401);
			}
		}catch(JWTException $e){
			return json_encode(["error" => "Error occured"]);
		}
		
		$user = JWTAuth::user();
        $user_type = $user->user_type_id;
        if ($user_type == 1) {
            $user->token = $token;
            return json_encode($user);
        }else {
			return response()->json(["error" => "Invalid Credentials"], 401);
        }
	}

    function getNonApprovedImages(){
        $user = JWTAuth::user();
        $user_type = $user->user_type_id;

        if ($user_type == 1) {
            $images = UserPicture::where('is_approved','0')->get()->toArray();
            return json_encode($images);
        }else {
			return response()->json(["error" => "You are Unauthorized"], 401);
        }
    }

    function approveImage(Request $request){
        $user = JWTAuth::user();
        $user_type = $user->user_type_id;

        if ($user_type == 1) {
            $approved_img_id = $request->approved_img_id;
            $img = UserPicture::find($approved_img_id);
            $img->is_approved = 1;
            $img->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Image Approved!',
            ], 200);
        }else {
			return response()->json(["error" => "You are Unauthorized"], 401);
        }
    }

    function rejectImage(Request $request){
        $user = JWTAuth::user();
        $user_type = $user->user_type_id;

        if ($user_type == 1) {
            $rejected_img_id = $request->rejected_img_id;
            $img = UserPicture::find($rejected_img_id);
            $img->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'Image Rejected!',
            ], 200);
        }else {
			return response()->json(["error" => "You are Unauthorized"], 401);
        }
    }

    function getNonApprovedMsgs(){
        $user = JWTAuth::user();
        $user_type = $user->user_type_id;

        if ($user_type == 1) {
            $msgs = UserMessage::where('is_approved','0')->get()->toArray();
        
            return json_encode($msgs);
        }else {
			return response()->json(["error" => "You are Unauthorized"], 401);
        }
    }

    public function approveMsg(Request $request){
        $user = JWTAuth::user();
        $user_type = $user->user_type_id;

        if ($user_type == 1) {
            $approved_msg_id = $request->approved_msg_id;
            $msg = UserMessage::find($approved_msg_id);
            $msg->is_approved = 1;
            $msg->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Message Approved!',
            ], 200);
        }else {
			return response()->json(["error" => "You are Unauthorized"], 401);
        }
    }

    public function rejectMsg(Request $request){
        $user = JWTAuth::user();
        $user_type = $user->user_type_id;

        if ($user_type == 1) {
            $rejected_msg_id = $request->rejected_msg_id;
            $msg = UserMessage::find($rejected_msg_id);
            $msg->delete();
    
            return response()->json([
                'status' => true,
                'message' => 'Message Rejected!',
            ], 200);
        }else {
			return response()->json(["error" => "You are Unauthorized"], 401);
        }
    }

    public function getPendingCount(){
        $user = JWTAuth::user();
        $user_type = $user->user_type_id;
        
        if ($user_type == 1) {
            $pending_imgs_count = UserPicture::where('is_approved', 0)->get()->count();
            $pending_msgs_count = UserMessage::where('is_approved', 0)->get()->count();
            $pending_count = ["imgs_count"  =>  $pending_imgs_count, "msgs_count"  =>  $pending_msgs_count];
    
            return json_encode($pending_count);
        }else {
			return response()->json(["error" => "You are Unauthorized"], 401);
        }
    }

    public function autoMsgReview(){
        $banned_words = ["fuck", "shit", "ass", "butt", "dick", "penis", "pussy", "breasts", "asshole", "bitch", 'bastard', 'cunt', 'cum', 'bollocks', 'bugger', 'blood', 'kill', 'choad', 'crikey', 'rubbish', 'shag', 'wank', 'wanker', 'sperm', 'piss', 'twat', 'oath'];
        //Checking is any of the un-approved msgs contain any of the banned words!
        // for ($i=0; $i <count($unapproved_msgs) ; $i++) { 
        //     for ($j=0; $j < count($banned_words); $j++) { 
        //         # code...
        //     }
        // }
        // Approval if Message body does not contain any of the banned words
        UserMessage::where('is_approved', '=', 0)
        ->update(['is_approved' => '1']);
    
        return json_encode("Auto Message Reviewer is Running!");
    }
}
