<?php
namespace App\Http\Controllers;


use App\IsUser as IsUser;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class inventorySysController extends Controller {
	/**
	 * Show the login page for the given user
	 *
	 */
	 
	  public function __construct(){
        $this->middleware('inventory');
   }
	 
	public function showIndex() {
		if (Auth::guard('inventory')->check()){
			return view('inventory.index');
		}
		return redirect('/inventory/login');
	}
 
	public function showLogin() {

		return view("inventory.login");

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

	public function changePass() {
		return view("inventory.change_pass");

	}

	/**
	 * Show the dashboard page for the given user
	 *
	 */

/*
public function processLogin(Request $request) {

 
		$post_data = $request -> all();
 		  				
				$is_users = IsUser::where('email', '=', $post_data['email']) -> first();
				$request['password'] = sha1($post_data['password']);
				$validator = Validator::make($request->all(),	['email' => 'required|exists:is_users,email',
								 	 'password'=> 'required|min:6']);
		 
				if ($validator->fails()) 
				{
						
						return redirect('/')
                             ->with('message','Email is not registered.')
                       		 ->withInput();						
      			}
    			else
    			{
    	
    				if($is_users['password'] == $request['password'])
    				{
    					return view("inventory/index", array("is_users" => $is_users));
    					//return redirect('/inventory/index');
    				}
    			
					else
					{
						return redirect('/')
						->with('message','Incorrect password.');
						
					}
    	
			 	
				}	
			
			
	
	}*/

	public function showRegister() {

		return view("inventory.register_account");
	}
	


	
	public function processRegister(Request $request) {
		// get all the data that has been posted from the form
		$post_data = $request -> all();
		$user_type = IsUser::lists('user_type');
		 $rules = ['captcha' => 'required|captcha'];
		$messages = [
					'captcha' => "Captcha code doesn't match."
		
		];	
			 
  
  		$validator = Validator::make($request->all(), 
    				['email' => 'email|required|unique:is_users,email',
   					'firstname'=> 'required|string|min:2',
   					'lastname' =>'required|string|min:2',
   					'password'=>'required|alpha_num|between:6,100|confirmed',
   					'password_confirmation' =>'required|alpha_num|between:6,100',  
   					'usertype' => 'required', 
   					'phone_number' =>'digits:11|required|unique:is_users,phone_number',
   					'captcha' => 'required|captcha'],$messages);


   
  		 if ($validator->fails()) {
  		 		
  		 	
     			return redirect('/inventory/register')
               			        ->withErrors($validator)
                        		->withInput();
    	     }
         else
    		{
         	
			$client = new IsUser();
			$client -> first_name = ucfirst($post_data['firstname']);
			$client -> last_name = ucfirst($post_data['lastname']);
			$client -> email = $post_data['email'];
			$client -> password = sha1($post_data['password']);
			$client -> phone_number = $post_data['phone_number'];
			$client -> user_type = $post_data['usertype'];
			$client -> date_created = date('Y-m-d H:i:s');
			$client -> save();
		
			return redirect('/inventory/signuptypage');			
			}
  
	}
	
	/*
	public function processForgotpass(Request $request){
															  $validator = Validator::make($request->all(), 
							['email' => 'email|exists:is_users,email']);
							
					$is_users = IsUser::where('email', '=', $request['email']) -> first();
																							  if ($validator->fails()) {
						 return redirect('/inventory/forgotpass')
										   ->with('error_message','You have entered an invalid e-mail address. Please try again.');
															   }
				 else if ($validator->passes())
					{			
														$data = array(
								   'client' => 'cravendelossantos',
								   'url' => 'http://laravel.com'
								); 
										 // \Mail::send('inventory.newpassword', $data, function($message)
							  //$url = URL::to('/inventory/forgotpass_confirmation');	
								  \Mail::send('inventory/forgotpass_link', ['is_users' => $is_users, 'token' => $token], function ($message) use ($is_users)
														   {
																			// $m->to($user->email, $user->name)->subject('Your Reminder!');
										$message->to($is_users->email, $is_users->name)->subject('Forgot Password');
																		$message->from('InventoryManagent@remoteStaff.com', 'RS Inventory Management');
																													   });
														  return view("/inventory/forgotpass_typage", array("is_users" => $is_users));
									
					}
								  }*/
	
	

	
	public function forgotpassTypage() {
		
		
		
		return view('inventory.forgotpass_typage');

	}
	
	public function showForgotpassLink() {
		
		
			
		return view('inventory.forgotpass_link');

	}
	
	


}
