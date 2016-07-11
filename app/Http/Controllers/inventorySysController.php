<?php
namespace App\Http\Controllers;
use App\BorrowForm as BorrowForm;
use App\IsUser as IsUser;
use App\Items as Items;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use DateTime;
use Carbon\Carbon;
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
     
			
        $users = IsUser::all();

		return view("inventory.manage_accounts") -> with('users', $users);

	}
	
	
	
	public function postManageAccounts(Request $request) {
		$updates = $request->input("updates");
		
		$users = [];
		foreach($updates as $update){
			IsUser::where("id", $update["id"])->update(["user_type"=>$update["user_type"]]);
			$user =  IsUser::where("id", $update["id"])->first();
			
		}
		$all_users = IsUser::all();
		foreach($all_users as $user){
			$users[] = array(
				"id"=>$user->id,
				"first_name"=>$user->first_name,
				"last_name"=>$user->last_name,
				"user_type"=>$user->user_type,
				"email"=>$user->email,
				"confirmed"=>$user->confirmed===1
			);
		}
		return response()->json(['success' => true, "users"=>$users]);	
		
	}
		
		
		

	public function showBorrow() {
  $borrowTable = DB::table('borrow_logs')
   ->leftJoin('items_tbl','borrow_logs.unique_identifier', '=' ,'items_tbl.unique_identifier')
   ->get();
		return view("inventory.borrow") -> with('borrow', $borrowTable);
	}
	
/* InputFUnctions here */	

	public function srchUnique(Request $request)
	{
		$get_data = $request->all();
		
		$itemsTable = Items::where('unique_identifier',$get_data['uniqueSrch'])->first();
		
	 return response(['itemsTbl' => $itemsTable]);
			
	}
	
	public function srchItemNo(Request $request)
	{
		$get_data = $request->all();
		
		$itemsTable = Items::where('item_no',$get_data['itemNoSrch'])->first();
		
	return response(['itemsTbl' => $itemsTable]);
	
	}
	
		public function postBorrow(Request $request) {
		$validator= Validator::make($request->all(),[
			'unique'  => 'required|min:3|max:255',    
            'lent'  => 'required|min:3|max:255',    
            'borrower' =>'required|min:3|max:255',
            'dateBorrowed'  => 'required|min:3|max:255', 
		]);
	
	
		if($validator->fails()){
			return response()->json(array('success'=> false,'errors' => $validator->getMessageBag()->toArray()));
		}
		else {
			//inputborrow
			$data = BorrowForm::create([
			
			'unique_identifier' => $request['unique'],
			'support_on_duty' => $request['lent'],
			'borrower' => $request['borrower'],
			'date_borrowed' => $request['dateBorrowed'],
			
			]);
			//update detailed
	
		$updateDetailed = Items::where('unique_identifier',$request['unique'])
		->update(array('status' => "Not Available"));
		
		
		
			
			}
				
		}


	public function showReturn()
	 {
	 	  $borrow_tbl = DB::table('items_tbl')
     ->leftJoin('borrow_logs','items_tbl.unique_identifier', '=' ,'borrow_logs.unique_identifier')
        ->select(array('borrow_logs.*','items_tbl.*', DB::raw('MAX(date_borrowed) as date_borrowed')))
     ->where('borrow_logs.date_borrowed' , '!=' , '')
     ->where('items_tbl.status' , '=' , 'Not Available')
     ->groupBy('borrow_logs.unique_identifier')
     ->orderBy('borrow_logs.date_borrowed','DESC')
     ->get();
			$now = new DateTime();
			
		$return_tbl = DB::table('returned_logs')
		->whereDate('date_returned', '>', date('Y-m-d',strtotime('-30 days')))
		->get();	
	 	return view("inventory.return",['borrow_tbl' => $borrow_tbl, 'return_tbl' => $return_tbl]);
	
			
	 }
	 
	 
	public function postReturn(Request $request)
	 {
	 	
		
			$validator = Validator::make($request->all(),[
        	'dateReturned' => 'required|date',
        	'receiver' => 'string',
        
        ]);
		

				   
        if ($validator->fails()) {
         	 return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray())); 
   
         
        }
		else{
		DB::table('returned_logs')->insert([
			'unique_identifier' => $request['uniqueIdentifier'],
			'date_returned' => $request['dateReturned'],
			'receiver' => $request['receiver'],
		]);
		
		DB::table('items_tbl')->where('unique_identifier', $request['uniqueIdentifier'])->update(['status' => 'Available']);
		}		
		
		

	 }
	 
	  public function showDetailed()
	 {
	 	
				$brokenDetails = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->where('broken_items.date_being_broken','!=','')
						->distinct()->get();
			
		return view("inventory.detailed") 
		-> with('brokenDetails',$brokenDetails );
	 }
	 
	  public function showIssues()
	 {
	 	return view("inventory.issues");	
	 }
	 
	  public function showBroken()
	 {
	  $brokenLogs = DB::table('broken_items')
   ->leftJoin('items_tbl','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
   ->where('items_tbl.status','On supplier')
   ->orWhere('items_tbl.status','Broken')
   ->get();
   
   
     $brokenLatest = DB::table('broken_items')
     ->leftJoin('items_tbl','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
     ->select(array('broken_items.*','items_tbl.*', DB::raw('MAX(date_being_broken) as date_being_broken')))
     ->where('items_tbl.status','On supplier')
   	 ->orWhere('items_tbl.status','Broken')
     ->groupBy('broken_items.unique_identifier')
     ->orderBy('broken_items.date_being_broken','DESC')
     ->get();
   
		return view("inventory.broken")
		->with('brokenLogs', $brokenLogs)
		->with('brokenLatest',$brokenLatest);
		
		
			 
	 }
	 
	 public function showSparkline(Request $request){
	 	
			
			
	 	$months = $request->get('months');
		$years = $request->get('years');
		$now = Carbon::now();
		$nowMonth = $now->month;
		$nowYear = $now->year;
		
		if($years == 'Current year'){
			
		$laptopCount = DB::table('items_tbl')
							->select('unique_identifier')
							->where('items_tbl.item_type','laptop')
							->whereYear('items_tbl.date_arrived','<',date($nowYear + 1))
							->distinct()
							->get();
		}
		else{
			$laptopCount = DB::table('items_tbl')
							->select('unique_identifier')
							->where('items_tbl.item_type','laptop')
							->whereYear('items_tbl.date_arrived','<',date($years + 1))
							->distinct()
							->get();
						
		}
		if ($months == 'All months'){
			if($years == 'Current year'){
					$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			
					$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();
					
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
		
			}
			else{
					$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()
						->get();
													
					
					
					$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()
						->get();
									
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
						
			}
		}
		
	
		elseif ($months == 'January'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('01'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('01'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('01'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('01'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}
		elseif ($months == 'February'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('02'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('02'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('02'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('02'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}
		elseif ($months == 'March'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('03'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('03'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('03'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('03'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}
		elseif ($months == 'April'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('04'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('04'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('04'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('04'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}
			elseif ($months == 'May'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('05'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('05'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('05'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('05'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}
		elseif ($months == 'June'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('06'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('06'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('06'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('06'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}
		elseif ($months == 'July'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('07'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('07'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('07'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('07'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}elseif ($months == 'August'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('08'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('08'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('08'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('08'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}
		elseif ($months == 'September'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('09'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('09'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('09'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('09'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}
		elseif ($months == 'October'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('10'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('10'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('10'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('10'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}
elseif ($months == 'November'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('11'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('11'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('11'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('11'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}
		elseif ($months == 'December'){
			if($years =='Current year'){
				
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('12'))
						->whereYear('broken_items.date_being_broken','=',date($nowYear))
						->distinct()->get();
			   								
			   					
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('12'))
						->whereYear('issue_report_logs.date_reported','=',date($nowYear))
						->distinct()->get();							
				
					$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				
			}
			else{
				$brokenLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('broken_items.date_being_broken','!=','')
						->whereMonth('broken_items.date_being_broken', '=', date('12'))
						->whereYear('broken_items.date_being_broken','=',date($years))
						->distinct()->get();
				
				$issueLaptopCount = DB::table('items_tbl')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier', '=' ,'issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')
						->where('items_tbl.item_type','laptop')
						->where('issue_report_logs.date_reported','!=','')
						->whereMonth('issue_report_logs.date_reported', '=', date('12'))
						->whereYear('issue_report_logs.date_reported','=',date($years))
						->distinct()->get();							
				
				$brokenData = array_map(function($object){
   					 return (array) $object;
											}, $brokenLaptopCount);
					
					$issueData = array_map(function($object){
   					 return (array) $object;
											}, $issueLaptopCount);
					
										
					$workingLaptopCount = DB::table('items_tbl')
						->leftJoin('broken_items','items_tbl.unique_identifier','=','broken_items.unique_identifier')
						->leftJoin('issue_report_logs','items_tbl.unique_identifier','=','issue_report_logs.unique_identifier')
						->select('items_tbl.unique_identifier')			
				//		->whereYear('broken_items.date_being_broken','!=',date($years))
				//		->whereYear('issue_report_logs.date_reported','!=',date($years))
						->whereNotIn('items_tbl.unique_identifier',$brokenData)
						->whereNotIn('items_tbl.unique_identifier',$issueData)
						->distinct()
						->get();	
				}				
			
			
			
		}

		return response(['brokenLaptopCount' => count($brokenLaptopCount), 
		'workingLaptopCount' => count($workingLaptopCount),
		'issueLaptopCount' => count($issueLaptopCount),
		'laptopCount' => count($laptopCount)]);
	 }
	 
	 
	 
	  public function showSummaryMonYrs()
	 {
	
	 	return view("/inventory/summaryMonYrs");
			
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
		$itemsTable = Items::all();

		return view("inventory.item_input") -> with('items', $itemsTable);

	}

	public function showRegister() {

		return view("inventory.register_account");
	}

	public function showForgotpassLink() {
		return view('inventory.forgotpass_link');

	}
	//input items
	public function createItem(Request $request){
		$validator = Validator::make($request->all(),[
        	'station_no' => 'required|numeric',
        	'company'  => 'required|string',    
            'item' =>'required|string',  
            'model'  => 'required|string',    
            'brand' =>'required|alpha',          
        	'unique_identifier'  => 'required|unique:items_tbl,unique_identifier|string',    
            'item_no' =>'required|numeric|unique:items_tbl,item_no',
            'date_arrived'  => 'required|date',    
        
        ]);

        if ($validator->fails()) {
            return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray())); 
          
        }
		else {
			
			$items = Items::create([
            'station_no' => $request['station_no'],
            'company' => $request['company'],
            'item_type' => strtolower($request['item']),
            'model' => $request['model'],
            'brand' => $request['brand'],
            'unique_identifier' => $request['unique_identifier'],
            'item_no' => $request['item_no'],
            'morning_shift'=>$request['morning_shift'],
            'night_shift' => $request['night_shift'],
            'date_arrived' => $request['date_arrived'],
            'date_deployed' => $request['date_deployed'],
            'date_created' => date('Y-m-d H:i:s'),
        ]);
		
		if($request['date_deployed'] != "")
		{
				$updateDetailed = Items::where('unique_identifier',$request['unique_identifier'])
		->update(array('status' => "Not Available"));
		}
		
		

			}
	}

	public function postBroken(Request $request){
		$validator = Validator::make($request->all(),[
        	'dateBroken' => 'required|date',
        	'damage' => 'string',
        
        ]);
		

				   
        if ($validator->fails()) {
         	 return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray())); 
   
         
        }
		else{
		DB::table('broken_items')->insert([
			'unique_identifier' => $request['unique'],
			'support_on_duty' => $request['brokenOnDuty'],
			'damage' => $request['damage'],
			'date_being_broken' => $request['dateBroken'],
		]);
		
				if($request['statusBox'] == "On supplier")		{
		DB::table('items_tbl')->where('unique_identifier', $request['unique'])->update(['status' => 'On supplier']);
				}
				 if($request['statusBox'] == "Broken"){
					DB::table('items_tbl')->where('unique_identifier', $request['unique'])->update(['status' => 'Broken']);
				}
			 if($request['statusBox'] == "Availabe"){
					DB::table('items_tbl')->where('unique_identifier', $request['unique'])->update(['status' => 'Available']);
				
				}
			
			DB::table('items_tbl')->where('unique_identifier',$request['unique'])
			->update(['morning_shift' => '','night_shift' => '' , 'date_deployed' => '']);
					
			}		
		
			
			}
		
	
	public function updateBroken(Request $request) {
		$updates = $request->input("updates");
		
		$users = [];
		foreach($updates as $update){
				
			DB::table('items_tbl')
				->where("unique_identifier", $update["id"])
				->update(["status"=>$update["status"]]);
		
			 $brokenTable = DB::table('broken_items')
   					->leftJoin('items_tbl','broken_items.unique_identifier', '=' ,'items_tbl.unique_identifier')
					->where("items_tbl.unique_identifier", $update["id"])->first();
		
	
				}

		
	}
	
	
}
