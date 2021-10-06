<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;

use JWTAuth;
use Auth;

class AuthController extends Controller{
	
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
				return response()->json(array(
					"status" => 'ok',
					"errors" => 'Invalid Credentials!'
				), 401);
				}
		}catch(JWTException $e){
			return json_encode(["error" => "Error occured"]);
		}
		
		$user = JWTAuth::user();
		$user->token = $token;
		return json_encode($user);
	}
	
	public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|between:2,100',
            'last_name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
			'gender' => 'required|integer',
			'interested_in' => 'required|integer',
			'dob' => 'required',
			'height' => 'required|integer',
			'weight' => 'required|integer',
			'nationality' => 'required|string|between:2,100',
			'net_worth' => 'required|integer',
			'currency' => 'required|string',
			'bio' => 'required|string|between:2,200',
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                "status" => 'false',
                "errors" => $validator->errors()
            ), 400);
        }

		User::insert([
			"user_type_id" => 2,
			"first_name" => $request -> first_name,
			"last_name" => $request -> last_name,
			"email" => $request -> email,
			"password" => bcrypt($request -> password),
			"gender" => $request -> gender,
			"interested_in" => $request -> interested_in,
			"dob" => $request -> dob,
			"height" => $request -> height,
			"weight" => $request -> weight,
			"nationality" => $request -> nationality,
			"net_worth" => $request -> net_worth,
			"currency" => $request -> currency,
			"bio" => $request -> bio,
			"is_highlighted" => 0,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')

	   ]);
		
        return response()->json([
            'status' => true,
            'message' => 'User successfully registered',
        ], 201);
    }

}

?>