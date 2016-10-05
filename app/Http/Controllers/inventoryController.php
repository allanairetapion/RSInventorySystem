<?php

namespace App\Http\Controllers;

use App\IsUser;
use App\AdminProfile as AProfile;
use App\ClientProfile as CProfile;
use App\InventoryItem as Item;
use App\BorrowItem as Borrow;
use App\ItemIssues as Issue;
use App\BrokenItem as Broken;
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
		$items = Item::all ();
		$itemTypes = Item::select ( 'itemType as label', DB::raw ( 'Count(*) as value' ) )->groupBy ( 'itemType' )->get ();
		$borrowCount = 0;
		$returnCount = 0;
		$issueCount = 0;
		$brokenCount = 0;
		$itemReport = Item::select ( 'items.itemType', 'working', 'issue', 'broken', DB::raw ( 'Count(*) as overall' ) )->leftJoin ( DB::raw ( '(select itemType, count(*) as working from items where itemStatus = "Available" || itemStatus = "Not Available"
				group by itemType) working' ), function ($join) {
			$join->on ( 'items.itemType', '=', 'working.itemType' );
		} )->leftJoin ( DB::raw ( '(select itemType, count(*) as issue from items where itemStatus = "With Issue" 
				group by itemType) issue' ), function ($join) {
			$join->on ( 'items.itemType', '=', 'issue.itemType' );
		} )->leftJoin ( DB::raw ( '(select itemType, count(*) as broken from items where itemStatus = "Broken"
				group by itemType) broken' ), function ($join) {
			$join->on ( 'items.itemType', '=', 'broken.itemType' );
		} )->groupBy ( 'itemType' )->get ();
		
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
				'summaryData' => [ 
						$borrowData,
						$returnData,
						$issueData,
						$brokenData 
				],
				'summaryXaxis' => $summaryXaxis,
				'itemReports' => $itemReport 
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
		$borrowedItems = Item::where ( 'itemStatus', 'Not Available' )->leftJoin ( DB::raw ( '(select itemNo, borrowee,borrower,borrowerStationNo,created_at as dateBorrowed,updated_at from borrow_logs) borrow' ), function ($join) {
			$join->on ( 'items.itemNo', '=', 'borrow.itemNo' );
			$join->on ( 'items.updated_at', '=', 'borrow.updated_at' );
		} )->leftJoin ( 'admin_profiles', 'borrow.borrowee', '=', 'admin_profiles.agent_id' )->orderBy ( 'dateBorrowed', 'desc' )->get ();
		
		$names = [];
		
		$agents = AProfile::select('agent_id as id','first_name','last_name')->get();
		$clients = CProfile::select('client_id as id','first_name','last_name')->get();
		
		
		
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
				$borrowInfo ['borrower'] = $name->first_name . ' ' . $name->last_name;
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
				'itemNo' => 'required|exists:items,itemNo,itemStatus,"Available"',
				'borrower' => 'required|',
				'stationNo' => 'required|numeric|max:255',
				'dateBorrowed' => 'required|date'
		] );
	
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray ()
			),400 );
		} else {
			$updatetime = Carbon::now ();
			$time = Carbon::parse ( Carbon::now () );
			$date = Carbon::parse ( $request ['dateBorrowed'] );
				
			$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [
					'itemStatus' => 'Not Available',
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
				
			$result = Borrow::where ( 'borrow_logs.itemNo', $request ['itemNo'] )
			->leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model from items) items' ),
					'borrow_logs.itemNo', '=', 'items.itemNo' )
					->leftJoin ( DB::raw ( '(select agent_id, first_name, last_name from admin_profiles) admin_profiles' ),
							'borrow_logs.borrowee', '=', 'admin_profiles.agent_id' )
								
							->orderBy ( 'borrow_logs.created_at', 'desc' )->first ();
				
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
			]
			);
		}
		;
	}
	
	public function showReturn() {
		$returnedItems = Item::where ( 'itemStatus', 'Available' )->leftJoin ( DB::raw ( '( 
				select itemNo, receiver, borrower, created_at as dateReturned ,updated_at from return_logs) returns' ), function ($join) {
			$join->on ( 'items.itemNo', '=', 'returns.itemNo' );
			$join->on ( 'items.updated_at', '=', 'returns.updated_at' );
		} )->leftJoin ( 'admin_profiles', 'returns.receiver', '=', 'admin_profiles.agent_id' )
		->orderby ( 'dateReturned', 'desc' )->get ();
		
		$names = [];
		
		$agents = AProfile::select('agent_id as id','first_name','last_name')->get();
		$clients = CProfile::select('client_id as id','first_name','last_name')->get();
		
		
		
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
				'itemNo' => 'required|exists:items,itemNo,itemStatus,"Not Available"',
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
				
			$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [
					'itemStatus' => 'Available',
					'stationNo' => 0,
					'updated_at' => $updatetime
			] );
				
			$result = ReturnItem::where ( 'return_logs.itemNo', $request ['itemNo'] )
			->leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model from items) items' ),
					'return_logs.itemNo', '=', 'items.itemNo' )
					->leftJoin ( DB::raw ( '(select agent_id, first_name, last_name from admin_profiles) admin_profiles' ),
							'return_logs.receiver', '=', 'admin_profiles.agent_id' )
							->orderby ( 'created_at', 'desc' )->first ();
				
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
			]
			);
		}
		;
	}
	public function showDetailed() {
		$items = Item::all ();
		
		$borrows = Borrow::select ( DB::raw ( 'itemNo,max(created_at) as dateBorrowed' ) )->groupBy ( 'itemNo' )->get ();
		$returns = ReturnItem::select ( DB::raw ( 'itemNo,max(created_at) as dateReturned' ) )->groupBy ( 'itemNo' )->get ();
		$issues = Issue::select ( DB::raw ( 'itemNo,max(created_at) as dateReport,damage' ) )->groupBy ( 'itemNo' )->get ();
		$brokens = Broken::select ( DB::raw ( 'itemNo,max(created_at) as dateReport,damage' ) )->groupBy ( 'itemNo' )->get ();
		foreach ( $items as $item ) {
			foreach ( $borrows as $borrow ) {
				if ($item->itemNo == $borrow->itemNo) {
					$item->dateBorrowed = $borrow->dateBorrowed;
					unset ( $borrow );
					break;
				}
			}
			foreach ( $returns as $return ) {
				if ($item->itemNo == $return->itemNo) {
					$item->dateReturned = $return->dateReturned;
					unset ( $return );
					break;
				}
			}
			foreach ( $issues as $issue ) {
				if ($item->itemNo == $issue->itemNo) {
					$item->lastIssue = $issue->damage . ", " . $issue->dateReport;
					unset ( $issue );
					break;
				}
			}
			foreach ( $brokens as $broken ) {
				if ($item->itemNo == $broken->itemNo) {
					$item->lastBroken = $broken->damage . ", " . $broken->dateReport;
					unset ( $broken );
					break;
				}
			}
		}
		return view ( "inventory.detailed", [ 
				'items' => $items 
		] );
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
		return view ( "inventory.addItem" );
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
			if($request['photo'] != null){
			$image = $request->file ( 'photo' );
			$imgvalidatoR = Validator::make ( $image, [ 
					'photo' => 'image|max:10485760' 
			] );
			foreach ( $image as $file ) {
				$ext = $file->guessClientExtension(); 
				$imageName = $request ['unique_id'] ."_".uniqid().".".$ext;
				
				$file->move ( public_path ( '/inventory/' ), $imageName );
				$filecount ++;
				$attachmentpath = $attachmentpath . "/inventory/" . $imageName . ",";
			}
			}
			
			$itemPrefix = explode(" ",$request ['company']);
			$itemNo = "";
			foreach ($itemPrefix as $prefix){
				$itemNo = $itemNo . substr($prefix,0,1) ; 
			};
			$itemNo = $itemNo . (Item::where('company',$request ['company'])->count() + 1);
			$time = Carbon::parse ( Carbon::now () );
			$date = Carbon::parse ( $request ['dateArrived'] );
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
					'created_at' => Carbon::create ( $date->year, $date->month, $date->day, $time->hour, $time->minute, $time->second ),
					'itemStatus' => "Available" 
			] );
			
			return response ()->json ( [ 
					'success' => true 
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
	
	
	// Borrow form advanced search
	public function borrowSearch(Request $request) {
		
		$borrows = Borrow::leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model
				from items) items' ), function ($join) {
			$join->on ( 'borrow_logs.itemNo','=','items.itemNo' );
			
		} )->leftJoin ( DB::raw ( '(select agent_id, first_name, last_name 
				from admin_profiles) admin_profiles' ), 'borrow_logs.borrowee', '=', 'admin_profiles.agent_id' )
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
		
		$names = DB::table ( 'admin_profiles' )->select ( 'agent_id as id', 'first_name', 'last_name' )->get ();
		$clients = DB::table ( 'client_profiles' )->select ( 'client_id as id', 'first_name', 'last_name' )->get ();
		
		foreach ( $clients as $client ) {
			array_push ( $names, $client );
		}
		$borrows = $borrows->get ();
		foreach ( $borrows as $borrowedItem ) {
			foreach ( $names as $nm ) {
				if ($nm->id == $borrowedItem->borrower) {
					$borrowedItem->borrower = $nm->first_name . ' ' . $nm->last_name;
				}
			}
		}
		
		return response ()->json ( [ 
				'success' => true,
				'response' => $borrows 
		]
		 );
	}
	// Return Form advanced search
	public function returnSearch(Request $request) {
							
		$returnedItems = ReturnItem::leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model
				from items) items' ), function ($join) {
							$join->on ( 'return_logs.itemNo','=','items.itemNo' );
								
						} )->leftJoin ( DB::raw ( '(select agent_id, first_name, last_name
				from admin_profiles) admin_profiles' ), 'return_logs.receiver', '=', 'admin_profiles.agent_id' )
						->orderBy ( 'created_at', 'desc' );
		
		$returnedItems = $returnedItems->newQuery ();
		
		if ($request ['itemNo'] != null) {
			$returnedItems->where ( 'items.itemNo', $request ['itemNo'] );
		}
		if ($request ['unique_id'] != null) {
			$returnedItems->where ( 'items.unique_addid', $request ['unique_id'] );
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
		
		$names = DB::table ( 'admin_profiles' )->select ( 'agent_id as id', 'first_name', 'last_name' )->get ();
		$clients = DB::table ( 'client_profiles' )->select ( 'client_id as id', 'first_name', 'last_name' )->get ();
		
		foreach ( $clients as $client ) {
			array_push ( $names, $client );
		}
		$returnedItems = $returnedItems->get ();
		foreach ( $returnedItems as $returnedItem ) {
			foreach ( $names as $nm ) {
				if ($nm->id == $returnedItem->borrower) {
					$returnedItem->borrower = $nm->first_name . ' ' . $nm->last_name;
				}
			}
		}
		
		return response ()->json ( [ 
				'success' => true,
				'response' => $returnedItems 
		]
		 );
	}
	
	// Issue Form
	public function showIssues() {
		$issueItems = Item::where ( 'itemStatus', 'With Issue' )->leftJoin ( 'issue_logs', function ($join) {
			$join->on ( 'items.itemNo', '=', 'issue_logs.itemNo' );
			$join->on ( 'items.updated_at', '=', 'issue_logs.created_at' );
		} )->leftJoin ( 'admin_profiles', 'issue_logs.reported_by', '=', 'admin_profiles.agent_id' )->orderby ( 'issue_logs.created_at', 'desc' )->get ();
		
		$agents = DB::table ( 'admin_profiles' )->get ();
		$itemNumbers = Item::select('itemNo')->get();
		return view ( "inventory.issues", [ 
				'issueItems' => $issueItems,
				'agents' => $agents,
				'itemNumbers' => $itemNumbers
		] );
	}
	public function issueItem(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'itemNo' => 'required|exists:items,itemNo,itemStatus,!"With Issue"',
				'damage' => 'required|max:255',
				'issue' => 'required|min:15|max:10000',
				'dateReported' => 'required|date' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			),400 );
		} else {
			$updatetime = Carbon::now ();
			$time = Carbon::parse ( Carbon::now () );
			$date = Carbon::parse ( $request ['dateBroken'] );
			
			
			$issueItem = new Issue ();
			
			$issueItem->itemNo = $request ['itemNo'];
			$issueItem->damage = $request ['damage'];
			$issueItem->issue = $request ['issue'];
			$issueItem->reported_by = Auth::guard ( 'inventory' )->user ()->id;
			$issueItem->created_at = Carbon::create ( $date->year, $date->month, $date->day, $time->hour, $time->minute, $time->second );
			$issueItem->updated_at = $updatetime;
			$issueItem->save ();
			
			$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [
					'itemStatus' => 'With Issue',
					'updated_at' => $updatetime
			] );
			
			$result = Issue::where ( 'issue_logs.itemNo', $request ['itemNo'] )
			->leftJoin ( DB::raw ( '(SELECT unique_id,itemNo,itemType,brand,model from items) items' ), function ($join) {
				$join->on ( 'issue_logs.itemNo', '=', 'items.itemNo' );
			} )->leftJoin ( DB::raw ( '(SELECT agent_id,first_name, last_name from admin_profiles) assignedSupport' ), function ($join) {
				$join->on ( 'issue_logs.reported_by', '=', 'assignedSupport.agent_id' );
			} )->orderby ( 'issue_logs.created_at', 'desc' )->first ();
			
			return response ()->json ( [ 
					'success' => true,
					'response' => $result 
			]
			 );
		}
		;
	}
	public function issueInfo(Request $request) {
		$itemInfo = Item::where ( 'unique_id', $request ['item'] )->first ();
		$issueInfo = Issue::where ( 'unique_id', $request ['item'] )->orderby ( 'created_at', 'desc' )->first ();
		if ($issueInfo == null) {
			return response ()->json ( [ 
					'success' => false 
			] );
		} else {
			
			$name = DB::table ( 'admin_profiles' )->where ( 'agent_id', $issueInfo ['reported_by'] )->first ();
			$issueInfo->itemStatus = $itemInfo ['itemStatus'];
			$issueInfo->itemNo = $itemInfo ['itemNo'];
			if ($name != null) {
				$issueInfo ['reported_by'] = $name->first_name . ' ' . $name->last_name;
			}
			return response ()->json ( [ 
					'success' => true,
					'info' => $issueInfo 
			] );
		}
	}
	public function issueSearch(Request $request) {
		
						
		$issueItems = Issue::leftJoin ( DB::raw ( '(select unique_id, itemNo, itemType, brand, model
				from items) items' ), function ($join) {
							$join->on ( 'issue_logs.itemNo','=','items.itemNo' );
		
						} )->leftJoin ( DB::raw ( '(select agent_id, first_name, last_name
				from admin_profiles) admin_profiles' ), 'issue_logs.reported_by', '=', 'admin_profiles.agent_id' )
						->orderBy ( 'created_at', 'desc' );
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
		
		return response ()->json ( [ 
				'success' => true,
				'response' => $issueItems->get () 
		]
		 );
	}
	public function repairItem(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'itemNo' => 'required|exists:items,itemNo,itemStatus,With Issue',
				'dateRepair' => 'required|date' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$time = Carbon::parse ( Carbon::now () );
			$date = Carbon::parse ( $request ['dateRepair'] );
			
			
			
			$repairItem = Issue::where ( 'itemNo', $request ['itemNo'] )->orderby ( 'created_at', 'desc' )->update ( [ 
					'updated_at' => Carbon::create ( $date->year, $date->month, $date->day, $time->hour, $time->minute, $time->second ) 
			] );
			$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [ 
					'itemStatus' => 'Available' 
			] );
			
			$result = Issue::where ( 'issue_logs.itemNo', $request ['itemNo'] )
			->leftJoin ( DB::raw ( '(SELECT unique_id,itemNo,itemType,model,brand from items) items' ), function ($join) {
				$join->on ( 'issue_logs.itemNo', '=', 'items.itemNo' );
			} )->leftJoin ( DB::raw ( '(SELECT agent_id,first_name, last_name from admin_profiles) assignedSupport' ), function ($join) {
				$join->on ( 'issue_logs.reported_by', '=', 'assignedSupport.agent_id' );
			} )->orderby ( 'issue_logs.created_at', 'desc' )->first ();
						
			return response ()->json ( [ 
					'success' => true,
					'response' => $result 
			]
			 );
		}
		;
	}
	// Broken Form
	public function showBroken() {
		$brokenItems = Item::select('items.unique_id','items.itemNo','itemType','brand','model','items.updated_at',
				'broken_logs.damage','broken_logs.brokenStatus','broken_logs.reported_by','broken_logs.created_at',
				'broken_logs.brokenSummary','admin_profiles.agent_id','admin_profiles.first_name','admin_profiles.last_name')
		->where ( 'itemStatus', 'Broken' )->leftJoin ( 'broken_logs', function ($join) {
			$join->on ( 'items.itemNo', '=', 'broken_logs.itemNo' );
			$join->on ( 'items.updated_at', '<=', 'broken_logs.updated_at' );
		} )->leftJoin ( 'admin_profiles', 'broken_logs.reported_by', '=', 'admin_profiles.agent_id' )->get ();
		
		$agents = DB::table ( 'admin_profiles' )->get ();
		$itemNumbers = Item::select ( 'itemNo' )->get ();
		return view ( "inventory.broken", [ 
				'brokenItems' => $brokenItems,
				'agents' => $agents,
				'itemNumbers' => $itemNumbers 
		] );
	}
	public function brokenItem(Request $request) {
		$validator = Validator::make ( $request->all (), [ 				
				'itemNo' => 'required|exists:items,itemNo,itemStatus,!"Broken"',
				'damage' => 'required|max:255',
				'status' => 'required|max:255',
				'summary' => 'required|min:15|max:10000',
				'dateBroken' => 'required|date' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$updatetime = Carbon::now ();
			$time = Carbon::parse ( Carbon::now () );
			$date = Carbon::parse ( $request ['dateBroken'] );
			
			
			$brokenItem = new Broken ();
			
			$brokenItem->itemNo = $request ['itemNo'];
			$brokenItem->damage = $request ['damage'];
			$brokenItem->brokenSummary = $request ['summary'];
			$brokenItem->brokenStatus = $request ['status'];
			$brokenItem->reported_by = Auth::guard ( 'inventory' )->user ()->id;
			$brokenItem->created_at = Carbon::create ( $date->year, $date->month, $date->day, $time->hour, $time->minute, $time->second );
			$brokenItem->updated_at = $updatetime;
			$brokenItem->save ();
			
			$itemStatus = Item::where ( 'itemNo', $request ['itemNo'] )->update ( [ 
					'itemStatus' => 'Broken',
					'updated_at' => $updatetime 
			] );
			
			$result = Broken::where ( 'broken_logs.itemNo', $request ['itemNo'] )
			->leftJoin ( DB::raw ( '(SELECT unique_id,itemNo,itemType,brand,model from items) items' ), function ($join) {
				$join->on ( 'broken_logs.itemNo', '=', 'items.itemNo' );
			} )->leftJoin ( DB::raw ( '(SELECT agent_id,first_name, last_name from admin_profiles) assignedSupport' ), function ($join) {
				$join->on ( 'broken_logs.reported_by', '=', 'assignedSupport.agent_id' );
			} )->orderby ( 'broken_logs.created_at', 'desc' )->first ();
			
			return response ()->json ( [ 
					'success' => true,
					'response' => $result 
			]
			 );
		}
		;
	}
	public function brokenSearch(request $request) {
		
		$brokenItems = Item::where ( 'itemStatus', 'Broken' )->leftJoin ( 'broken_logs', function ($join) {
			$join->on ( 'items.itemNo', '=', 'broken_logs.itemNo' );
			$join->on ( 'items.updated_at', '<=', 'broken_logs.updated_at' );
		} )->leftJoin ( 'admin_profiles', 'broken_logs.reported_by', '=', 'admin_profiles.agent_id' );
		
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
		
		return response ()->json ( [ 
				'success' => true,
				'response' => $brokenItems->get () 
		]
		 );
	}
	
	public function updateBroken(Request $request) {
		$items = $request ['items'];
		
		$broken_logs = Broken::select ( DB::raw ( 'max(created_at) as created_at, unique_id, brokenStatus' ) )
		->groupBy ( 'itemNo' )
		->join ( DB::raw ( '(Select unique_id,itemNo,updated_at as dateUpdate from items) items' ), function ($join) {
			$join->on ( 'broken_logs.updated_at', '>=', 'items.dateUpdate' );
			$join->on ( 'broken_logs.itemNo', '=', 'items.itemNo' );
		} );
		
		if ($request ['mark'] == "Repaired") {
			$changeItemStatus;
			foreach ( $items as $key => $value ) {
				if ($key == 0) {
					$broken_logs->where ( 'broken_logs.itemNo', $value );
					$changeItemStatus = Item::where ( 'itemNo', $value );
				} else {
					$broken_logs->orWhere ( 'broken_logs.itemNo', $value );
					$changeItemStatus->orWhere ( 'itemNo', $value );
				}
			}
			$broken_logs = $broken_logs->update ( [ 
					'brokenStatus' => $request ['mark'] 
			] );
			$changeItemStatus->update ( [ 
					'itemStatus' => "Available" 
			] );
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
		
		$result = Broken::select ( DB::raw ( 'max(created_at) as created_at, itemNo, brokenStatus, max(updated_at) as updated_at' ) )
		->groupBy ( 'itemNo' )->get ();
		return response ()->json ( [ 
				'success' => true,
				'response' => $result 
		]
		 );
	}
	// end Broken Form
	public function showMaintenance() {
		$schedules = mSchedule::leftJoin ( DB::raw ( '(select id, area from maintenance_areas) area' ), function ($join) {
			$join->on ( 'maintenance_schedules.area', '=', 'area.id' );
		} )->get ();
		
		$area = mArea::all ();
		$activity = mActivity::all ();
		$x = [ ];
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
			array_push ( $x, [ 
					'title' => $schedule ['title'],
					'start' => $startdate [0] . 'T' . $startdate [1] . ".196Z",
					'end' => $enddate [0] . 'T' . $enddate [1] . ".196Z",
					'description' => $schedule['area'] . "\n" . $schedText 
			] );
		}
		;
		
		return view ( "inventory.maintenance", [ 
				'areas' => $area,
				'activities' => $activity,
				'schedules' => $x 
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
			)
			, 400 );
		}
		$activities = "";
		foreach ( $request ['activity'] as $activity ) {
			$activities = $activities . $activity . ",";
		}
		;
		
		$schedule = new mSchedule ();
		$schedule->title = $request ['title'];
		$schedule->activities = $activities;
		$schedule->area = $request ['area'];
		$schedule->start_date = $request ['startScheduleDate'] . ' ' . $request ['startScheduleTime'];
		$schedule->end_date = $request ['endScheduleDate'] . ' ' . $request ['endScheduleTime'];
		$schedule->save ();
		
		return response ()->json ( [ 
				'success' => true,
				'response' => $request ['activity'] 
		]
		 );
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
			)
			, 400 );
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
	public function viewItemDetails($id) {
		$item = Item::where ( 'itemNo', $id )->first ();
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
				'brokens' => $broken 
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
			)
			, 400 );
		}
		
		$item = Item::where('unique_id',$request['id'])->first();
		$photoNo = explode(",", $item['photo']);
		$photoNo = count($photoNo);
		$ext = explode(".",$request['name']);
		
		$imageName = $request ['id']."_".uniqid().".".$ext[1];
		$dataUrl = explode ( ',', $request ['photo'] );
		$photo = base64_decode ( $dataUrl [1] );
		
		$filepath = public_path () . "/inventory/" . $imageName;
		
		file_put_contents ( $filepath, $photo );
		
		$itemUpdate = Item::where('unique_id',$request['id'])
		->update(['photo' => $item['photo'] . "/inventory/" . $imageName.","]);
		return response ()->json ( array (
				'success' => true,
				'response' => "/inventory/" . $imageName
		) );
	}
	
	public function deleteItemPhoto(Request $request){
		$validator = Validator::make ( $request->all (), [
				'id' => 'required|exists:items,unique_id'
		] );
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray ()
			)
					, 400 );
		}
		
		$item = Item::where('unique_id',$request['id'])->first();
		$itemPhoto = str_replace($request['name'].',', '', $item['photo']);
		unlink(public_path($request['name']));
		
		$itemUpdate = Item::where('unique_id',$request['id'])->update(['photo' => $itemPhoto]);
		return response ()->json ( array (
				'success' => true
		) );
	}
	
	// edit Account
	public function showEditAccount(){
		return view('inventory.editAccount');
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
		$admin = AProfile::where ( 'agent_id', $request ['id'] )->first();
		
		if($admin != null){
		$dataUrl = explode ( ',', $request ['photo'] );
		$photo = base64_decode ( $dataUrl [1] );
		$ext = explode("/",$dataUrl[0]);
		$ext = explode(";",$ext[1]);
		$filepath = public_path () . "/img/agents/" . Auth::guard ( 'inventory' )->user ()->id .".". $ext[0];
	
		file_put_contents ( $filepath, $photo );
		$admin = AProfile::where ( 'agent_id', $request ['id'] )->update(['photo' => "/img/agents/" . Auth::guard ( 'inventory' )->user ()->id .".". $ext[0] ]);
		return response ()->json ( array (
				'success' => true
		) );
		}
		
		return response ()->json ( array (
				'success' => false,
				'error' => "User does not exists"
		) );
	}
	public function showAgentProfile($id){
		$agent = AProfile::where ( 'agent_id', $id )->first();
		if($agent == null){
			return abort(404);
		}
		$borrow_logs = Borrow::where('borrowee',$id)->get();
		$return_logs = ReturnItem::where('receiver',$id)->get();
		$issue_logs = Issue::where('reported_by',$id)->get();
		$broken_logs = Broken::where('reported_by',$id)->get();
		$xAxis = ["x"];
		$borrow = ['Borrow'];
		$return = ['Return'];
		$issue = ['Issue'];
		$broken = ['broken'];
		for($i = 9; $i >= 0; $i --) {
			$date = Carbon::today ();
			$dateStart = Carbon::today ()->subMonths ( $i )->startOfMonth ();
			$dateEnd = Carbon::today ()->subMonths ( $i )->endOfMonth ();
			
			$borrowCount = Borrow::where('borrowee',$agent['agent_id'])->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			$returnCount = ReturnItem::where('receiver',$agent['agent_id'])->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			$issueCount = Issue::where('reported_by',$agent['agent_id'])->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			$brokenCount = Broken::where('reported_by',$agent['agent_id'])->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			array_push($borrow,$borrowCount);
			array_push($return,$returnCount);
			array_push($issue,$issueCount);
			array_push($broken,$brokenCount);
			$monthName = $dateStart->format ( 'M Y' );
			array_push ( $xAxis, $monthName );
		};
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
		return view('inventory.agentProfile',['agent' => $agent,'stats' => [$borrow,$return,$issue,$broken,$xAxis],
				'borrows' => $borrow_logs,'returns' => $return_logs,'issues' => $issue_logs,'brokens'=>$broken_logs
		]);
	}
}
