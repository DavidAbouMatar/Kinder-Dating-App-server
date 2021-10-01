<?php
namespace App\Http\Controllers\API;

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

		$users = User::select('*')
					 ->where('gender', $interested_in)
					 ->get();
					
		return json_encode($users);
	}
	
	function test(){
		$user = Auth::user();
		$id = $user->id;
		return json_encode(Auth::user());
	}

}

?>