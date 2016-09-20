<?php

namespace App\Http\Controllers;


use App\IsUser;
use App\InventoryItem as Item;
use App\BorrowItem as Borrow;
use App\ItemIssues as Issue;
use App\BrokenItem as Broken;
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
	public function showBorrow() {
		
		$borrowedItems = Item::where('itemStatus','Not Available')
		->leftJoin(DB::raw('(select unique_id, borrowee,borrower,borrowerStationNo,created_at as dateBorrowed,updated_at from borrow_logs) borrow'),
		function($join){
			$join->on('items.unique_id','=','borrow.unique_id');
			$join->on('items.updated_at','=','borrow.updated_at');
		})
		->leftJoin('admin_profiles','borrow.borrowee','=','admin_profiles.agent_id')
		->orderBy('dateBorrowed','desc')->get();
		
		$names = DB::table('admin_profiles')->select('agent_id as id','first_name','last_name')->get();	
		$clients = DB::table('client_profiles')->select('client_id as id','first_name','last_name')->get();
		
		foreach($clients as $client){
			array_push($names,$client);
		}
		
		
		
		foreach($borrowedItems as $borrowedItem){
			foreach($names as $nm){
				if($nm->id == $borrowedItem->borrower){
					$borrowedItem->borrower = $nm->first_name.' '.$nm->last_name;
				}
			}
		}
		
		$clients = DB::table('client_profiles')->select('client_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		$agents = DB::table('admin_profiles')->select('agent_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		 
		return view ( "inventory.borrow",['borrowedItems' => $borrowedItems, 'clients' => array_merge($clients,$agents)] );
		
	}
	public function showReturn() {
		$returnedItems = Item::where('itemStatus','Available')
		->leftJoin(DB::raw('( 
				select unique_id, receiver, borrower, created_at as dateReturned ,updated_at from return_logs) returns'),
				function($join){
					$join->on('items.unique_id','=','returns.unique_id');
					$join->on('items.updated_at','=','returns.updated_at');
				})			
		->leftJoin('admin_profiles','returns.receiver','=','admin_profiles.agent_id')->orderby('dateReturned','desc')->get();
		
		$names = DB::table('admin_profiles')->select('agent_id as id','first_name','last_name')->get();
		$clients = DB::table('client_profiles')->select('client_id as id','first_name','last_name')->get();
		
		foreach($clients as $client){
			array_push($names,$client);
		}
		
		foreach($returnedItems as $returnedItem){
			foreach($names as $nm){
				if($nm->id == $returnedItem->borrower){
					$returnedItem->borrower = $nm->first_name.' '.$nm->last_name;
				}
			}
		}
		$clients = DB::table('client_profiles')->select('client_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		$agents = DB::table('admin_profiles')->select('agent_id as id','first_name','last_name')->orderBy('first_name')->get();
		
		return view ( "inventory.return",['returnedItems' => $returnedItems,'clients' => array_merge($clients,$agents)] );
		
	}
	public function showDetailed() {
		
		$items = Item::all();
		
		$borrows = Borrow::select(DB::raw('unique_id,max(dateBorrowed) as dateBorrowed'))->groupBy('unique_id')->get();
		$returns = ReturnItem::select(DB::raw('unique_id,max(dateReturned) as dateReturned'))->groupBy('unique_id')->get();
		$issues = Issue::select(DB::raw('unique_id,max(created_at) as dateReport,issue'))->groupBy('unique_id')->get();
		$brokens = Broken::select(DB::raw('unique_id,max(created_at) as dateReport,damage'))->groupBy('unique_id')->get();
		foreach ($items as $item){
			foreach($borrows as $borrow){
				if($item->unique_id == $borrow->unique_id){
					$item->dateBorrowed = $borrow->dateBorrowed;
					unset($borrow);
					break;
				}
			}
			foreach($returns as $return){
				if($item->unique_id == $return->unique_id){
					$item->dateReturned = $return->dateReturned;
					unset($return);
					break;
				}
			}
			foreach($issues as $issue){
				if($item->unique_id == $issue->unique_id){
					$item->lastIssue = $issue->issue.", ".$issue->dateReport;
					unset($issue);
					break;
				}
			}
			foreach($brokens as $broken){
				if($item->unique_id == $broken->unique_id){
					$item->lastBroken = $broken->damage.", ".$broken->dateReport;
					unset($broken);
					break;
				}
			}
		}
		return view ( "inventory.detailed",['items' => $items] );
		
	}
	public function showIssues() {
		
		$issueItems = Item::where('itemStatus','With Issue')
		->leftJoin('issue_logs',function($join){
			$join->on('items.unique_id','=','issue_logs.unique_id');
			$join->on('items.updated_at','=','issue_logs.created_at');
		})
		->leftJoin('admin_profiles','issue_logs.reported_by','=','admin_profiles.agent_id')->orderby('issue_logs.created_at','desc')->get();
		
		return view ( "inventory.issues",['issueItems' => $issueItems] );
	}
	public function showBroken() {
		
				
		$brokenItems = Item::where('itemStatus','Broken')
		->leftJoin('broken_logs',function($join){
			$join->on('items.unique_id','=','broken_logs.unique_id');
			$join->on('items.updated_at','<=','broken_logs.updated_at');
		})
		->leftJoin('admin_profiles','broken_logs.reported_by','=','admin_profiles.agent_id')
		->get();
		
		
		return view ( "inventory.broken",['brokenItems' => $brokenItems] );
		
		
	}
	public function brokenItem(Request $request){
		$validator = Validator::make ( $request->all (), [
				'unique_id' => 'required|alpha_num|exists:items,unique_id,itemStatus,!"Broken"',
				'itemNo' => 'required|numeric',
				'damage' => 'required|max:255',
				'status' => 'required|max:255',
				'dateBroken' => 'required|date',
		] );
	
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray ()
			) );
		} else {
			$updatetime = Carbon::now();
			$time = Carbon::parse(Carbon::now());
			$date = Carbon::parse($request['dateBroken']);
	
			$itemStatus = Item::where('unique_id',$request['unique_id'])->update(['itemStatus' => 'Broken','updated_at' => $updatetime]);
	
			$brokenItem = new Broken;
	
			$brokenItem->unique_id = $request['unique_id'];
			$brokenItem->damage = $request['damage'];
			$brokenItem->brokenStatus = $request['status'];
			$brokenItem->reported_by = Auth::guard ( 'inventory' )->user ()->id;
			$brokenItem->created_at = Carbon::create($date->year,$date->month,$date->day,$time->hour,$time->minute,$time->second);
			$brokenItem->updated_at = $updatetime;
			$brokenItem->save();
	
			
			
			$result = Broken::where('broken_logs.unique_id',$request['unique_id'])
			->leftJoin ( DB::raw ( '(SELECT unique_id,itemNo from items) items' ), function ($join) {
				$join->on ( 'broken_logs.unique_id', '=', 'items.unique_id' );
			} )
			->leftJoin ( DB::raw ( '(SELECT agent_id,first_name, last_name from admin_profiles) assignedSupport' ), function ($join) {
				$join->on ( 'broken_logs.reported_by', '=', 'assignedSupport.agent_id' );
			} )
			->orderby('broken_logs.created_at','desc')->first();
	
			return response ()->json ( [
					'success' => true,
					'response' => $result,
	
			] );
		};
	
	}
	public function showAgents(){
		
		$users = DB::table ( 'admin' )->leftJoin ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->get ();
		
		return view ("inventory.agents",['agents' => $users]);
	}
	public function showCreateAgent(){
		
		return view ("inventory.createAgent");
	}
	public function checkPassword(Request $request) {
	
		if( Auth::guard ( 'inventory' )->attempt ( [
					'email' => Auth::guard ( 'inventory' )->user ()->email,
					'password' => $request ['password']
			] )) {
				return response ()->json ( array (
					'success' => true,
					));
			} else {
				return response ()->json ( array (
					'success' => false,
					));
			}
		
	
	}
	
	public function showAddItem() {
		return view ( "inventory.addItem" );
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
				$borrowInfo = Borrow::where('unique_id',$request['item'])->orderBy('created_at','desc')->first();
						
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
			$updatetime = Carbon::now();
			$time = Carbon::parse(Carbon::now());
			$date = Carbon::parse($request['dateBorrowed']);
			
			$itemStatus = Item::where('unique_id',$request['unique_id'])->update(['itemStatus' => 'Not Available','updated_at' => $updatetime]);
			$borrowItem = new Borrow;
			$borrowItem->unique_id = $request['unique_id'];
			$borrowItem->borrower = $request['borrower'];
			$borrowItem->borrowerStationNo = $request['stationNo'];
			$borrowItem->borrowee = Auth::guard ( 'inventory' )->user ()->id;
			$borrowItem->created_at = Carbon::create($date->year,$date->month,$date->day,$time->hour,$time->minute,$time->second);
			$borrowItem->updated_at = $updatetime;
			$borrowItem->save();
			
			
			
			$result = Borrow::where('borrow_logs.unique_id',$request['unique_id'])
					->leftJoin(DB::raw('(select unique_id, itemNo, itemType, brand, model from items) items')
					,'borrow_logs.unique_id','=','items.unique_id')
					->leftJoin('admin_profiles','borrow_logs.borrowee','=','admin_profiles.agent_id')
					->orderBy('created_at','desc')->first();
			
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
			$updatetime = Carbon::now();
			$time = Carbon::parse(Carbon::now());
			$date = Carbon::parse($request['dateReturned']);
			$borrower = Borrow::where('unique_id',$request['unique_id'])->orderBy('created_at','desc')->first();
			$itemStatus = Item::where('unique_id',$request['unique_id'])->update(['itemStatus' => 'Available','updated_at' => $updatetime]);
			$returnItem = new ReturnItem;
			
			$returnItem->unique_id = $request['unique_id'];
			$returnItem->receiver = Auth::guard ( 'inventory' )->user ()->id;
			$returnItem->borrower = $borrower->borrower;
			$returnItem->created_at = Carbon::create($date->year,$date->month,$date->day,$time->hour,$time->minute,$time->second);
			$returnItem->updated_at = $updatetime;
			$returnItem->save();
			
			$result = ReturnItem::where('return_logs.unique_id',$request['unique_id'])
			->leftJoin(DB::raw('(select unique_id, itemNo, itemType, brand, model from items) items')
					,'return_logs.unique_id','=','items.unique_id')
			->leftJoin('admin_profiles','return_logs.receiver','=','admin_profiles.agent_id')
			->orderby('created_at','desc')->first();
	
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
	public function borrowSearch(Request $request){
		$borrows = Item::where('itemStatus','Not Available')
		->leftJoin(DB::raw('(select unique_id, borrowee,borrower,
				borrowerStationNo,created_at as dateBorrowed,updated_at from borrow_logs) borrow'),
				function($join){
					$join->on('items.unique_id','=','borrow.unique_id');
					$join->on('items.updated_at','=','borrow.updated_at');
				})
				->leftJoin('admin_profiles','borrow.borrowee','=','admin_profiles.agent_id')
				->orderBy('dateBorrowed','desc');
		
		$borrows = $borrows->newQuery();
		if($request['unique_id'] != null){
			$borrows->where('items.unique_id',$request['unique_id']);
		}
		if($request['borrower'] != null){
			$borrows->where('borrower',$request['borrower']);
		}
		if($request['borrowee'] != null){
			$borrows->where('borrowee',$request['borrower']);
		}
		
		if($request['dateBorrowed'] != null){
			$borrows->where('dateBorrowed','like', '%'.$request['dateBorrowed'].'%');
		}
		
		
		$names = DB::table('admin_profiles')->select('agent_id as id','first_name','last_name')->get();
		$clients = DB::table('client_profiles')->select('client_id as id','first_name','last_name')->get();
		
		foreach($clients as $client){
			array_push($names,$client);
		}
		$borrows = $borrows->get();
		foreach($borrows as $borrowedItem){
			foreach($names as $nm){
				if($nm->id == $borrowedItem->borrower){
					$borrowedItem->borrower = $nm->first_name.' '.$nm->last_name;
					
				}
			}
		}
		
		
		 
		return response ()->json ( [
					'success' => true,
					'response' => $borrows,
	
			] );
	}
	//Return Form advanced search
	
	public function returnSearch(Request $request){
		
		
		$returnedItems = Item::where('itemStatus','Available')
		->leftJoin(DB::raw('(select unique_id, receiver, borrower, 
				created_at as dateReturned ,updated_at from return_logs) returns'),
				function($join){
					$join->on('items.unique_id','=','returns.unique_id');
					$join->on('items.updated_at','=','returns.updated_at');
				})
				->leftJoin('admin_profiles','returns.receiver','=','admin_profiles.agent_id')->orderby('dateReturned','desc');
		
		
		$returnedItems = $returnedItems->newQuery();
		
		if($request['unique_id'] != null){
			$returnedItems->where('items.unique_id',$request['unique_id']);
		}
		if($request['borrower'] != null){
			$returnedItems->where('borrower',$request['borrower']);
		}
		if($request['receiver'] != null){
			$returnedItems->where('receiver',$request['receiver']);
		}
	if($request['dateReturned'] != null){
			$returnedItems->where('dateReturned','like', '%'.$request['dateReturned'].'%');
		}
		
		
	$names = DB::table('admin_profiles')->select('agent_id as id','first_name','last_name')->get();
		$clients = DB::table('client_profiles')->select('client_id as id','first_name','last_name')->get();
		
		foreach($clients as $client){
			array_push($names,$client);
		}
		$returnedItems = $returnedItems->get();
		foreach($returnedItems as $returnedItem){
			foreach($names as $nm){
				if($nm->id == $returnedItem->borrower){
					$returnedItem->borrower = $nm->first_name.' '.$nm->last_name;
				}
			}
		}
		
		return response ()->json ( [
					'success' => true,
					'response' => $returnedItems,
	
			] );
	}
	
	// Issue Form
	
	public function issueItem(Request $request){
		$validator = Validator::make ( $request->all (), [
				'unique_id' => 'required|alpha_num|exists:items,unique_id,itemStatus,!With Issue',
				'itemNo' => 'required|numeric',
				'damage' => 'required|max:255',
				'issue' => 'required|max:255',
				'dateReported' => 'required|date',
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray ()
			) );
		} else {
			$updatetime = Carbon::now();
			$time = Carbon::parse(Carbon::now());
			$date = Carbon::parse($request['dateBroken']);
			
			$itemStatus = Item::where('unique_id',$request['unique_id'])->update(['itemStatus' => 'With Issue',
					'updated_at' => $updatetime
			]);
			$issueItem = new Issue;
				
			$issueItem->unique_id = $request['unique_id'];
			$issueItem->damage = $request['damage'];
			$issueItem->issue = $request['issue'];
			$issueItem->reported_by = Auth::guard ( 'inventory' )->user ()->id;
			$issueItem->created_at = Carbon::create($date->year,$date->month,$date->day,$time->hour,$time->minute,$time->second);
			$issueItem->updated_at = $updatetime;
			$issueItem->save();
				
			$result = Issue::where('issue_logs.unique_id',$request['unique_id'])
			->leftJoin ( DB::raw ( '(SELECT unique_id,itemNo from items) items' ), function ($join) {
				$join->on ( 'issue_logs.unique_id', '=', 'items.unique_id' );
			} )
			->leftJoin ( DB::raw ( '(SELECT agent_id,first_name, last_name from admin_profiles) assignedSupport' ), function ($join) {
			$join->on ( 'issue_logs.reported_by', '=', 'assignedSupport.agent_id' );
		} )
			->orderby('issue_logs.created_at','desc')->first();
		
			return response ()->json ( [
					'success' => true,
					'response' => $result,
		
			] );
		};
	}
	
	public function issueInfo(Request $request){
		$itemInfo = Item::where('unique_id',$request['item'])->first();
		$issueInfo = Issue::where('unique_id',$request['item'])->orderby('created_at','desc')->first();
		if($issueInfo == null){
			return response ()->json ( [
					'success' => false
			] );
		}else{
			
		
			$name = DB::table('admin_profiles')->where('agent_id', $issueInfo['reported_by'])->first();
			$issueInfo->itemStatus = $itemInfo['itemStatus'];
			$issueInfo->itemNo = $itemInfo['itemNo'];
			if($name != null){
				$issueInfo['reported_by'] = $name->first_name.' '.$name->last_name;
			}
			return response ()->json ( [
					'success' => true, 'info' => $issueInfo
			] );
			
			
		}
	}
	
	public function repairItem(Request $request){
		$validator = Validator::make ( $request->all (), [
				'unique_id' => 'required|alpha_num|exists:items,unique_id,itemStatus,With Issue',
				'itemNo' => 'required|numeric',
				'dateRepair' => 'required|date',
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray ()
			) );
		} else {
			$time = Carbon::parse(Carbon::now());
			$date = Carbon::parse($request['dateRepair']);
				
			$itemStatus = Item::where('unique_id',$request['unique_id'])->update(['itemStatus' => 'Available']);
			
			$repairItem = Issue::where('unique_id',$request['unique_id'])->orderby('created_at','desc')
			->update(['updated_at' => Carbon::create($date->year,$date->month,$date->day,$time->hour,$time->minute,$time->second)]);
		
		
			$result = Issue::where('issue_logs.unique_id',$request['unique_id'])->leftJoin('items','issue_logs.unique_id','=','items.unique_id')
			->leftJoin('admin_profiles','issue_logs.reported_by','=','admin_profiles.agent_id')
			->orderby('issue_logs.created_at','desc')->first();
		
			$name = DB::table('admin_profiles')->where('agent_id', $result['reported_by'])->first();
		
		
			if($name != null){
				$result['reported_by'] = $name->first_name.' '.$name->last_name;
			}
		
			return response ()->json ( [
					'success' => true,
					'response' => $result,
		
			] );
		};
	}
	
	public function updateBroken(Request $request){
		$items = $request['items'];
		
		$validator = Validator::make ( $items, [
				'items' => 'required|exists:items,unique_id'
		] );
		
		$broken_logs = Broken::select(DB::raw('max(created_at) as created_at, unique_id, brokenStatus'))
		->groupBy('unique_id')->join(DB::raw('(Select unique_id,updated_at as dateUpdate from items) items'),
				function($join){
					$join->on('broken_logs.updated_at','>=','items.dateUpdate');
					$join->on('broken_logs.unique_id','=','items.unique_id');
				});
		
		if($request['mark'] == "Repaired"){
			$changeItemStatus ;
			foreach ($items as $key => $value){
				if($key == 0){
					$broken_logs->where('broken_logs.unique_id',$value);
					$changeItemStatus = Item::where('unique_id',$value);
				}else{
					$broken_logs->orWhere('broken_logs.unique_id',$value);
					$changeItemStatus->orWhere('unique_id',$value);
				}
			}
			$broken_logs = $broken_logs->update(['brokenStatus' => $request['mark']]);
			$changeItemStatus->update(['itemStatus' => "Available"]);
		}else{
			foreach ($items as $key => $value){
				if($key == 0){
					$broken_logs->where('broken_logs.unique_id',$value);
				}else{
					$broken_logs->orWhere('broken_logs.unique_id',$value);
				}
			}
			$broken_logs = $broken_logs->update(['brokenStatus' => $request['mark']]);
		}
		
		$result = Broken::select(DB::raw('max(created_at) as created_at, unique_id, brokenStatus, max(updated_at) as updated_at'))
		->groupBy('unique_id')->get();
		return response ()->json ( [
				'success' => true,
				'response' => $result,
		
		] );
	}
	public function showMaintenance(){
		return view("inventory.maintenance");
	}
}
