<?php

namespace App\Http\Controllers;
use App\Tickets;
use App\Admin;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TicketsAdmin extends Controller
{
    public function __construct(){
        $this->middleware('admin');
   }
	public function index(){
        return view('tickets.admin.dashboard');
    }
	
	public function createAgent(){
		return view('tickets.admin.createAgent');
	}
	
	public function checkPassword($password){
		if (Auth::guard('admin')->attempt(['email' => Auth::guard('admin')->user()->email, 'password' => $password]))
        {
            return "true";
        }
		else{
			return "false";
		}
	}
	
	
	public function checkEmail(Request $request){
		$validator = Validator::make($request->all(),[
        	'firstname' => 'required|min:3|max:255',
            'lastname' => 'required|min:2|max:255',
            'email' => 'required|email|max:255|unique:admin,email',
            'user_type' => 'required',            
        ]);

        if ($validator->fails()) {
            return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray())); 
          
        }
		else {
			return response()->json(['response' => '']); 
		}
	}
	
	public function showCreateTicket(){
		return view('tickets.admin.createTicket');
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
            'sender' => Auth::guard('admin')->user()->email,
            'topic' => $request['Topic'],
            'subject' => $request['Subject'],
            'summary' => $request['Summary'],
            'status' => 'Open',
            'priority' => 'High',
            'department' =>'Support',
        ]);
			
			return response()->json(['response' => '']); 
			
			
		}
	}
	

	
}