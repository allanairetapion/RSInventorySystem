<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class indexhtmlcontroller extends Controller{
	/**
	 * Show the login page for the given user
	 * 
	 */
	 public function showIndex(){
	 	return view("InventorySys.index");
	 }
	 
	/**
	 * Show the dashboard page for the given user
	 * 
	 */
	 
	  
}
