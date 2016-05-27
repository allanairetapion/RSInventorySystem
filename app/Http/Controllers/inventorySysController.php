<?php
namespace App\Http\Controllers;
use App\BorrowForm as BorrowForm;
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

	public function __construct() {
		$this -> middleware('inventory');
	}

	public function showIndex() {

		return view('inventory.index');

	}

	public function showInventory() {

		return view("inventory.inventory");

	}

	public function showManageAccounts() {

		return view("inventory.manage_accounts");

	}

	public function showBorrow() {

		$borrowTable = BorrowForm::all();

		return view("inventory.borrow") -> with('borrow', $borrowTable);

	}
	
	public function showReturn()
	 {
	 	return view("inventory.return");	
	 }
	 
	  public function showDetailed()
	 {
	 	return view("inventory.detailed");	
	 }
	 
	  public function showIssues()
	 {
	 	return view("inventory.issues");	
	 }
	 
	  public function showBroken()
	 {
	 	return view("inventory.broken");	
	 }
	 
	  public function showSummaryMonYrs()
	 {
	 	return view("inventory.summaryMonYrs");	
	 }
	 
	  public function showSummaryAll()
	 {
	 	return view("inventory.summaryAll");	
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

	public function showInputItem() {
		return view("inventory.item_input");

	}

	public function showRegister() {

		return view("inventory.register_account");
	}

	public function showForgotpassLink() {
		return view('inventory.forgotpass_link');

	}

}
