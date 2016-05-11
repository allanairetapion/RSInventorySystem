<?php
namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Validator;
use App\Client as Client;
use App\ClientProfile as ClientProfile;
use app\user;
use Illuminate\Support\Facades\Mail;



class TicketsController extends Controller{
	
	 public function showDashboard(Request $request){
	 	return view("tickets.dashboard");
	 }
	 
	 public function showWelcome(Request $request){
	 	
		$client_profile = ClientProfile::find(1);
		
	 	return view("tickets.welcome", array("client_profile"=>$client_profile));
	 }
	 
	 public function showSignUp(){
	 	return view("tickets.SignUp");
	 }
	 
	   public function showSignUpSuccess(Request $request){	
			return view("tickets.signUpSuccess");	
	}
	 public function showForgotPassword(){
	 	return view("tickets.forgotpassword");
	 }

	 
	 public function landingPage(Request $request){
	 	if (Auth::guest()){
	 	     return view("auth.login");
		}
		else{
			
			return view("tickets.landingPage");
		}
	 }
	 public function showChangePassword(){
		return view("tickets.ChangePassword");
	}
	 public function showChangePasswordSuccess(){
		return view("tickets.changePasswordSuccess");
	}	 	 
	 
	  public function processSignUP(Request $request){
	 	
		$validator = Validator::make($request->all(), 
	 		['email' => 'required|unique:clients,email',
			'fname'=> 'required|alpha|min:2',
			'lname' =>'required|alpha|min:2',
			'password'=>'required|alpha_num|between:6,100|confirmed',
			'password_confirmation'=>'required|alpha_num|between:6,100', 	
			'dept' => 'required',
			'captcha' => 'required|captcha']								);
			if ($validator->fails()) {
					return redirect('tickets/signUp')
                        ->withErrors($validator)
                        ->withInput();
        			}
        	else
				
        	{
		
		 // get all the data that has been posted from the form
		 $post_data = $request->all();
		
		 $client = new Client();
		 $client->department_id = $post_data['dept'];
		 $client->email = $post_data['email'];
		 $client->password = sha1($post_data['password']);
		 /*$client->confirm_password = sha1($post_data['password_confirmation']);*/
		 $client->date_registered = date('Y-m-d H:i:s');		 
		 $client->save();
		 
		 $clientProfile = new ClientProfile();
		 $clientProfile->first_name = $post_data['fname'];
		 $clientProfile->last_name = $post_data['lname'];
		 $clientProfile->date_updated=date('Y-m-d H:i:s');	
		 $clientProfile->save();
		
		return view("tickets.signUpSuccess");	
        
        }
        }
        
	 
	   /*
	 public function processLogIn (Request $request){
	 	 $post_data = $request->all();		 
		 $client = Client::where('email','=',$post_data['email'])->first();		 
		 $request['password'] = sha1($post_data['password']); 
		 
		 
		 $validator = Validator::make($request->all(),	
		 ['email' => 'required|exists:clients,email',
	 	 'password'=> 'exists:clients,password|required|min:6']);
		
		 
	if ($validator->fails()) {
		echo $request['password'];
		return redirect('tickets/login')
                        ->withErrors($validator)
                        ->withInput();						
      	}
    else{ 	
        return redirect('tickets/landingPage'); 
	 	}
	 }		 	 
	 */
	/*public function processForgot(Request $request){	 	
	 	$validator = Validator::make($request->all(), 
	 	['email' => 'required|exists:clients,email']);
		
		 
	if ($validator->fails()) {
		return redirect('tickets/forgotPassword')
                        ->withErrors($validator)
                        ->withInput();
        }
        else{
        	echo "exists";            
        }	
	}*/
	
		 
}

?>