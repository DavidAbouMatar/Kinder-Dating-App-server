<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

use App\Models\UserType;
use App\Models\User;
use App\Models\UserHobby;
use App\Models\UserMessage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(){
       		
		UserType::insert([
			"name" => "admin",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);
	   
		UserType::insert([
			"name" => "user",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		User::insert([
			"user_type_id" => 1,
			"first_name" => "Admin",
			"last_name" => "Admin",
			"email" => "kinder.admin@gmail.com",
			"password" => bcrypt("admin123"),
			"gender" => 0,
			"interested_in" => 1,
			"dob" => "1980-01-01",
			"height" => "200",
			"weight" => "100",
			"nationality" => "Lebanese",
			"net_worth" => "1",
			"currency" => "USD",
			"bio" => "Admin!",
			"is_highlighted" => 0,
	   ]);

	   
		User::insert([
			"user_type_id" => 2,
			"first_name" => "Nabih",
			"last_name" => "Tannous",
			"email" => "nabih@gmail.com",
			"password" => bcrypt("test123"),
			"gender" => 0,
			"interested_in" => 1,
			"dob" => "1981-02-12",
			"height" => "120",
			"weight" => "78",
			"nationality" => "lebanese",
			"net_worth" => "75000000",
			"currency" => "USD",
			"bio" => "Hello !",
			"is_highlighted" => 1,
	   ]);
	   
	   User::insert([
			"user_type_id" => 2,
			"first_name" => "Nabiha",
			"last_name" => "Family",
			"email" => "test@gmail.com",
			"password" => bcrypt("test123"),
			"gender" => 1,
			"interested_in" => 0,
			"dob" => "1981-02-12",
			"height" => "120",
			"weight" => "78",
			"nationality" => "lebanese",
			"net_worth" => "100000",
			"currency" => "LBP",
			"bio" => "Hey ! I need a sugar daddy *.* ",
			"is_highlighted" => 1,
	   ]);
	   
	   UserHobby::insert([
			"user_id" => "1",
			"name" => "Running",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
	   ]);
	   
	   UserHobby::insert([
			"user_id" => "1",
			"name" => "Swimming",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
	   ]);
	   
	   UserHobby::insert([
			"user_id" => "1",
			"name" => "football",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
	   ]);

	   UserHobby::insert([
			"user_id" => "2",
			"name" => "Reading",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
	   ]);

	   UserHobby::insert([
			"user_id" => "2",
			"name" => "Dancing",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
	   ]);
	   
	   UserHobby::insert([
			"user_id" => "2",
			"name" => "football",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
	   ]);

	   UserMessage::insert([
		"sender_id" => "3",
		"receiver_id" => "2",
		"body" => "Hello, how are you today??",
		"is_approved" => "0",
		"is_read" => "0",
		"created_at" => date("Y-m-d"),
		"updated_at" => date("Y-m-d")
		]);

	UserMessage::insert([
		"sender_id" => "3",
		"receiver_id" => "2",
		"body" => "Hi, **** *** *** ***!!!",
		"is_approved" => "0",
		"is_read" => "0",
		"created_at" => date("Y-m-d"),
		"updated_at" => date("Y-m-d")
		]);

		UserMessage::insert([
			"sender_id" => "2",
			"receiver_id" => "3",
			"body" => "Nice to meet you :)",
			"is_approved" => "0",
			"is_read" => "0",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);

		UserMessage::insert([
			"sender_id" => "3",
			"receiver_id" => "2",
			"body" => "yes of course",
			"is_approved" => "0",
			"is_read" => "0",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);
		
		UserMessage::insert([
			"sender_id" => "3",
			"receiver_id" => "2",
			"body" => "How was last night ;)",
			"is_approved" => "0",
			"is_read" => "0",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);
		
		UserMessage::insert([
			"sender_id" => "3",
			"receiver_id" => "2",
			"body" => "sure, today at 9",
			"is_approved" => "0",
			"is_read" => "0",
			"created_at" => date("Y-m-d"),
			"updated_at" => date("Y-m-d")
		]);
		
    }
}
