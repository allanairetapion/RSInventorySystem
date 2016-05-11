<?php
namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


use Validator;


use App\IsUser as IsUser;

class inventorySysController extends Controller {
	/**
	 * Show the login page for the given user
	 *
	 */
	public function showIndex() {
		return view("inventory.index");
	}

	public function showLogin() {

		return view("inventory.login");

	}

	public function showRegister() {

		return view("inventory.register_account");
	}

	public function showCr8AccTyPage() {

		return view("inventory.signuptypage");
	}

	public function showForgotpass() {
		return view("inventory.forgotpass");

	}

	public function showVerify() {
		return view("inventory.verification");

	}

	public function showNewPass() {

		return view("inventory.newpassword");

	}

	public function showNewPassTy() {
		return view("inventory.thankyoupage");

	}

	/**
	 * Show the dashboard page for the given user
	 *
	 */

	public function processLogin(Request $request) {

 
		$post_data = $request -> all();
 		  				
				$is_users = IsUser::where('email', '=', $post_data['email']) -> first();
				$request['password'] = sha1($post_data['password']);
				$validator = Validator::make($request->all(),	['email' => 'required|exists:is_users,email',
								 	 'password'=> 'exists:is_users,password|required|min:6']);
		 
				if ($validator->fails()) 
				{
						
						return redirect('/inventory/login')
                        ->withErrors($validator)
                        ->withInput();						
      			}
    			else
    			{
    	
    	 				return 	redirect('/inventory/index');
			 	
				}	
			
			
	
	}
	
	 	 	 

	
	 
	 	 
	public function processForgot(Request $request)
		 {
	 	
	 	$validator = Validator::make($request->all(), 
	 	['email' => 'required|exists:is_users,email']);
		
		 
		if ($validator->fails()) {
		return redirect('inventory/forgotpass')
                        ->withErrors($validator)
                        ->withInput();
        }
        else{
        	echo "exists";            
        	}
		}
		


			
	
	}
