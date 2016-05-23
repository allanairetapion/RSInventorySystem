<?php
namespace App\Http\Controllers;
use App\Tickets;
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

	public function __construct(){
        $this->middleware('user');
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
	 	if(Auth::guard('user')->check()){	
			return view("tickets.landingPage");
		}
		else {
			return redirect('tickets/login');
		}
	 }
	 public function showChangePassword(){
		return view("tickets.ChangePassword");
	}
	 public function showChangePasswordSuccess(){
	 	Auth::guard('user')->logout();
		return view("tickets.changePasswordSuccess");
	}	 	 
	 
	public function showCreateTicket(){
		return view('tickets.createTicket');
	}
	
	public function createTicket(Request $request){
		$validator = Validator::make($request->all(),[
        	'Topic' => 'required',
        	'Subject'  => 'required|min:3|max:255',    
            'Summary' =>'required|min:3',            
        ]);

        if ($validator->fails()) {
            return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray())); 
          
        }
		else {
			
			$user = Tickets::create([
            'sender' => Auth::guard('user')->user()->email,
            'topic' => $request['Topic'],
            'subject' => $request['Subject'],
            'summary' => $request['Summary'],
            'status' => 'Open',
            'priority' => 'Medium',
            'department' =>Auth::guard('user')->user()->department,
        ]);
			
			return response()->json(['response' => '']); 
			
			
		}
	}
	
	
		 
}

?>