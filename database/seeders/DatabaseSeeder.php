<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

use App\Models\UserType;
use App\Models\User;

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
			"user_type_id" => 2,
			"first_name" => "Nabih",
			"last_name" => "Tannous",
			"email" => "nabih@gmail.com",
			"password" => hash("sha256", "test123"),
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
	   
    }
}
