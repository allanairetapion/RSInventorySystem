<?php

namespace App\Http\Controllers;

use App\BorrowForm as BorrowForm;
use App\IsUser;
use App\InventoryItem as Item;
use App\Borrow as Borrow;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;

class inventoryController extends Controller {
	/**
	 * Show the login page for the given user
	 */
	public function __construct() {
		$this->middleware ( 'inventory' );
	}
	public function showIndex() {
		return view ( 'inventory.index' );
	}
	public function showInventory() {
		return view ( "inventory.inventory" );
	}
	public function showManageAccounts() {
		return view ( "inventory.manage_accounts" );
	}
	public function showBorrow() {
		
		$borrowedItems = DB::table('borrow_logs')->leftJoin('items','borrow_logs.unique_id','=','items.unique_id')
		->leftJoin('admin_profiles','borrow_logs.borrowee','=','admin_profiles.agent_id')->orderby('dateBorrowed','desc')->get();
		return view ( "inventory.borrow",['borrowedItems' => $borrowedItems] );
	}
	public function showReturn() {
		return view ( "inventory.return" );
	}
	public function showDetailed() {
		return view ( "inventory.detailed" );
	}
	public function showIssues() {
		return view ( "inventory.issues" );
	}
	public function showBroken() {
		return view ( "inventory.broken" );
	}
	public function showSummaryMonYrs() {
		return view ( "inventory.summaryMonYrs" );
	}
	public function showSummaryAll() {
		return view ( "inventory.summaryAll" );
	}
	public function showCr8AccTyPage() {
		return view ( "inventory.signuptypage" );
	}
	public function showForgotpass() {
		return view ( "inventory.forgotpass" );
	}
	public function showVerify() {
		return view ( "inventory.verification" );
	}
	public function showNewPass() {
		return view ( "inventory.newpassword" );
	}
	public function showNewPassTy() {
		Auth::guard ( 'inventory' )->logout ();
		return view ( "inventory.thankyoupage" );
	}
	public function showAddItem() {
		return view ( "inventory.addItem" );
	}
	public function showRegister() {
		return view ( "inventory.register_account" );
	}
	public function showForgotpassLink() {
		return view ( 'inventory.forgotpass_link' );
	}
	
	public function addItem(Request $request){
		$validator = Validator::make ( $request->all (), [
				'unique_id' => 'required|alpha_num|unique:items',
				'itemNo' => 'required|numeric',
				'company' => 'required|max:255',
				'stationNo' => 'required|numeric|max:255',
				'brand' => 'required|max:255',
				'model' => 'required|max:255',
				'itemType' => 'required|max:255',
				'dateArrived' => 'required|max:255',
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray ()
			) );
		} else {
			
			$newItem = Item::create([
					'unique_id' => $request['unique_id'],
					'itemNo' => $request['itemNo'],
					'company' => $request['company'],
					'stationNo' => $request['stationNo'],
					'brand' => $request['brand'],
					'model' => $request['model'],
					'itemType' => $request['itemType'],
					'arrivalDate' => $request['dateArrived'],
					'itemStatus' => "Available",
			]);
			
			return response ()->json ( [
					'success' => true,
				] );
		};
	}
	
	public function itemInfo(Request $request){
		$itemInfo = Item::where('unique_id',$request['item'])->first();
		
		if($itemInfo == null){
			return response ()->json ( [
					'success' => false
			] );
		}else{
			return response ()->json ( [
					'success' => true, 'info' => $itemInfo
			] );
		}
	}
	
	public function borrowItem(Request $request){
		$validator = Validator::make ( $request->all (), [
				'unique_id' => 'required|alpha_num|exists:items,unique_id,itemStatus,"Available"',
				'itemNo' => 'required|numeric',
				'borrower' => 'required|max:255',
				'stationNo' => 'required|numeric|max:255',
				
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray ()
			) );
		} else {
			
			$itemStatus = Item::where('unique_id',$request['unique_id'])->update(['itemStatus' => 'Not Available']);
			$borrowItem = Borrow::create([
				'unique_id' => $request['unique_id'],
				'itemNo' => $request['itemNo'],
				'borrower' => $request['borrower'],
				'stationNo' => $request['stationNo'],
				'borrowee' => Auth::guard ( 'inventory' )->user ()->id,
				'dateBorrowed' => Carbon::now(),
			]);
				
			return response ()->json ( [
					'success' => true,
					'response' => Borrow::where('borrow_logs.unique_id',$request['unique_id'])->leftJoin('items','borrow_logs.unique_id','=','items.unique_id')
					->leftJoin('admin_profiles','borrow_logs.borrowee','=','admin_profiles.agent_id')->first()
						
			] );
		};
	}
	
}
