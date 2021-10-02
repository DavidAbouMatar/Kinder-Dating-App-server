<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;
use App\Models\UserFavorite;
use App\Models\UserConnection;
use App\Models\UserNotification;

use Auth;

class UserController extends Controller{

	    /**
     * Create a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

	
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

	    /**
     * Authenticated User Favorites another User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addToFavorites($id)
    {
		$user_id = auth()->user()->id;

		UserFavorite::insert([
			'from_user_id' => $user_id,
			'to_user_id' => $id,
			'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
		]);

		$is_match =  UserFavorite::where('from_user_id', $id)->where('to_user_id', $user_id)->get()->count();

		$user1 = User::find($user_id);
		$user1_fullname = $user1->first_name.' '.$user1->last_name;

		if ($is_match) {
			$user2 = User::find($id);
			$user2_fullname = $user2->first_name.' '.$user2->last_name;

			UserConnection::insert([
				'user1_id' => $user_id,
				'user2_id' => $id,
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
				'user_id' => $id,
				'body' => "You are a Match with $user1_fullname!",
				'is_read' => 0,
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')	
			]);

		}else {
			UserNotification::insert([
				'user_id' => $id,
				'body' => "Hey! $user1_fullname tapped you!",
				'is_read' => 0,
				'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
				'updated_at' => Carbon::now()->format('Y-m-d H:i:s')	
			]);
		}

		// return response()->json($is_match, 201);
        return response()->json([
            'status' => true,
            'message' => 'User added to Favorites!',
        ], 201);
    }

}

?>