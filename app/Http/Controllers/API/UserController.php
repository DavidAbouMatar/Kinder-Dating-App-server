<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;

class UserController extends Controller{
	
	function highlighted(){
		$highlighted_users = User::where("is_highlighted", 1)->limit(6)->get()->toArray();
		return json_encode($highlighted_users);
	}

	function getUsers($id){
		$user_data = User::select('*')
						 ->where('id', $id)   
					     ->get();
		$interested_in = $user_data[0]['interested_in'];

		$users = User::select('first_name', 'last_name', 'dob', 'net_worth')
					 ->where('gender', $interested_in)
					 ->get();
					
		return json_encode($users);
	}

	function edit_profile(Request $request) {

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
		
		$user = Auth::user();
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

	
	function test(){
		$user = Auth::user();
		$id = $user->id;
		return json_encode(Auth::user());
	}

}

?>