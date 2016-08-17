<?php

namespace App\Http\Controllers;


use App\IsUser;
use App\InventoryItem as Item;
use App\Borrow as Borrow;
use App\Admin as Admin;
use App\ReturnItem as ReturnItem;
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
		
		foreach($borrowedItems as $borrowedItem){
			$name = DB::table('admin_profiles')->where('agent_id', $borrowedItem->borrower)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $borrowedItem->borrower)->first();
			}
			
			if($name != null){
			$borrowedItem->borrower = $name->first_name.' '.$name->last_name;
			}
		}
		
		$clients = DB::table('client_profiles')->select('client_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		$agents = DB::table('admin_profiles')->select('agent_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		 
		return view ( "inventory.borrow",['borrowedItems' => $borrowedItems,'clients' => array_merge($clients,$agents)] );
		
	}
	public function showReturn() {
		
		$returnedItems = DB::table('return_logs')->leftJoin('items','return_logs.unique_id','=','items.unique_id')
		->leftJoin('admin_profiles','return_logs.receiver','=','admin_profiles.agent_id')->orderby('dateReturned','desc')->get();
		
		foreach($returnedItems as $returnedItem){
			$name = DB::table('admin_profiles')->where('agent_id', $returnedItem->borrower)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $returnedItem->borrower)->first();
			}
			if($name != null){
			$returnedItem->borrower = $name->first_name.' '.$name->last_name;
			}
		}
		$clients = DB::table('client_profiles')->select('client_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		$agents = DB::table('admin_profiles')->select('agent_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		return view ( "inventory.return",['returnedItems' => $returnedItems,'clients' => array_merge($clients,$agents)] );
		
	}
	public function showDetailed() {
		
		$items = Item::all();
		
		foreach ($items as $item){
			$borrow = Borrow::where('unique_id',$item['unique_id'])->orderBy('dateBorrowed','desc')->first();
			$return = ReturnItem::where('unique_id',$item['unique_id'])->orderBy('dateReturned','desc')->first();
			
			$item->dateBorrowed = $borrow['dateBorrowed'];
			$item->dateReturned = $return['dateReturned'];
		}
		return view ( "inventory.detailed",['items' => $items] );
		
	}
	public function showIssues() {
		return view ( "inventory.issues" );
	}
	public function showBroken() {
		return view ( "inventory.broken" );
	}
	public function showAgents(){
		
		$users = DB::table ( 'admin' )->leftJoin ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->get ();
		
		return view ("inventory.agents",['agents' => $users]);
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
			
			$time = Carbon::parse(Carbon::now());
			$date = Carbon::parse($request['dateArrived']);
			$newItem = Item::create([
					'unique_id' => $request['unique_id'],
					'itemNo' => $request['itemNo'],
					'company' => $request['company'],
					'stationNo' => $request['stationNo'],
					'brand' => $request['brand'],
					'model' => $request['model'],
					'itemType' => $request['itemType'],
					'arrivalDate' => Carbon::create($date->year,$date->month,$date->day,$time->hour,$time->minute,$time->second),
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
	public function borrowInfo(Request $request){
			
	$itemInfo = Item::where('unique_id',$request['item'])->first();
			if($itemInfo == null){
				return response ()->json ( [
						'success' => false
				] );
			}else{
				$borrowInfo = Borrow::where('unique_id',$request['item'])->orderBy('dateBorrowed','desc')->first();
						
				$name = DB::table('admin_profiles')->where('agent_id', $borrowInfo['borrower'])->first();
				if($name == null){
					$name = DB::table('client_profiles')->where('client_id', $borrowInfo['borrower'])->first();
				}
				
				if($name != null){
				$borrowInfo['borrower'] = $name->first_name.' '.$name->last_name;
				}
				return response ()->json ( [
						'success' => true, 'info' => $itemInfo,'borrow' => $borrowInfo
				] );
			}
	}
	
	public function borrowItem(Request $request){
		$validator = Validator::make ( $request->all (), [
				'unique_id' => 'required|alpha_num|exists:items,unique_id,itemStatus,"Available"',
				'itemNo' => 'required|numeric',
				'borrower' => 'required|',
				'stationNo' => 'required|numeric|max:255',
				'dateBorrowed' => 'required|date'
				
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray ()
			) );
		} else {
			$time = Carbon::parse(Carbon::now());
			$date = Carbon::parse($request['dateBorrowed']);
			
			$itemStatus = Item::where('unique_id',$request['unique_id'])->update(['itemStatus' => 'Not Available']);
			$borrowItem = new Borrow;
			$borrowItem->unique_id = $request['unique_id'];
			$borrowItem->borrower = $request['borrower'];
			$borrowItem->borrowerStationNo = $request['stationNo'];
			$borrowItem->borrowee = Auth::guard ( 'inventory' )->user ()->id;
			$borrowItem->dateBorrowed = Carbon::create($date->year,$date->month,$date->day,$time->hour,$time->minute,$time->second);
			$borrowItem->save();
			

			$result = Borrow::where('borrow_logs.unique_id',$request['unique_id'])->leftJoin('items','borrow_logs.unique_id','=','items.unique_id')
					->leftJoin('admin_profiles','borrow_logs.borrowee','=','admin_profiles.agent_id')->orderBy('dateBorrowed','desc')->first();
			
			$name = DB::table('admin_profiles')->where('agent_id', $result['borrower'])->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $result['borrower'])->first();
			}
				
			if($name != null){
				$result['borrower'] = $name->first_name.' '.$name->last_name;
			}
			return response ()->json ( [
					'success' => true,
					'response' => $result,
						
			] );
		};
	}
	
	public function returnItem(Request $request){
		$validator = Validator::make ( $request->all (), [
				'unique_id' => 'required|alpha_num|exists:items,unique_id,itemStatus,"Not Available"',
				'itemNo' => 'required|numeric',
				'dateReturned' => 'required|date',
		] );
	
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray ()
			) );
		} else {
			$time = Carbon::parse(Carbon::now());
			$date = Carbon::parse($request['dateReturned']);
			$borrower = Borrow::where('unique_id',$request['unique_id'])->orderBy('dateBorrowed','desc')->first();
			$itemStatus = Item::where('unique_id',$request['unique_id'])->update(['itemStatus' => 'Available']);
			$returnItem = new ReturnItem;
			
			$returnItem->unique_id = $request['unique_id'];
			$returnItem->receiver = Auth::guard ( 'inventory' )->user ()->id;
			$returnItem->borrower = $borrower->borrower;
			$returnItem->dateReturned = Carbon::create($date->year,$date->month,$date->day,$time->hour,$time->minute,$time->second);
			$returnItem->save();
			
			$result = ReturnItem::where('return_logs.unique_id',$request['unique_id'])->leftJoin('items','return_logs.unique_id','=','items.unique_id')
					->leftJoin('admin_profiles','return_logs.receiver','=','admin_profiles.agent_id')->orderby('dateReturned','desc')->first();
	
			$name = DB::table('admin_profiles')->where('agent_id', $result['borrower'])->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $result['borrower'])->first();
			}
			
			if($name != null){
				$result['borrower'] = $name->first_name.' '.$name->last_name;
			}
			
			return response ()->json ( [
					'success' => true,
					'response' => $result,
	
			] );
		};
	}
	//Borrow form advanced search
	public function borrowSearch(Request $request,Borrow $borrow){
		
		$borrow = $borrow->leftJoin('items','borrow_logs.unique_id','=','items.unique_id')
		->leftJoin('admin_profiles','borrow_logs.borrowee','=','admin_profiles.agent_id')->orderby('dateBorrowed','desc');
		$borrow = $borrow->newQuery();
		if($request['unique_id'] != null){
			$borrow->where('borrow_logs.unique_id',$request['unique_id']);
		}
		if($request['borrower'] != null){
			$borrow->where('borrower',$request['borrower']);
		}
		if($request['borrowee'] != null){
			$borrow->where('borrowee',$request['borrower']);
		}
		if($request['brand'] != null){
			$borrow->where('brand',$request['brand']);
		}
		if($request['dateStart'] != null && $request['dateEnd'] != null){
			$borrow->whereBetween('dateBorrowed',[$request['dateStart'],$request['dateEnd']]);
		}
		
		
		
		$borrows = $borrow->get();
		foreach($borrows as $borrowedItem){
			$name = DB::table('admin_profiles')->where('agent_id', $borrowedItem->borrower)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $borrowedItem->borrower)->first();
			}
		
			if($name != null){
				$borrowedItem->borrower = $name->first_name.' '.$name->last_name;
			}
		}
		
		$clients = DB::table('client_profiles')->select('client_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		$agents = DB::table('admin_profiles')->select('agent_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		 
		return view ( "inventory.borrow",['borrowedItems' => $borrows,'clients' => array_merge($clients,$agents)] );
	}
	//Return Form advanced search
	
	public function returnSearch(Request $request,ReturnItem $returnItem){
		
		$returnItem = $returnItem->leftJoin('items','return_logs.unique_id','=','items.unique_id')
		->leftJoin('admin_profiles','return_logs.receiver','=','admin_profiles.agent_id')->orderby('dateReturned','desc');
		
		$returnItem = $returnItem->newQuery();
		
		if($request['unique_id'] != null){
			$returnItem->where('return_logs.unique_id',$request['unique_id']);
		}
		if($request['borrower'] != null){
			$returnItem->where('borrower',$request['borrower']);
		}
		if($request['receiver'] != null){
			$returnItem->where('receiver',$request['borrower']);
		}
		if($request['brand'] != null){
			$borrow->where('brand',$request['brand']);
		}
		if($request['dateStart'] != null && $request['dateEnd'] != null){
			$returnItem->whereBetween('dateBorrowed',[$request['dateStart'],$request['dateEnd']]);
		}
		
		$returns = $returnItem->get();
		foreach($returns as $returnItem){
			$name = DB::table('admin_profiles')->where('agent_id', $returnItem->borrower)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $returnItem->borrower)->first();
			}
		
			if($name != null){
				$returnItem->borrower = $name->first_name.' '.$name->last_name;
			}
		}
		
		$clients = DB::table('client_profiles')->select('client_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		$agents = DB::table('admin_profiles')->select('agent_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		return view ( "inventory.return",['returnedItems' => $returns,'clients' => array_merge($clients,$agents)] );
	}
	
}
