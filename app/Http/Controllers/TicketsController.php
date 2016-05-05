<?php
namespace App\Http\Controllers;

use App\Http\Controllers;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Validator;
use App\Client as Client;
use App\ClientProfile as ClientProfile;




class TicketsController extends Controller{
	
	 public function showDashboard(Request $request){
	 	return view("tickets.dashboard");
	 }
	 
	 public function showWelcome(Request $request){
	 	
		$client_profile = ClientProfile::find(1);
		
	 	return view("tickets.welcome", array("client_profile"=>$client_profile));
	 }
	 
	 
	 public function showLogin(){
	 	return view("tickets.login");
	 }
	 
	 public function showSignUp(){
	 	return view("tickets.SignUp");
	 }
	  public function showSignUpSuccess(){
		return view("tickets.signUpSuccess");
	}	 	
	 public function showForgotPassword(){
	 	return view("tickets.forgotpassword");
	 }

	 
	 public function landingPage(Request $request){
	 	return view("tickets.landingPage");
	 }
	 public function showChangePassword(){
		return view("tickets.ChangePassword");
	}
	 public function showChangePasswordSuccess(){
		return view("tickets.changePasswordSuccess");
	}	 	 
	 public function showCodeVerification()
	 {
		return view("tickets.enterVerificationCode"); 
	 }  
	  
	 public function processSignUP(Request $request){
	 	
		
		 // get all the data that has been posted from the form
		 $post_data = $request->all();
				
		 $client = new Client();
		 $client->department_id = $post_data['dept'];
		 $client->email = $post_data['email'];
		 $client->password = sha1($post_data['password']);
		 $client->date_registered = date('Y-m-d H:i:s');		 
		 $client->save();
		 
		 $clientid = Client::where('email','=',$post_data['email'])->orderby('id','desc')->first();
		 
		 $clientProfile = new ClientProfile();
		 $clientProfile->client_id = $clientid->id;
		 $clientProfile->first_name = $post_data['fname'];
		 $clientProfile->last_name = $post_data['lname'];
		 $clientProfile->date_updated=date('Y-m-d H:i:s');	
		 $clientProfile->save();		 
	 }
	 
	 public function processLogIn (Request $request){
	 	 $post_data = $request->all();		 
		 $client = Client::where('email','=',$post_data['email'])->first();		 
		 $request['password'] = sha1($post_data['password']); 
		 $validator = Validator::make($request->all(),	['email' => 'required|exists:clients,email',
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
	 
	public function processForgot(Request $request){	 	
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
	}
	
		 
}

?>