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
use Auth;
use Storage;


class UserController extends Controller{
	
	// testing function. temp.
	public function test() {
		$user = Auth::user();
		$id = $user->id;
		return json_encode(Auth::user());
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
		$user = Auth::user();
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
		$user = Auth::user();
		$id = $user->id;
		$search = User::where('id','!=',$id)->where('first_name','like',$keyword.'%')->orwhere('last_name','like',$keyword.'%')->limit(6)->get()->toArray();
	
		return json_encode($search);
	}

	//save image to storage/app/public/uploads
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
}

?>