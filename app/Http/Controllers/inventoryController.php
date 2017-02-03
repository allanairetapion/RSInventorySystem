<?php

namespace App\Http\Controllers;

use App\IsUser;
use App\AdminProfile as AProfile;
use App\ClientProfile as CProfile;
use App\InventoryItem as Item;
use App\BorrowItem as Borrow;
use App\ItemIssues as Issue;
use App\DeployItem as Deploy;
use App\BrokenItem as Broken;
use App\RepairItem as Repair;
use App\MaintenanceActivity as mActivity;
use App\MaintenanceSchedule as mSchedule;
use App\MaintenanceArea as mArea;
use App\Admin as Admin;
use App\ReturnItem as ReturnItem;
use Illuminate\Http\Request;
use Validator;
use File;
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
	// Dashboard
	public function showIndex() {
		$items = Item::whereBetween ( 'updated_at', [ 
				Carbon::today (),
				Carbon::today ()->addDays ( 7 ) 
		] )->orderBy ( 'updated_at', 'desc' )->leftJoin ( DB::raw ( '(select client_id,first_name as morning_FN, last_name as morning_LN from client_profiles) cProfile' ), function ($join) {
			$join->on ( 'items.morningClient', '=', 'cProfile.client_id' );
		} )->leftJoin ( DB::raw ( '(select client_id,first_name as night_FN, last_name as night_LN from client_profiles) cProfile2' ), function ($join) {
			$join->on ( 'items.nightClient', '=', 'cProfile2.client_id' );
		} )->get ();
		
		$itemTypes = Item::select ( 'itemType as label', DB::raw ( 'Count(*) as value' ) )->groupBy ( 'itemType' )->get ();
		$borrowCount = 0;
		$returnCount = 0;
		$issueCount = 0;
		$brokenCount = 0;
		$mSchedToday = mSchedule::whereBetween ( 'start_date', [ 
				Carbon::today (),
				Carbon::tomorrow () 
		] )->count ();
		
		foreach ( $items as $item ) {
			if ($item->itemStatus == "Available") {
				$returnCount ++;
			} elseif ($item->itemStatus == "Not Available") {
				$borrowCount ++;
			} elseif ($item->itemStatus == "With Issue") {
				$issueCount ++;
			} else {
				$brokenCount ++;
			}
		}
		;
		$borrowData = [ 
				'label' => 'Borrowed',
				'color' => '#1c84c6',
				'data' => [ ] 
		];
		$returnData = [ 
				'label' => 'Returned',
				'color' => '#23c6c8',
				'data' => [ ] 
		];
		$issueData = [ 
				'label' => 'With issues',
				'color' => '#f8ac59',
				'data' => [ ] 
		];
		$brokenData = [ 
				'label' => 'Broken',
				'color' => '#ed5565',
				'data' => [ ] 
		];
		$summaryXaxis = [ ];
		for($i = 11; $i >= 0; $i --) {
			$date = Carbon::today ();
			$dateStart = Carbon::today ()->subMonths ( $i )->startOfMonth ();
			$dateEnd = Carbon::today ()->subMonths ( $i )->endOfMonth ();
			
			$data = [ 
					11 - $i 
			];
			array_push ( $borrowData ['data'], [ 
					11 - $i,
					Borrow::whereBetween ( 'created_at', [ 
							$dateStart,
							$dateEnd 
					] )->count () 
			] );
			
			array_push ( $returnData ['data'], [ 
					11 - $i,
					ReturnItem::whereBetween ( 'created_at', [ 
							$dateStart,
							$dateEnd 
					] )->count () 
			] );
			
			array_push ( $issueData ['data'], [ 
					11 - $i,
					Issue::whereBetween ( 'created_at', [ 
							$dateStart,
							$dateEnd 
					] )->count () 
			] );
			
			array_push ( $brokenData ['data'], [ 
					11 - $i,
					Broken::whereBetween ( 'created_at', [ 
							$dateStart,
							$dateEnd 
					] )->count () 
			] );
			array_push ( $summaryXaxis, [ 
					11 - $i,
					$dateStart->format ( 'M' ) 
			] );
		}
		
		return view ( 'inventory.index', [ 
				'items' => $items,
				'borrowCount' => $borrowCount,
				'returnCount' => $returnCount,
				'issueCount' => $issueCount,
				'brokenCount' => $brokenCount,
				'types' => $itemTypes,
				'mScheds' => $mSchedToday,
				'summaryData' => [ 
						$borrowData,
						$returnData,
						$issueData,
						$brokenData 
				],
				'summaryXaxis' => $summaryXaxis 
		] );
	}
	public function itemTypeSummary(Request $request) {
		$items = Item::where ( 'itemType', $request ['itemType'] )->get ();
		
		return response ()->json ( array (
				'success' => true,
				'response' => $items 
		) );
	}
	
	// Borrow Page
	public function showBorrow() {
		$borrowedId = Borrow::select ( DB::raw ( 'Max(id)' ) )->groupBy ( 'itemNo' )->get ();
		
		$borrowedItems = Item::leftJoin ( DB::raw ( '(select id,itemNo, borrowee,borrower,borrowerStationNo,created_at as dateBorrowed,updated_at from borrow_logs) borrow' ), function ($join) {
			$join->on ( 'items.itemNo', '=', 'borrow.itemNo' );
		} )->leftJoin ( 'admin_profiles', 'borrow.borrowee', '=', 'admin_profiles.agent_id' )->where ( 'itemStatus', 'Borrowed' )->whereIn ( 'borrow.id', $borrowedId )->orderBy ( 'dateBorrowed', 'desc' )->get ();
		
		$names = [ ];
		
		$agents = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' )->get ();
		$clients = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->get ();
		
		foreach ( $clients as $client ) {
			array_push ( $names, $client );
		}
		foreach ( $agents as $agent ) {
			array_push ( $names, $agent );
		}
		
		foreach ( $borrowedItems as $borrowedItem ) {
			foreach ( $names as $nm ) {
				if ($nm->id == $borrowedItem->borrower) {
					$borrowedItem->borrower = $nm->first_name . ' ' . $nm->last_name;
				}
			}
		}
		
		$itemNumbers = Item::select ( 'itemNo' )->get ();
		
		return view ( "inventory.borrow", [ 
				'borrowedItems' => $borrowedItems,
				'names' => $names,
				'agents' => $agents,
				'itemNumbers' => $itemNumbers 
		] );
	}
	public function borrowInfo(Request $request) {
		$itemInfo = Item::where ( 'itemNo', $request ['item'] )->first ();
		if ($itemInfo == null) {
			return response ()->json ( [ 
					'success' => false 
			] );
		} else {
			$borrowInfo = Borrow::where ( 'itemNo', $request ['item'] )->orderBy ( 'created_at', 'desc' )->first ();
			
			$name = DB::table ( 'admin_profiles' )->where ( 'agent_id', $borrowInfo ['borrower'] )->first ();
			if ($name == null) {
				$name = DB::table ( 'client_profiles' )->where ( 'client_id', $borrowInfo ['borrower'] )->first ();
			}
			
			if ($name != null) {
				$borrowInfo->borrowerName = $name->first_name . ' ' . $name->last_name;
			}
			return response ()->json ( [ 
					'success' => true,
					'info' => $itemInfo,
					'borrow' => $borrowInfo 
			] );
		}
	}
	public function borrowItem(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'itemNo' => 'required|exists:items,itemNo,itemStatus,"In-stock"',
				'borrower' => 'required|numeric',
				'stationNo' => 'required|numeric|max:255',
				'dateBorrowed' => 'required|date' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 400 );
		} else {
			$updatetime = Carbon::now ();
			$time = Carbon::parse ( Carbon::now () );
			$date = Carbon::parse ( $request ['dateBorrowed'] );
			
			$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [ 
					'itemStatus' => 'Borrowed',
					'stationNo' => $request ['stationNo'],
					'updated_at' => $updatetime 
			] );
			$borrowItem = new Borrow ();
			$borrowItem->itemNo = $request ['itemNo'];
			$borrowItem->borrower = $request ['borrower'];
			$borrowItem->borrowerStationNo = $request ['stationNo'];
			$borrowItem->borrowee = Auth::guard ( 'inventory' )->user ()->id;
			$borrowItem->created_at = Carbon::create ( $date->year, $date->month, $date->day, $time->hour, $time->minute, $time->second );
			$borrowItem->updated_at = $updatetime;
			$borrowItem->save ();
			
			$result = Borrow::where ( 'borrow_logs.itemNo', $request ['itemNo'] )->leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model from items) items' ), 'borrow_logs.itemNo', '=', 'items.itemNo' )->leftJoin ( DB::raw ( '(select agent_id, first_name, last_name from admin_profiles) admin_profiles' ), 'borrow_logs.borrowee', '=', 'admin_profiles.agent_id' )->

			orderBy ( 'borrow_logs.created_at', 'desc' )->first ();
			
			$name = DB::table ( 'admin_profiles' )->where ( 'agent_id', $result ['borrower'] )->first ();
			if ($name == null) {
				$name = DB::table ( 'client_profiles' )->where ( 'client_id', $result ['borrower'] )->first ();
			}
			
			if ($name != null) {
				$result ['borrower'] = $name->first_name . ' ' . $name->last_name;
			}
			return response ()->json ( [ 
					'success' => true,
					'response' => $result 
			] );
		}
		;
	}
	public function showDeployed() {
		$agents = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' )->get ();
		$borrowedId = Borrow::select ( DB::raw ( 'Max(id)' ) )->groupBy ( 'itemNo' )->get ();
		
		$deployedId = Deploy::select ( DB::raw ( 'Max(id)' ) )->groupBy ( 'itemNo' )->get ();
		$deployedItems = Deploy::leftJoin ( DB::raw ( '(select itemNo, unique_id, brand,stationNo, itemStatus,itemType,model from items) items' ), 'deploy_logs.itemNo', '=', 'items.itemNo' )->leftJoin ( 'admin_profiles', 'deploy_logs.deploy_by', '=', 'admin_profiles.agent_id' )->where ( 'itemStatus', 'Deployed' )->whereIn ( 'deploy_logs.id', $deployedId )->orderby ( 'deploy_logs.created_at', 'desc' )->get ();
		$itemNumbers = Item::select ( 'itemNo' )->get ();
		
		return view ( "inventory.deployed", [ 
				'deployedItems' => $deployedItems,
				
				'agents' => $agents,
				'itemNumbers' => $itemNumbers 
		] );
	}
	public function deployItem(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'itemNo' => 'required|exists:items,itemNo,itemStatus,"In-stock"',
				'stationNo' => 'required|numeric|unique:items,stationNo' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 400 );
		} else {
			
			$deployItem = new Deploy ();
			$deployItem->itemNo = $request ['itemNo'];
			$deployItem->stationNo = $request ['stationNo'];
			$deployItem->deploy_by = Auth::guard ( 'inventory' )->user ()->id;
			$deployItem->save ();
			
			$updateItem = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [ 
					'itemStatus' => 'Deployed',
					'stationNo' => $request ['stationNo'] 
			] );
			$result = Deploy::leftJoin ( DB::raw ( '(select itemNo, unique_id, brand,stationNo, itemStatus,itemType,model from items) items' ), 'deploy_logs.itemNo', '=', 'items.itemNo' )->where ( 'id', $deployItem->id )->leftJoin ( 'admin_profiles', 'deploy_logs.deploy_by', '=', 'admin_profiles.agent_id' )->first ();
			
			return response ()->json ( [ 
					'success' => true,
					'response' => $result 
			] );
		}
	}
	public function deploySearch(Request $request) {
		$columns = [ 
				"deploy_logs.itemNo",
				"unique_id",
				"itemType",
				"brand",
				"model",
				"stationNo",
				"first_name",
				"last_name" 
		];
		$deployItems = Deploy::leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model
				from items) items' ), function ($join) {
			$join->on ( 'deploy_logs.itemNo', '=', 'items.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name, last_name
				from admin_profiles) admin_profiles' ), 'deploy_logs.deploy_by', '=', 'admin_profiles.agent_id' )->orderBy ( 'created_at', 'desc' );
		$deployItems = $deployItems->newQuery ();
		foreach ( $columns as $column ) {
			$deployItems->orWhere ( $column, $request ['deploySearch'] );
		}
		return response ()->json ( [ 
				'success' => true,
				'response' => $deployItems->get () 
		] );
	}
	public function deployAdvancedSearch(Request $request) {
		$deployItems = Deploy::leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model
				from items) items' ), function ($join) {
			$join->on ( 'deploy_logs.itemNo', '=', 'items.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name, last_name
				from admin_profiles) admin_profiles' ), 'deploy_logs.deploy_by', '=', 'admin_profiles.agent_id' )->orderBy ( 'created_at', 'desc' );
		$deployItems = $deployItems->newQuery ();
		if ($request ['itemNo'] != null) {
			$deployItems->where ( 'items.itemNo', $request ['itemNo'] );
		}
		if ($request ['unique_id'] != null) {
			$deployItems->where ( 'items.unique_id', $request ['unique_id'] );
		}
		if ($request ['deployed_by'] != null) {
			$deployItems->where ( 'deploy_by', $request ['borrower'] );
		}
		if ($request ['dateDeployed'] != null) {
			$deployItems->where ( 'created_at', 'like', '%' . $request ['dateBorrowed'] . '%' );
		}
		$deployItems = $deployItems->get ();
		return response ()->json ( [ 
				'success' => true,
				'response' => $deployItems 
		] );
	}
	public function showReturn() {
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		
		$returnedItems = Item::leftJoin ( DB::raw ( '(select id,itemNo, receiver, borrower,
				created_at as dateReturned ,updated_at from return_logs) returnInfo' ), function ($join) {
			$join->on ( 'items.itemNo', '=', 'returnInfo.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name as agent_FN, last_name as agent_LN
				from admin_profiles) admin_profiles' ), 'returnInfo.receiver', '=', 'admin_profiles.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'returnInfo.borrower', '=', 'names.id' );
		} )->orderby ( 'dateReturned', 'desc' )->get ();
		
		$names = [ ];
		
		$agents = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' )->get ();
		$clients = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->get ();
		
		foreach ( $clients as $client ) {
			array_push ( $names, $client );
		}
		foreach ( $agents as $agent ) {
			array_push ( $names, $agent );
		}
		
		$itemNumbers = Item::select ( 'itemNo' )->get ();
		return view ( "inventory.return", [ 
				'returnedItems' => $returnedItems,
				'clients' => $names,
				'agents' => $agents,
				'itemNumbers' => $itemNumbers 
		] );
	}
	public function returnItem(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'itemNo' => 'required|exists:items,itemNo,itemStatus,"Borrowed"',
				'dateReturned' => 'required|date' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$updatetime = Carbon::now ();
			$time = Carbon::parse ( Carbon::now () );
			$date = Carbon::parse ( $request ['dateReturned'] );
			
			$returnItem = new ReturnItem ();
			
			$returnItem->itemNo = $request ['itemNo'];
			$returnItem->receiver = Auth::guard ( 'inventory' )->user ()->id;
			$returnItem->borrower = $request ['borrower'];
			$returnItem->created_at = Carbon::create ( $date->year, $date->month, $date->day, $time->hour, $time->minute, $time->second );
			$returnItem->updated_at = $updatetime;
			$returnItem->save ();
			
			$result = ReturnItem::where ( 'return_logs.itemNo', $request ['itemNo'] )->leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model from items) items' ), 'return_logs.itemNo', '=', 'items.itemNo' )->leftJoin ( DB::raw ( '(select agent_id, first_name, last_name from admin_profiles) admin_profiles' ), 'return_logs.receiver', '=', 'admin_profiles.agent_id' )->orderby ( 'created_at', 'desc' )->first ();
			
			$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [ 
					'itemStatus' => 'In-stock',
					'stationNo' => $result ['id'],
					'updated_at' => $updatetime 
			] );
			
			$name = DB::table ( 'admin_profiles' )->where ( 'agent_id', $result ['borrower'] )->first ();
			if ($name == null) {
				$name = DB::table ( 'client_profiles' )->where ( 'client_id', $result ['borrower'] )->first ();
			}
			
			if ($name != null) {
				$result ['borrower'] = $name->first_name . ' ' . $name->last_name;
			}
			
			return response ()->json ( [ 
					'success' => true,
					'response' => $result 
			] );
		}
		;
	}
	public function showAgents() {
		$users = DB::table ( 'admin' )->leftJoin ( DB::raw ( '(select agent_id, first_name, last_name from admin_profiles) agents' ), 'admin.id', '=', 'agents.agent_id' )->get ();
		return view ( "inventory.agents", [ 
				'agents' => $users 
		] );
	}
	public function showCreateAgent() {
		return view ( "inventory.createAgent" );
	}
	public function checkPassword(Request $request) {
		if (Auth::guard ( 'inventory' )->attempt ( [ 
				'email' => Auth::guard ( 'inventory' )->user ()->email,
				'password' => $request ['password'] 
		] )) {
			return response ()->json ( array (
					'success' => true 
			) );
		} else {
			return response ()->json ( array (
					'success' => false 
			) );
		}
	}
	public function showAddItem() {
		$items = Item::all ();
		$itemTypes = Item::select ( 'itemType' )->distinct ()->get ();
		return view ( "inventory.addItem", [ 
				'items' => $items,
				'itemTypes' => $itemTypes 
		] );
	}
	public function addItem(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'serial_no' => 'required|alpha_num|unique:items,unique_id',
				'company' => 'required|max:255',
				'brand' => 'required|max:255',
				'model' => 'required|max:255',
				'itemType' => 'required|max:255',
				'dateArrived' => 'required|max:255',
				'specification' => 'required|min:15' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 400 );
		} else {
			$filecount = 1;
			$attachmentpath = "";
			if ($request ['photo'] != null) {
				$image = $request->file ( 'photo' );
				$imgvalidatoR = Validator::make ( $image, [ 
						'photo' => 'image|max:10485760' 
				] );
				foreach ( $image as $file ) {
					$ext = $file->guessClientExtension ();
					$imageName = $request ['unique_id'] . "_" . uniqid () . "." . $ext;
					
					$file->move ( public_path ( '/inventory/' ), $imageName );
					$filecount ++;
					$attachmentpath = $attachmentpath . "/inventory/" . $imageName . ",";
				}
			}
			
			$itemPrefix = explode ( " ", $request ['company'] );
			$itemNo = "";
			foreach ( $itemPrefix as $prefix ) {
				$itemNo = $itemNo . substr ( $prefix, 0, 1 );
			}
			;
			$itemNo = $itemNo . (Item::where ( 'company', $request ['company'] )->count () + 1);
			$time = Carbon::parse ( Carbon::now () );
			$date = Carbon::parse ( $request ['dateArrived'] );
			$dateArrived = Carbon::create ( $date->year, $date->month, $date->day, $time->hour, $time->minute, $time->second );
			$newItem = Item::create ( [ 
					'unique_id' => $request ['serial_no'],
					'itemNo' => $itemNo,
					'company' => $request ['company'],
					'stationNo' => 0,
					'brand' => $request ['brand'],
					'model' => $request ['model'],
					'itemType' => $request ['itemType'],
					'specification' => $request ['specification'],
					'photo' => $attachmentpath,
					'created_at' => $dateArrived,
					'itemStatus' => "In-stock" 
			] );
			$result = [ 
					'unique_id' => $request ['serial_no'],
					'itemNo' => $itemNo,
					'itemType' => $request ['itemType'],
					'brand' => $request ['brand'],
					'model' => $request ['model'],
					'dateArrived' => $dateArrived 
			];
			return response ()->json ( [ 
					'success' => true,
					'response' => $result 
			] );
		}
		;
	}
	public function itemInfo(Request $request) {
		$itemInfo = Item::where ( 'itemNo', $request ['item'] )->first ();
		
		if ($itemInfo == null) {
			return response ()->json ( [ 
					'success' => false 
			] );
		} else {
			return response ()->json ( [ 
					'success' => true,
					'info' => $itemInfo 
			] );
		}
	}
	
	// Borrow form search and advanced search
	public function borrowSearch(Request $request) {
		$columns = [ 
				"borrow_logs.itemNo",
				"unique_id",
				"itemType",
				"brand",
				"model",
				"borrowerStationNo",
				"first_name",
				"last_name",
				"agent_FN",
				"agent_LN" 
		];
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		
		$borrows = Borrow::leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model
				from items) items' ), function ($join) {
			$join->on ( 'borrow_logs.itemNo', '=', 'items.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name as agent_FN, last_name as agent_LN
				from admin_profiles) admin_profiles' ), 'borrow_logs.borrowee', '=', 'admin_profiles.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'borrow_logs.borrower', '=', 'names.id' );
		} )->orderBy ( 'created_at', 'desc' );
		$borrows = $borrows->newQuery ();
		foreach ( $columns as $column ) {
			$borrows->orWhere ( $column, $request ['borrowSearch'] );
		}
		return response ()->json ( [ 
				'success' => true,
				'response' => $borrows->get () 
		] );
	}
	public function borrowAdvancedSearch(Request $request) {
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		
		$borrows = Borrow::leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model
				from items) items' ), function ($join) {
			$join->on ( 'borrow_logs.itemNo', '=', 'items.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name as agent_FN, last_name as agent_LN
				from admin_profiles) admin_profiles' ), 'borrow_logs.borrowee', '=', 'admin_profiles.agent_id' )
				->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
					$join->on ( 'borrow_logs.borrower', '=', 'names.id' );
				} )->orderBy ( 'created_at', 'desc' )
		->orderBy ( 'created_at', 'desc' );
		
		$borrows = $borrows->newQuery ();
		if ($request ['itemNo'] != null) {
			$borrows->where ( 'items.itemNo', $request ['itemNo'] );
		}
		if ($request ['unique_id'] != null) {
			$borrows->where ( 'items.unique_id', $request ['unique_id'] );
		}
		if ($request ['borrower'] != null) {
			$borrows->where ( 'borrower', $request ['borrower'] );
		}
		if ($request ['borrowee'] != null) {
			$borrows->where ( 'borrowee', $request ['borrowee'] );
		}
		
		if ($request ['dateBorrowed'] != null) {
			$borrows->where ( 'created_at', 'like', '%' . $request ['dateBorrowed'] . '%' );
		}
		
		$borrows = $borrows->get ();
		
		return response ()->json ( [ 
				'success' => true,
				'response' => $borrows 
		] );
	}
	// Return Form search and advanced search
	public function returnSearch(Request $request) {
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		$columns = [ 
				"returnInfo.itemNo",
				"unique_id",
				"itemType",
				"brand",
				"model",
				"first_name",
				"last_name",
				"agent_FN",
				"agent_LN" 
		];
		
		$returnedItems = Item::leftJoin ( DB::raw ( '(select id,itemNo, receiver, borrower,
				created_at as dateReturned ,updated_at from return_logs) returnInfo' ), function ($join) {
			$join->on ( 'items.itemNo', '=', 'returnInfo.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name as agent_FN, last_name as agent_LN
				from admin_profiles) admin_profiles' ), 'returnInfo.receiver', '=', 'admin_profiles.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'returnInfo.borrower', '=', 'names.id' );
		} )->orderby ( 'dateReturned', 'desc' );
		
		$returnedItems = $returnedItems->newQuery ();
		foreach ( $columns as $column ) {
			$returnedItems->orWhere ( $column, $request ['returnSearch'] );
		}
		return response ()->json ( [ 
				'success' => true,
				'response' => $returnedItems->get () 
		] );
	}
	public function returnAdvancedSearch(Request $request) {
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		$returnedItems = ReturnItem::leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model
				from items) items' ), function ($join) {
			$join->on ( 'return_logs.itemNo', '=', 'items.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name as agent_FN, last_name as agent_LN
				from admin_profiles) admin_profiles' ), 'return_logs.receiver', '=', 'admin_profiles.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'return_logs.borrower', '=', 'names.id' );
		} )->orderBy ( 'created_at', 'desc' );
		
		$returnedItems = $returnedItems->newQuery ();
		
		if ($request ['itemNo'] != null) {
			$returnedItems->where ( 'items.itemNo', $request ['itemNo'] );
		}
		if ($request ['unique_id'] != null) {
			$returnedItems->where ( 'items.unique_id', $request ['unique_id'] );
		}
		if ($request ['borrower'] != null) {
			$returnedItems->where ( 'borrower', $request ['borrower'] );
		}
		if ($request ['receiver'] != null) {
			$returnedItems->where ( 'receiver', $request ['receiver'] );
		}
		if ($request ['dateReturned'] != null) {
			$returnedItems->where ( 'created_at', 'like', '%' . $request ['dateReturned'] . '%' );
		}
		
		return response ()->json ( [ 
				'success' => true,
				'response' => $returnedItems->get () 
		] );
	}
	
	// Issue Form
	public function showIssues() {
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		$issueId = Issue::select ( DB::raw ( 'Max(id)' ) )->groupBy ( 'itemNo' )->get ();
		
		$issueItems = Issue::leftJoin ( 'items', function ($join) {
			$join->on ( 'items.itemNo', '=', 'issue_logs.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name as agent_FN, last_name as agent_LN
				from admin_profiles) admin_profiles' ), 'issue_logs.reported_by', '=', 'admin_profiles.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'issue_logs.itemUser', '=', 'names.id' );
		} )->where ( 'itemStatus', 'With Issue' )->whereIn ( 'issue_logs.id', $issueId )->orderBy ( 'issue_logs.created_at', 'desc' )->get ();
		
		$names = [ ];
		$agents = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' )->get ();
		$clients = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->get ();
		
		foreach ( $clients as $client ) {
			array_push ( $names, $client );
		}
		foreach ( $agents as $agent ) {
			array_push ( $names, $agent );
		}
		
		$itemNumbers = Item::select ( 'itemNo' )->get ();
		return view ( "inventory.issues", [ 
				'issueItems' => $issueItems,
				'agents' => $agents,
				'users' => $names,
				'itemNumbers' => $itemNumbers 
		] );
	}
	public function issueItem(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'itemNo' => 'required|exists:items,itemNo,itemStatus,!"With Issue"',
				'item_user' => 'required|',
				'damage' => 'required|max:255',
				'summary' => 'required|min:15|max:10000',
				'dateReported' => 'required|date' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 400 );
		} else {
			$updatetime = Carbon::now ();
			$time = Carbon::parse ( Carbon::now () );
			$date = Carbon::parse ( $request ['dateReported'] );
			
			$issueItem = new Issue ();
			
			$issueItem->itemNo = $request ['itemNo'];
			$issueItem->damage = $request ['damage'];
			$issueItem->issue = $request ['summary'];
			$issueItem->itemUser = $request ['item_user'];
			$issueItem->reported_by = Auth::guard ( 'inventory' )->user ()->id;
			$issueItem->created_at = Carbon::create ( $date->year, $date->month, $date->day, $time->hour, $time->minute, $time->second );
			$issueItem->updated_at = $updatetime;
			$issueItem->save ();
			
			$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [ 
					'itemStatus' => 'With Issue',
					'updated_at' => $updatetime 
			] );
			
			$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
			$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
			
			$result = Issue::where ( 'issue_logs.id', $issueItem->id )->leftJoin ( DB::raw ( '(SELECT unique_id,itemNo,itemType,brand,model from items) items' ), function ($join) {
				$join->on ( 'issue_logs.itemNo', '=', 'items.itemNo' );
			} )->leftJoin ( DB::raw ( '(SELECT agent_id,first_name as agent_FN, last_name as agent_LN from admin_profiles) assignedSupport' ), function ($join) {
				$join->on ( 'issue_logs.reported_by', '=', 'assignedSupport.agent_id' );
			} )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
				$join->on ( 'issue_logs.itemUser', '=', 'names.id' );
			} )->first ();
			
			return response ()->json ( [ 
					'success' => true,
					'response' => $result 
			] );
		}
		;
	}
	public function issueSearch(Request $request) {
		$columns = [ 
				"issue_logs.itemNo",
				"unique_id",
				"itemType",
				"brand",
				"model",
				"itemUser",
				"first_name",
				"last_name",
				"agent_FN",
				"agent_LN" 
		];
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		
		$issueItems = Issue::leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model
				from items) items' ), function ($join) {
			$join->on ( 'issue_logs.itemNo', '=', 'items.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name as agent_FN, last_name as agent_LN
				from admin_profiles) admin_profiles' ), 'issue_logs.reported_by', '=', 'admin_profiles.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'issue_logs.itemUser', '=', 'names.id' );
		} )->orderBy ( 'created_at', 'desc' );
		
		$issueItems = $issueItems->newQuery ();
		foreach ( $columns as $column ) {
			$issueItems->orWhere ( $column, $request ['issueSearch'] );
		}
		return response ()->json ( [ 
				'success' => true,
				'response' => $issueItems->get () 
		] );
	}
	public function issueAdvancedSearch(Request $request) {
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		
		$issueItems = Issue::leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model
				from items) items' ), function ($join) {
			$join->on ( 'issue_logs.itemNo', '=', 'items.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name as agent_FN, last_name as agent_LN
				from admin_profiles) admin_profiles' ), 'issue_logs.reported_by', '=', 'admin_profiles.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'issue_logs.itemUser', '=', 'names.id' );
		} )->orderBy ( 'created_at', 'desc' );
		$issueItems = $issueItems->newQuery ();
		
		if ($request ['itemNo'] != null) {
			$issueItems->where ( 'items.itemNo', $request ['itemNo'] );
		}
		if ($request ['unique_id'] != null) {
			$issueItems->where ( 'items.unique_id', $request ['unique_id'] );
		}
		if ($request ['reported_by'] != null) {
			$issueItems->where ( 'reported_by', $request ['reported_by'] );
		}
		if ($request ['dateReported'] != null) {
			$issueItems->where ( 'created_at', 'like', '%' . $request ['dateReturned'] . '%' );
		}
		$issueItems = $issueItems->get ();
		
		return response ()->json ( [ 
				'success' => true,
				'response' => $issueItems 
		] );
	}
	public function repairItem(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'itemNo' => 'required|exists:items,itemNo' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$item = Issue::select ( 'damage', 'itemStatus' )->where ( 'issue_logs.itemNo', $request ['itemNo'] )->leftJoin ( 'items', 'items.itemNo', '=', 'issue_logs.itemNo' )->orderBy ( 'issue_logs.created_at', 'desc' )->first ();
			if ($item ['itemStatus'] != "With Issue" && $item ['itemStatus'] != "Broken") {
				return response ()->json ( array (
						'success' => false,
						'errors' => [ 
								'itemNo' => 'This Item is already Repaired' 
						] 
				) );
			}
			$repairItem = new Repair ();
			$repairItem->itemNo = $request ['itemNo'];
			$repairItem->damage = $item ['damage'];
			$repairItem->repaired_by = Auth::guard ( 'inventory' )->user ()->id;
			$repairItem->save ();
			
			$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [ 
					'itemStatus' => 'In-stock',
					'stationNo' => 0 
			] );
			
			return response ()->json ( [ 
					'success' => true 
			] );
		}
		;
	}
	// Broken Form
	public function showBroken() {
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		$brokenId = Broken::select ( DB::raw ( 'Max(id)' ) )->groupBy ( 'itemNo' )->get ();
		
		$brokenItems = Broken::leftJoin ( 'items', function ($join) {
			$join->on ( 'items.itemNo', '=', 'broken_logs.itemNo' );
		} )->leftJoin ( DB::raw ( '(SELECT agent_id,first_name as agent_FN, last_name as agent_LN from admin_profiles) assignedSupport' ), function ($join) {
			$join->on ( 'broken_logs.reported_by', '=', 'assignedSupport.agent_id' );
		} )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'broken_logs.itemUser', '=', 'names.id' );
		} )->where ( 'itemStatus', 'Broken' )->whereIn ( 'broken_logs.id', $brokenId )->orderBy ( 'broken_logs.created_at', 'desc' )->get ();
		
		$itemNumbers = Item::select ( 'itemNo' )->get ();
		return view ( "inventory.broken", [ 
				'brokenItems' => $brokenItems,
				'agents' => $first->get (),
				'users' => $second->get (),
				'itemNumbers' => $itemNumbers 
		] );
	}
	public function brokenItem(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'itemNo' => 'required|exists:items,itemNo',
				'damage' => 'required|max:255',
				'item_user' => 'required',
				'status' => 'required|max:255',
				'summary' => 'required|min:15|max:10000',
				'dateReported' => 'required|date' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$item = Broken::select ( 'damage', 'itemStatus' )->where ( 'broken_logs.itemNo', $request ['itemNo'] )->leftJoin ( 'items', 'items.itemNo', '=', 'broken_logs.itemNo' )->orderBy ( 'broken_logs.created_at', 'desc' )->first ();
			
			if ($item ['itemStatus'] == "With Issue" && $item ['itemStatus'] == "Broken") {
				return response ()->json ( array (
						'success' => false,
						'errors' => [ 
								'itemNo' => 'This Item is already Reported' 
						] 
				) );
			}
			$updatetime = Carbon::now ();
			$time = Carbon::parse ( Carbon::now () );
			$date = Carbon::parse ( $request ['dateReported'] );
			
			$brokenItem = new Broken ();
			
			$brokenItem->itemNo = $request ['itemNo'];
			$brokenItem->damage = $request ['damage'];
			$brokenItem->brokenSummary = $request ['summary'];
			$brokenItem->itemUser = $request ['item_user'];
			$brokenItem->brokenStatus = $request ['status'];
			$brokenItem->reported_by = Auth::guard ( 'inventory' )->user ()->id;
			$brokenItem->created_at = Carbon::create ( $date->year, $date->month, $date->day, $time->hour, $time->minute, $time->second );
			$brokenItem->updated_at = $updatetime;
			$brokenItem->save ();
			
			$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [ 
					'itemStatus' => 'Broken',
					'updated_at' => $updatetime 
			] );
			$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
			$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
			
			$result = Broken::where ( 'broken_logs.itemNo', $request ['itemNo'] )->leftJoin ( DB::raw ( '(SELECT unique_id,itemNo,itemType,brand,model from items) items' ), function ($join) {
				$join->on ( 'broken_logs.itemNo', '=', 'items.itemNo' );
			} )->leftJoin ( DB::raw ( '(SELECT agent_id,first_name as agent_FN, last_name as agent_LN from admin_profiles) assignedSupport' ), function ($join) {
				$join->on ( 'broken_logs.reported_by', '=', 'assignedSupport.agent_id' );
			} )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
				$join->on ( 'broken_logs.itemUser', '=', 'names.id' );
			} )->first ();
			
			return response ()->json ( [ 
					'success' => true,
					'response' => $result 
			] );
		}
		;
	}
	public function brokenSearch(Request $request) {
		$columns = [ 
				"broken_logs.itemNo",
				"unique_id",
				"itemType",
				"brand",
				"model",
				"itemUser",
				"first_name",
				"last_name",
				"agent_FN",
				"agent_LN" 
		];
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		
		$brokenItems = Broken::leftJoin ( 'items', function ($join) {
			$join->on ( 'items.itemNo', '=', 'broken_logs.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name as agent_FN, last_name as agent_LN
				from admin_profiles) admin_profiles' ), 'broken_logs.reported_by', '=', 'admin_profiles.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'broken_logs.itemUser', '=', 'names.id' );
		} )->orderBy ( 'broken_logs.created_at', 'desc' );
		
		$brokenItems = $brokenItems->newQuery ();
		foreach ( $columns as $column ) {
			$brokenItems->orWhere ( $column, $request ['brokenSearch'] );
		}
		return response ()->json ( [ 
				'success' => true,
				'response' => $brokenItems->get () 
		] );
	}
	public function brokenAdvancedSearch(request $request) {
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		
		$brokenItems = Broken::leftJoin ( 'items', function ($join) {
			$join->on ( 'items.itemNo', '=', 'broken_logs.itemNo' );
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name as agent_FN, last_name as agent_LN
				from admin_profiles) admin_profiles' ), 'broken_logs.reported_by', '=', 'admin_profiles.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'broken_logs.itemUser', '=', 'names.id' );
		} )->orderBy ( 'broken_logs.created_at', 'desc' );
		
		$brokenItems = $brokenItems->newQuery ();
		
		if ($request ['itemNo'] != null) {
			$brokenItems->where ( 'items.itemNo', $request ['itemNo'] );
		}
		if ($request ['unique_id'] != null) {
			$brokenItems->where ( 'items.unique_id', $request ['unique_id'] );
		}
		if ($request ['reported_by'] != null) {
			$brokenItems->where ( 'reported_by', $request ['reported_by'] );
		}
		if ($request ['status'] != null) {
			$brokenItems->where ( 'brokenStatus', $request ['status'] );
		}
		if ($request ['dateBroken'] != null) {
			$brokenItems->where ( 'created_at', 'like', '%' . $request ['dateBroken'] . '%' );
		}
		$brokenItems = $brokenItems->get ();
		
		return response ()->json ( [ 
				'success' => true,
				'response' => $brokenItems 
		] );
	}
	public function addItemSearch(Request $request) {
		$columns = [ 
				"unique_id",
				"itemType",
				"brand",
				"model",
				"created_at"
		];
		$items = Item::orWhere ( 'itemNo', $request ['search'] );
		
		foreach ( $columns as $column ) {
			$items->orWhere ( $column, $request ['search'] );
		}
		return response ()->json ( [ 
				'success' => true,
				'response' => $items->get () 
		] );
	}
	public function addItemAdvancedSearch(Request $request) {
		$items = Item::whereNotNull ( 'itemNo' );
		$items = $items->newQuery ();
		if ($request ['itemNo'] != null) {
			$items->where ( 'items.itemNo', $request ['itemNo'] );
		}
		if ($request ['unique_id'] != null) {
			$items->where ( 'items.unique_id', $request ['unique_id'] );
		}
		if ($request ['itemType'] != null) {
			$items->where ( 'items.itemType', $request ['itemType'] );
		}
		if ($request ['brand'] != null) {
			$items->where ( 'items.brand', $request ['brand'] );
		}
		if ($request ['model'] != null) {
			$items->where ( 'items.model', $request ['model'] );
		}
		if ($request ['dateArrived'] != null) {
			$items->where ( 'items.created_at', $request ['created_at'] );
		}
		return response ()->json ( [ 
				'success' => true,
				'response' => $items->get () 
		] );
	}
	public function updateBroken(Request $request) {
		$items = $request ['items'];
		
		$broken_logs = Broken::select ( DB::raw ( 'max(created_at) as created_at, unique_id, brokenStatus' ) )->groupBy ( 'itemNo' )->join ( DB::raw ( '(Select unique_id,itemNo,updated_at as dateUpdate from items) items' ), function ($join) {
			$join->on ( 'broken_logs.updated_at', '>=', 'items.dateUpdate' );
			$join->on ( 'broken_logs.itemNo', '=', 'items.itemNo' );
		} );
		
		if ($request ['mark'] == "Repaired") {
			$changeItemStatus;
			foreach ( $items as $key => $value ) {
				$item = Broken::select ( 'damage' )->where ( 'issue_logs.itemNo', $request ['itemNo'] )->orderBy ( 'issue_logs.created_at', 'desc' )->first ();
				
				$repairItem = new Repair ();
				$repairItem->itemNo = $request ['itemNo'];
				$repairItem->damage = $item;
				$repairItem->repaired_by = Auth::guard ( 'inventory' )->user ()->id;
				$repairItem->save ();
				
				$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [ 
						'itemStatus' => 'In-stock',
						'stationNo' => 0 
				] );
			}
		} else {
			foreach ( $items as $key => $value ) {
				if ($key == 0) {
					$broken_logs->where ( 'broken_logs.itemNo', $value );
				} else {
					$broken_logs->orWhere ( 'broken_logs.itemNo', $value );
				}
			}
			$broken_logs = $broken_logs->update ( [ 
					'brokenStatus' => $request ['mark'] 
			] );
		}
		
		$result = Broken::select ( DB::raw ( 'max(created_at) as created_at, itemNo, brokenStatus, max(updated_at) as updated_at' ) )->groupBy ( 'itemNo' )->get ();
		return response ()->json ( [ 
				'success' => true,
				'response' => $result 
		] );
	}
	// end Broken Form
	// Maintenance
	public function showMaintenance() {
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		$area = mArea::all ();
		$activity = mActivity::all ();
		
		$itemNumbers = Item::select ( 'itemNo' )->get ();
		return view ( "inventory.maintenance", [ 
				'areas' => $area,
				'activities' => $activity,
				'agents' => $first->get (),
				'users' => $second->get (),
				'itemNumbers' => $itemNumbers 
		] );
	}
	public function getMaintenaceItems() {
		$items = Item::where ( 'stationNo', '!=', 0 )->get ();
		
		return $items;
	}
	public function maintenanceItem($stationNo) {
		$items = Item::where ( 'stationNo', $stationNo )->get ();
		return response ()->json ( [ 
				'success' => true,
				'response' => $items 
		] );
	}
	public function addSchedule(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'title' => 'required',
				'activity' => 'required|min:1',
				'area' => 'required',
				'startScheduleDate' => 'required',
				'startScheduleTime' => 'required',
				'endScheduleDate' => 'required',
				'endScheduleTime' => 'required' 
		] );
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 400 );
		}
		$activities = "";
		foreach ( $request ['activity'] as $activity ) {
			$activities = $activities . $activity . ",";
		}
		;
		$agentNames = "";
		foreach ( $request ['agents'] as $agent ) {
			$agentNames = $agentNames . $agent . ",";
		}
		;
		
		/*
		 * $request ['startScheduleTime'] = $this->merTime($request ['startScheduleTime']);
		 * $request ['endScheduleTime'] = $this->merTime($request ['endScheduleTime']);
		 */
		
		$schedule = new mSchedule ();
		$schedule->title = $request ['title'];
		$schedule->agents = $agentNames;
		$schedule->status = $request ['status'];
		$schedule->activities = $activities;
		$schedule->area = $request ['area'];
		$schedule->start_date = $request ['startScheduleDate'] . ' ' . $request ['startScheduleTime'];
		$schedule->end_date = $request ['endScheduleDate'] . ' ' . $request ['endScheduleTime'];
		$schedule->save ();
		
		return response ()->json ( [ 
				'success' => true,
				'response' => [ 
						'id' => $schedule->id,
						'start_date' => $request ['startScheduleDate'] . ' ' . $request ['startScheduleTime'] 
				] 
		] );
	}
	public function merTime($t) {
		$mer = substr ( $t, - 2, 2 );
		$time = chop ( $t, $mer );
		if ($mer == "PM") {
			$adjustTime = explode ( ":", $time );
			$adjustTime [0] = $adjustTime [0] + 12;
			$time = $adjustTime [0] + ":" + $adjustTime [1];
		}
		return $time;
	}
	public function addActivity(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'activity' => 'required|max:255',
				'description' => 'required|max:255' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 400 );
		}
		
		$newActivity = new mActivity ();
		$newActivity->activity = $request ['activity'];
		$newActivity->description = $request ['description'];
		$newActivity->save ();
		
		$response = mActivity::orderBy ( 'created_at', 'desc' )->first ();
		return response ()->json ( [ 
				'success' => true,
				'response' => $response 
		] );
	}
	public function viewMaintenanceDetail($id) {
		$schedule = mSchedule::where ( 'id', $id )->get ();
		return $schedule;
	}
	public function maintenanceSchedules() {
		$schedules = mSchedule::leftJoin ( DB::raw ( '(select id as areaId, area from maintenance_areas) area' ), function ($join) {
			$join->on ( 'maintenance_schedules.area', '=', 'area.areaId' );
		} )->get ();
		
		$activity = mActivity::all ();
		$mSched = [ ];
		foreach ( $schedules as $schedule ) {
			
			$startdate = explode ( ' ', $schedule ['start_date'] );
			$enddate = explode ( ' ', $schedule ['end_date'] );
			$schedActivities = explode ( ',', $schedule ['activities'] );
			$schedText = "";
			
			foreach ( $schedActivities as $sched ) {
				foreach ( $activity as $k ) {
					if ($k->id == $sched)
						$schedText = $schedText . $k->activity . ", ";
				}
			}
			;
			array_push ( $mSched, [ 
					'id' => $schedule ['id'],
					'title' => $schedule ['title'],
					'start' => $startdate [0] . 'T' . $startdate [1] . ".196Z",
					'end' => $enddate [0] . 'T' . $enddate [1] . ".196Z",
					'description' => $schedule ['area'] . "\n" . $schedText 
			] );
		}
		;
		
		return $mSched;
	}
	public function maintenanceDashboard() {
		$mSchedToday = mSchedule::whereBetween ( 'start_date', [ 
				Carbon::today (),
				Carbon::today ()->addDays ( 7 ) 
		] )->orderBy ( 'start_date' )->get ();
		return $mSchedToday;
	}
	public function updateMaintenanceSchedule(Request $request) {
		$schedule = mSchedule::find ( $request ['schedID'] );
		
		$activities = "";
		foreach ( $request ['activity'] as $activity ) {
			$activities = $activities . $activity . ",";
		}
		;
		$agentNames = "";
		foreach ( $request ['agents'] as $agent ) {
			$agentNames = $agentNames . $agent . ",";
		}
		;
		
		$request ['startScheduleTime'] = $this->merTime ( $request ['startScheduleTime'] );
		$request ['endScheduleTime'] = $this->merTime ( $request ['endScheduleTime'] );
		
		$schedule->title = $request ['title'];
		$schedule->agents = $agentNames;
		$schedule->status = $request ['status'];
		$schedule->activities = $activities;
		$schedule->area = $request ['area'];
		$schedule->start_date = $request ['startScheduleDate'] . ' ' . $request ['startScheduleTime'];
		$schedule->end_date = $request ['endScheduleDate'] . ' ' . $request ['endScheduleTime'];
		$schedule->save ();
		
		return response ()->json ( [ 
				'success' => true,
				'response' => $schedule->id 
		] );
	}
	public function viewItemDetails($id) {
		$item = Item::where ( 'itemNo', $id )->leftJoin ( DB::raw ( '(select client_id, first_name as morningFN, last_name as morningLN from client_profiles) morning' ), function ($join) {
			$join->on ( 'items.morningClient', '=', 'morning.client_id' );
		} )->leftJoin ( DB::raw ( '(select client_id, first_name as eveningFN, last_name as eveningLN from client_profiles) evening' ), function ($join) {
			$join->on ( 'items.nightClient', '=', 'evening.client_id' );
		} )->first ();
		$borrows = Borrow::where ( 'itemNo', $id )->leftJoin ( DB::raw ( '(Select agent_id,first_name,last_name from admin_profiles) admin_profiles' ), function ($join) {
			$join->on ( 'borrow_logs.borrowee', '=', 'admin_profiles.agent_id' );
		} )->orderBy ( 'borrow_logs.created_at', 'desc' )->get ();
		
		$returns = ReturnItem::where ( 'itemNo', $id )->leftJoin ( DB::raw ( '(Select agent_id,first_name,last_name from admin_profiles) admin_profiles' ), function ($join) {
			$join->on ( 'return_logs.receiver', '=', 'admin_profiles.agent_id' );
		} )->orderBy ( 'return_logs.created_at', 'desc' )->get ();
		$issue = Issue::where ( 'itemNo', $id )->leftJoin ( DB::raw ( '(Select agent_id,first_name,last_name from admin_profiles) admin_profiles' ), function ($join) {
			$join->on ( 'issue_logs.reported_by', '=', 'admin_profiles.agent_id' );
		} )->orderBy ( 'issue_logs.created_at', 'desc' )->get ();
		
		$broken = Broken::where ( 'itemNo', $id )->leftJoin ( DB::raw ( '(Select agent_id,first_name,last_name from admin_profiles) admin_profiles' ), function ($join) {
			$join->on ( 'broken_logs.reported_by', '=', 'admin_profiles.agent_id' );
		} )->orderBy ( 'broken_logs.created_at', 'desc' )->get ();
		if ($item == null)
			abort ( 404 );
		
		$names = DB::table ( 'admin_profiles' )->select ( 'agent_id as id', 'first_name', 'last_name' )->get ();
		$clients = DB::table ( 'client_profiles' )->select ( 'client_id as id', 'first_name', 'last_name' )->get ();
		
		foreach ( $clients as $client ) {
			array_push ( $names, $client );
		}
		
		foreach ( $borrows as $borrow ) {
			foreach ( $names as $nm ) {
				if ($nm->id == $borrow->borrower) {
					$borrow->borrower = $nm->first_name . ' ' . $nm->last_name;
				}
			}
		}
		foreach ( $returns as $return ) {
			foreach ( $names as $nm ) {
				if ($nm->id == $return->borrower) {
					$return->borrower = $nm->first_name . ' ' . $nm->last_name;
				}
			}
		}
		
		return view ( 'inventory.itemDetails', [ 
				'item' => $item,
				'borrows' => $borrows,
				'returns' => $returns,
				'issues' => $issue,
				'brokens' => $broken,
				'clients' => $clients 
		] );
	}
	public function addItemPhoto(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'required|exists:items,unique_id' 
		] );
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 400 );
		}
		
		$item = Item::where ( 'unique_id', $request ['id'] )->first ();
		$photoNo = explode ( ",", $item ['photo'] );
		$photoNo = count ( $photoNo );
		$ext = explode ( ".", $request ['name'] );
		
		$imageName = $request ['id'] . "_" . uniqid () . "." . $ext [1];
		$dataUrl = explode ( ',', $request ['photo'] );
		$photo = base64_decode ( $dataUrl [1] );
		
		$filepath = public_path () . "/inventory/" . $imageName;
		
		file_put_contents ( $filepath, $photo );
		
		$itemUpdate = Item::where ( 'unique_id', $request ['id'] )->update ( [ 
				'photo' => $item ['photo'] . "/inventory/" . $imageName . "," 
		] );
		return response ()->json ( array (
				'success' => true,
				'response' => "/inventory/" . $imageName 
		) );
	}
	public function deleteItemPhoto(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'required|exists:items,unique_id' 
		] );
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 400 );
		}
		
		$item = Item::where ( 'unique_id', $request ['id'] )->first ();
		$itemPhoto = str_replace ( $request ['name'] . ',', '', $item ['photo'] );
		
		if (file_exists ( public_path ( $request ['name'] ) )) {
			unlink ( public_path ( $request ['name'] ) );
		}
		$itemUpdate = Item::where ( 'unique_id', $request ['id'] )->update ( [ 
				'photo' => $itemPhoto 
		] );
		return response ()->json ( array (
				'success' => true 
		) );
	}
	
	// edit Account
	public function showEditAccount() {
		return view ( 'inventory.editAccount' );
	}
	public function changePersonalInfo(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'exists:admin' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$changePersonalInfo = DB::table ( 'admin' )->leftJoin ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->where ( 'id', $request ['id'] )->update ( [ 
					'email' => $request ['email'],
					'first_name' => $request ['fname'],
					'last_name' => $request ['lname'],
					'admin.updated_at' => Carbon::now (),
					'admin_profiles.updated_at' => Carbon::now () 
			] );
			
			return response ()->json ( array (
					'success' => true 
			) );
		}
	}
	public function changePassword(Request $request) {
		if (Auth::guard ( 'inventory' )->attempt ( [ 
				'email' => Auth::guard ( 'admin' )->user ()->email,
				'password' => $request ['oldPassword'] 
		] )) {
			$validator = Validator::make ( $request->all (), [ 
					'id' => 'exists:admin',
					'password' => 'required|min:6|confirmed' 
			] );
			
			if ($validator->fails ()) {
				return response ()->json ( array (
						'success' => false,
						'errors' => $validator->getMessageBag ()->toArray () 
				) );
			} else {
				$changePassword = DB::table ( 'admin' )->where ( 'id', $request ['id'] )->update ( [ 
						'password' => bcrypt ( $request ['password'] ),
						'updated_at' => Carbon::now () 
				] );
				return response ()->json ( array (
						'success' => true 
				) );
			}
		} else {
			return response ()->json ( array (
					'success' => false,
					'errors' => [ 
							'oldPassword' => 'Wrong Password. Please try again.' 
					] 
			) );
		}
	}
	public function changeProfilePicture(Request $request) {
		$admin = AProfile::where ( 'agent_id', $request ['id'] )->first ();
		
		if ($admin != null) {
			$dataUrl = explode ( ',', $request ['photo'] );
			$photo = base64_decode ( $dataUrl [1] );
			$ext = explode ( "/", $dataUrl [0] );
			$ext = explode ( ";", $ext [1] );
			$filepath = public_path () . "/img/agents/" . Auth::guard ( 'inventory' )->user ()->id . "." . $ext [0];
			
			file_put_contents ( $filepath, $photo );
			$admin = AProfile::where ( 'agent_id', $request ['id'] )->update ( [ 
					'photo' => "/img/agents/" . Auth::guard ( 'inventory' )->user ()->id . "." . $ext [0] 
			] );
			return response ()->json ( array (
					'success' => true 
			) );
		}
		
		return response ()->json ( array (
				'success' => false,
				'error' => "User does not exists" 
		) );
	}
	public function showAgentProfile($id) {
		$agent = AProfile::where ( 'agent_id', $id )->first ();
		if ($agent == null) {
			return abort ( 404 );
		}
		$borrow_logs = Borrow::where ( 'borrowee', $id )->get ();
		$return_logs = ReturnItem::where ( 'receiver', $id )->get ();
		$issue_logs = Issue::where ( 'reported_by', $id )->get ();
		$broken_logs = Broken::where ( 'reported_by', $id )->get ();
		$xAxis = [ 
				"x" 
		];
		$borrow = [ 
				'Borrow' 
		];
		$return = [ 
				'Return' 
		];
		$issue = [ 
				'Issue' 
		];
		$broken = [ 
				'broken' 
		];
		for($i = 9; $i >= 0; $i --) {
			$date = Carbon::today ();
			$dateStart = Carbon::today ()->subMonths ( $i )->startOfMonth ();
			$dateEnd = Carbon::today ()->subMonths ( $i )->endOfMonth ();
			
			$borrowCount = Borrow::where ( 'borrowee', $agent ['agent_id'] )->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			$returnCount = ReturnItem::where ( 'receiver', $agent ['agent_id'] )->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			$issueCount = Issue::where ( 'reported_by', $agent ['agent_id'] )->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			$brokenCount = Broken::where ( 'reported_by', $agent ['agent_id'] )->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			array_push ( $borrow, $borrowCount );
			array_push ( $return, $returnCount );
			array_push ( $issue, $issueCount );
			array_push ( $broken, $brokenCount );
			$monthName = $dateStart->format ( 'M Y' );
			array_push ( $xAxis, $monthName );
		}
		;
		$names = DB::table ( 'admin_profiles' )->select ( 'agent_id as id', 'first_name', 'last_name' )->get ();
		$clients = DB::table ( 'client_profiles' )->select ( 'client_id as id', 'first_name', 'last_name' )->get ();
		
		foreach ( $clients as $client ) {
			array_push ( $names, $client );
		}
		
		foreach ( $borrow_logs as $borrow_log ) {
			foreach ( $names as $nm ) {
				if ($nm->id == $borrow_log->borrower) {
					$borrow_log->borrower = $nm->first_name . ' ' . $nm->last_name;
				}
			}
		}
		foreach ( $return_logs as $return_log ) {
			foreach ( $names as $nm ) {
				if ($nm->id == $return_log->borrower) {
					$return_log->borrower = $nm->first_name . ' ' . $nm->last_name;
				}
			}
		}
		return view ( 'inventory.agentProfile', [ 
				'agent' => $agent,
				'stats' => [ 
						$borrow,
						$return,
						$issue,
						$broken,
						$xAxis 
				],
				'borrows' => $borrow_logs,
				'returns' => $return_logs,
				'issues' => $issue_logs,
				'brokens' => $broken_logs 
		] );
	}
	public function updateItemDetails(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'itemNo' => 'required|exists:items,itemNo' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 400 );
		}
		$item = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [ 
				'morningClient' => $request ['morning_user'],
				'nightClient' => $request ['evening_user'] 
		] );
		
		return response ()->json ( array (
				'success' => true 
		) );
	}
	
	// Detailed
	public function showDetailed(Request $request) {
		$first = CProfile::select ( 'client_id as id', 'first_name', 'last_name' );
		$items = $items = Item::leftJoin ( DB::raw ( '(select client_id,first_name as morning_FN, last_name as morning_LN from client_profiles) cProfile' ), function ($join) {
			$join->on ( 'items.morningClient', '=', 'cProfile.client_id' );
		} )->leftJoin ( DB::raw ( '(select client_id,first_name as night_FN, last_name as night_LN from client_profiles) cProfile2' ), function ($join) {
			$join->on ( 'items.nightClient', '=', 'cProfile2.client_id' );
		} )->get ();
		
		$itemNumbers = Item::select ( 'itemNo' )->get ();
		
		if ($request->ajax ()) {
			return view ( 'inventory.stockItems', [ 
					'items' => $items 
			] )->render ();
		}
		return view ( "inventory.detailed", [ 
				'items' => $items,'itemNumbers' => $itemNumbers,
				'names' => $first->get()
		] );
	}
	public function detailSearch(Request $request) {
		$columns = [ 
				"itemNo",
				"unique_id",
				"itemType",
				"brand",
				"model",
				"cProfile.morning_FN",
				"cProfile.morning_LN",
				"cProfile2.night_FN",
				"cProfile2.night_LN",
				"items.created_at" 
		];
		
		$items = Item::leftJoin ( DB::raw ( '(select client_id,first_name as morning_FN, last_name as morning_LN from client_profiles) cProfile' ), function ($join) {
			$join->on ( 'items.morningClient', '=', 'cProfile.client_id' );
		} )->leftJoin ( DB::raw ( '(select client_id,first_name as night_FN, last_name as night_LN from client_profiles) cProfile2' ), function ($join) {
			$join->on ( 'items.nightClient', '=', 'cProfile2.client_id' );
		} );
		$items = $items->newQuery ();
		foreach ( $columns as $column ) {
			$items->orWhere ( $column, $request ['detailSearch'] );
		}
		return response ()->json ( [ 
				'success' => true,
				'response' => $items->get () 
		] );
	}
	public function detailAdvancedSearch(Request $request) {
		$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
		
		$items = Item::leftJoin ( DB::raw ( '(select client_id,first_name as morning_FN, last_name as morning_LN from client_profiles) cProfile' ), function ($join) {
			$join->on ( 'items.morningClient', '=', 'cProfile.client_id' );
		} )->leftJoin ( DB::raw ( '(select client_id,first_name as night_FN, last_name as night_LN from client_profiles) cProfile2' ), function ($join) {
			$join->on ( 'items.nightClient', '=', 'cProfile2.client_id' );
		} );
		
						$items = $items->newQuery ();
						if ($request ['itemNo'] != null) {
							$items->where ( 'itemNo', $request ['itemNo'] );
						}
						if ($request ['unique_id'] != null) {
							$items->where ( 'unique_id', $request ['unique_id'] );
						}
						if ($request ['morning_user'] != null) {
							$items->where ( 'morningClient', $request ['morning_user'] );
						}
						if ($request ['night_user'] != null) {
							$items->where ( 'nightClient', $request ['night_user'] );
						}
		
						if ($request ['dateArrived'] != null) {
							$items->where ( 'created_at', 'like', '%' . $request ['dateArrived'] . '%' );
						}
		
						$items = $items->get ();
		
						return response ()->json ( [
								'success' => true,
								'response' => $items
						] );
		
	}
	public function itemLevel(Request $request) {
		$itemTotalStock = [ 
				'In-stock' 
		];
		$itemTotalDeployed = [ 
				'Deployed' 
		];
		$xAxis = [ 
				'x' 
		];
		$itemInStock = Item::select ( 'itemType', DB::raw ( 'Count(*) as stockCount' ) )->groupBy ( 'itemType' )->get ();
		$itemDeployed = Item::select ( 'itemType', DB::raw ( 'Count(*) as deployedCount' ) )->where ( 'itemStatus', '!=', 'In-stock' )->groupBy ( 'itemType' )->get ();
		foreach ( $itemInStock as $stock ) {
			array_push ( $xAxis, $stock->itemType );
			array_push ( $itemTotalStock, $stock->stockCount );
		}
		foreach ( $itemDeployed as $deployed ) {
			array_push ( $itemTotalDeployed, $deployed->deployedCount );
		}
		return [ 
				$xAxis,
				$itemTotalStock,
				$itemTotalDeployed 
		];
	}
	public function stockItems(Request $request) {
		$stocks = Item::where ( 'itemType', $request ['itemType'] )->leftJoin ( DB::raw ( '(select client_id,first_name as morning_FN, last_name as morning_LN from client_profiles) cProfile' ), function ($join) {
			$join->on ( 'items.morningClient', '=', 'cProfile.client_id' );
		} )->leftJoin ( DB::raw ( '(select client_id,first_name as night_FN, last_name as night_LN from client_profiles) cProfile2' ), function ($join) {
			$join->on ( 'items.nightClient', '=', 'cProfile2.client_id' );
		} )->get ();
		return $stocks;
	}
}
