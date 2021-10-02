<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

use App\Models\User;
use App\Models\UserConnection;
use App\Models\UserFavorite;
use App\Models\UserNotification;
use App\Models\UserHobby;
use App\Models\UserPicture;
use App\Models\UserMessage;

use Auth;
use JWTAuth;
use Storage;


class UserController extends Controller{
	
	// testing function. temp.
	public function test() {
		$user = JWTAuth::user();
		$id = $user->id;
		return json_encode(JWTAuth::user());
	}

	// get only highlighted users to home page (no authintication needed)
	public function highlighted(){
		$highlighted_users = User::where("is_highlighted", 1)->limit(6)->get()->toArray();
		return json_encode($highlighted_users);
	}

	// get all users with preferred gender to user 
	public function getUsers(){
		$user = JWTAuth::user();
		$id = $user->id;
		$user_data = User::select('*')
						 ->where('id', $id)   
					     ->get();
		$interested_in = $user_data[0]['interested_in'];

		$users = User::select('first_name', 'last_name', 'dob', 'net_worth')
					 ->where('gender', $interested_in)
					 ->get();
					
		return json_encode($users);
	}

	// edit profile. profile picture not included yet
	public function edit_profile(Request $request) {

		$validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
			'gender' => 'required|integer',
			'interested_in' => 'required|integer',
			'dob' => 'required',
			'net_worth' => 'required|integer',
			'currency' => 'required|string',
			'bio' => 'required|string|between:2,200'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                "status" => false,
                "errors" => $validator->errors()
            ), 400);
        }
		
		$user = JWTAuth::user();
		$id = $user->id;
		$user::where('id', $id)
		 	 ->update([
				"first_name" => $request -> first_name,
				"last_name" => $request -> last_name,
				"gender" => $request -> gender,
				"interested_in" => $request -> interested_in,
				"dob" => $request -> dob,
				"net_worth" => $request -> net_worth,
				"currency" => $request -> currency,
				"bio" => $request -> bio,
			]);
		return response()->json([
			'status' => true,
			'message' => 'User profile successfully updated',
		], 201);
	}	

	// returns all user hobbies
	public function getUserHobbies() {
		$user = JWTAuth::user();
		$id = $user->id;

		$user_data = UserHobby::select('name')
							  ->where('user_id', $id)   
							  ->get();
		
		foreach ($user_data as $name => $hobby) {
			$user_hobbies[] = $hobby->name;
		}

		return json_encode($user_hobbies);
	}

	// returns all hobbies in table user_hobbies
	public function getHobbies() {
		$user_data = UserHobby::select('name')->get();
		
		foreach ($user_data as $name => $hobby) {
			$hobbies[] = $hobby->name;
		}
		return json_encode($hobbies);
	}

	// add hobbies to table user_hobbies
	public function addHobbies(Request $request) {
		$user = JWTAuth::user();
		$id = $user->id;

		foreach ($request->all() as $name => $hobby) {
			$Hobby = new UserHobby;
			$Hobby->user_id = $id;
			$Hobby->name = $hobby;
			$Hobby->save();
		}

		return response()->json([
            'status' => true,
            'message' => 'Hobbies added successfully.',
        ], 201);
	}

	//search users by keyword
	function search($keyword = null){
		$user = JWTAuth::user();
		$id = $user->id;
		$search = User::where('id','!=',$id)
					  ->where('first_name','like',$keyword.'%')
					  ->orwhere('last_name','like',$keyword.'%')
					  ->limit(6)
					  ->get()
					  ->toArray();
	
		return json_encode($search);
	}

	//save image to storage/app/public/uploads
	// run php artisan storage:link when testing
	public function uploadImage(Request $request){
		$user = Auth::user();
		$id = $user->id;

		$validator = Validator::make($request->all(), [
			'image' => 'required|image:jpeg,png,jpg,gif,svg'
		]);

		if ($validator->fails()) {
			return response()->json(array(
				"status" => false,
				"errors" => $validator->errors()
			), 400);
		}
		
		$fileModel = new UserPicture();

		if($request->file()) {
			$fileName = $request->image->getClientOriginalName();
			$filePath = $request->file('image')->storeAs('uploads', $fileName, 'public');
			$fileModel->user_id =$id;
			$fileModel->is_approved ='0';
			$fileModel->is_profile_picture ='1';
			$fileModel->picture_url = $request->image->getClientOriginalName();
			$fileModel->picture_url = 'http://127.0.0.1:8000' . '/storage/' . $filePath;
			$fileModel->save();
			
			return response()->json([
				'status' => true,
				'message' => 'User profile successfully updated',
			], 201);

		}
	}

	// add user to favorites, and decide whether there is a match or not
	public function addToFavorites(Request $request)
    {
		$user_id =JWTAuth::user()->id;
		$receiver_id = $request->receiver_id;

		UserFavorite::insert([
			'from_user_id' => $user_id,
			'to_user_id' => $receiver_id,
			'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
		]);

		$is_match =  UserFavorite::where('from_user_id', $receiver_id)
							     ->where('to_user_id', $user_id)
								 ->get()
								 ->count();

		$user1 = User::find($user_id);
		$user1_fullname = $user1->first_name.' '.$user1->last_name;

		if ($is_match) {
			$user2 = User::find($receiver_id);
			$user2_fullname = $user2->first_name.' '.$user2->last_name;

			UserConnection::insert([
				'user1_id' => $user_id,
				'user2_id' => $receiver_id,
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')	
			]);

			UserNotification::insert([
				'user_id' => $user_id,
				'body' => "You are a Match with $user2_fullname!",
				'is_read' => 0,
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')	
			]);

			UserNotification::insert([
				'user_id' => $receiver_id,
				'body' => "You are a Match with $user1_fullname!",
				'is_read' => 0,
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')	
			]);

		}else {
			UserNotification::insert([
				'user_id' => $receiver_id,
				'body' => "Hey! $user1_fullname tapped you!",
				'is_read' => 0,
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')	
			]);
		}

		return response()->json([
            'status' => true,
            'message' => 'User added to Favorites!',
        ], 201);
    }

	// send msg to a match
	public function sendMsg(Request $request)
	{
		$user_id = JWTAuth::user()->id;
		$receiver_id = $request->receiver_id;
		$msg_body = $request->msg_body;

		$is_match = UserConnection::where('user1_id', $user_id)
									->where('user2_id', $receiver_id)
									->orwhere('user1_id', $receiver_id)
									->where('user2_id', $user_id)
									->get()->count();

		if ($is_match) {
			UserMessage::insert([
				'sender_id' => $user_id,
				'receiver_id' => $receiver_id,
				'body' => $msg_body,
				'is_approved' => 0,
				'is_read' => 0,
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')	
			]);

			return response()->json("Message sent successfully!");
		}else {
			return response()->json("Can't send message. You are not a Match yet!");
		}

	}
}

?>