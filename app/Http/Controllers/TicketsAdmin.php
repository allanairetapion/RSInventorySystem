<?php

namespace App\Http\Controllers;

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
	public function checkEmail(Request $request){
		$validation = Validator::make( $request->all(), [                              
                               'email' => 'required|email|exists:admin,email',                             
                            ]);

		  if( $validation->fails() )
		  {
		    return json_encode([
		            'errors' => $validation->errors()->getMessages(),
		            'code' => 422
		         ]);
		  }
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
	
	public function showChangePasswordSuccess (){
		Auth::guard('admin')->logout();
		
		return view('tickets.admin.changePasswordSuccess');
	}
}