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
		
		$user = Auth::user();
        $user_type = $user->user_type_id;
        if ($user_type == 1) {
            $user->token = $token;
            return json_encode($user);
        }else {
			return response()->json(["error" => "Invalid Credentials"], 401);
        }
	}

    function getNonApprovedImages(){
        $images = UserPicture::where('is_approved','0')->get()->toArray();
        
        return json_encode($images);

    }

    function approveImage(Request $request){
        $approved_img_id = $request->approved_img_id;
        $img = UserPicture::find($approved_img_id);
        $img->is_approved = 1;
        $img->save();

        return response()->json([
            'status' => true,
            'message' => 'Image Approved!',
        ], 200);
    }

    function rejectImage(Request $request){
        $rejected_img_id = $request->rejected_img_id;
        $img = UserPicture::find($rejected_img_id);
        $img->delete();

        return response()->json([
            'status' => true,
            'message' => 'Image Rejected!',
        ], 200);
    }


    function getNonApprovedMsgs(){
        $images = UserMessage::where('is_approved','0')->get()->toArray();
        
        return json_encode($images);

    }

    public function approveMsg(Request $request)
    {
        $approved_msg_id = $request->approved_msg_id;
        $msg = UserMessage::find($approved_msg_id);
        $msg->is_approved = 1;
        $msg->save();

        return response()->json([
            'status' => true,
            'message' => 'Message Approved!',
        ], 200);
    }

    public function rejectMsg(Request $request)
    {
        $rejected_msg_id = $request->rejected_msg_id;
        $msg = UserMessage::find($rejected_msg_id);
        $msg->delete();

        return response()->json([
            'status' => true,
            'message' => 'Message Rejected!',
        ], 200);
    }

    public function getPendingCount()
    {
        $pending_imgs_count = UserPicture::where('is_approved', 0)->get()->count();
        $pending_msgs_count = UserMessage::where('is_approved', 0)->get()->count();
        $pending_count = ["imgs_count"  =>  $pending_imgs_count, "msgs_count"  =>  $pending_msgs_count];

        return json_encode($pending_count);
    }
}
