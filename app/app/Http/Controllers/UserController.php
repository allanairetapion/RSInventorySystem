<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller{
	/**
	 * Show the login page for the given user
	 * 
	 */
	 public function showLogin(){
	 	return view("user.login");
	 }
	 
	/**
	 * Show the dashboard page for the given user
	 * 
	 */
	 public function showDashboard(Request $request){
	 	return view("user.dashboard");
	 }
	 
	  
}
