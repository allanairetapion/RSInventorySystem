<?php

namespace App\Http\Controllers;
use DB;
use App\Tickets;
use App\Admin;
use App\TicketTopics;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TicketsAdmin extends Controller
{
    public function __construct(){
        $this->middleware('admin', ['except' => ['logout', 'getLogout']]);
   }
	public function index(){
		$today = Carbon::today();
		
		$restriction = DB::table('ticket_restrictions')->get();
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')->where('user_type','agent')->get();
	
		$topic_id = array();
		$trendingTopics = array();
		$trendingDescription = array();
		$topics = DB::table('ticket_topics')->select('topic_id')->get();
		$topicsDesc = DB::table('ticket_topics')->select('description')->get();
		$sample = [];
		
		foreach($topicsDesc as $desc){
			foreach($desc as $key => $value){
				$trendingDescription[] = $value;
			}					
		}
		
		foreach ($topics as $topic ) {
			foreach($topic as $key => $value){
				$topic_id[] = $value;
			}										
		}
		
		for ($i=0; $i < count($topic_id) ; $i++) { 
			 $a = DB::table('tickets')->where('topic_id',$topic_id[$i])->where('created_at','>=',$today->startOfWeek())->count();			 
			 $trendingTopics[] = $a;
		 	}
		 
		for ($i=0; $i < count($topic_id) ; $i++) { 
				$sample[$i]['label'] = $trendingDescription[$i];
				$sample[$i]['data'] = $trendingTopics[$i];  
			}
			
			if(Auth::guard('admin')){
			$newtickets = DB::table('tickets')->where('ticket_status','Open')->where('created_at','>=',$today)->get();
			$pendingtickets = DB::table('tickets')->where('ticket_status','Pending')->whereBetween('created_at',array($today,Carbon::tomorrow()))->get();
			$overdueTickets = DB::table('tickets')->where('ticket_status','!=','Closed')->whereNotBetween('created_at',array($today,Carbon::tomorrow()))->get();
			$closedtickets = DB::table('tickets')->where('ticket_status','Closed')->whereBetween('created_at',array($today,Carbon::tomorrow()))->get();
			$ticketsNoSupport = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')->where('assigned_support','=',"")->get();
        	
        	return view('tickets.admin.dashboard',['newTickets' => $newtickets,
        	'pendingTickets' => $pendingtickets,
        	'overdueTickets' => $overdueTickets,
        	'closedTickets' => $closedtickets,
        	'noSupport' => $ticketsNoSupport,
        	'restrictions' => $restriction,
        	'trendingTopics' => $trendingTopics,
        	'trendingDescription' => $trendingDescription,
        	'sample' => $sample,
        	'agent' =>$agents]);
		}
		else {
			
			return redirect('/admin/login');
		}
    }
	
	public function createAgent(){
		$restriction = DB::table('ticket_restrictions')->get();
		return view('tickets.admin.createAgent',['restrictions' => $restriction]);
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
	
	public function checkEmail(Request $request){
		$validator = Validator::make($request->all(),[
        	'firstname' => 'required|min:3|max:255',
            'lastname' => 'required|min:2|max:255',
            'email' => 'required|email|max:255|unique:admin,email',
            'user_type' => 'required',            
        ]);

        if ($validator->fails()) {
            return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray())); 
          
        }
		else {
			return response()->json(['response' => '']); 
		}
	}
	
	public function showCreateTicket(){
		$restriction = DB::table('ticket_restrictions')->get();
		$users = DB::table('ticket_topics')->where('status',1)->get();
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')->where('user_type','agent')->get();
		return view('tickets.admin.createTicket', ['topics' => $users , 'agent' => $agents,'restrictions' => $restriction]);
	}
	
	public function createTicket(Request $request){
		$validator = Validator::make($request->all(),[
			'assigned_support' => 'required',
        	'topic' => 'required',
        	'subject'  => 'required|min:6|max:255',    
            'summary' =>'required|min:10|max:500',                    
        ]);

        if ($validator->fails()) {
            return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));           
        }
		else {
			$ida;
    	do{
			$ida =rand(0000, 9999);
		}
		while (DB::table('admin')->where('id',$ida)->first() != null);		
			$user = Tickets::create([
			'id' => $ida,
            'sender' => Auth::guard('admin')->user()->adminProfile ? Auth::guard('admin')->user()->adminProfile->first_name.' '.Auth::guard('admin')->user()->adminProfile->last_name : '',
            'sender_email' => Auth::guard('admin')->user()->email,
            'topic_id' => $request['topic'],
            'subject' => $request['subject'],
            'summary' => $request['summary'],
            'ticket_status' => 'Open',
            'assigned_support' => $request['assigned_support'],
            'priority' => 'High',
            'department' =>'Support',
        ]);			
			return response()->json(['response' => '']); 			
		}
	}
	
	public function showTopics(){
		$users = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();
        return view('tickets.admin.newTopics', ['topics' => $users,'restrictions' => $restriction]);
	}
	
	public function addTopic(Request $request){
		$validator = Validator::make($request->all(),[
        	'topic' => 'required|min:5|max:30',          
        ]);
		if ($validator->fails()) {
            return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));           
        }
		else {
			
			$user = TicketTopics::create([
            'description' =>$request['topic'],
            'status' => 1,
        ]);
			$users = DB::table('ticket_topics')->get();
			return view('tickets.admin.ajaxTopics', ['topics' => $users]); 						
		}
	}
 
	public function updateSelection(Request $request){
		$users = DB::table('ticket_topics')->update(['status'=> 0]);
		
		foreach ($request as $key => $topic ) {
			foreach ($topic as $key => $value) {
				$users = DB::table('ticket_topics')->where('topic_id',$key)->update(['status'=> 1]);
			}
			
		}
	}
		
	public function deleteTopic(Request $request){
		foreach ($request as $key => $topic ) {
			foreach ($topic as $key => $value) {
				$users = DB::table('ticket_topics')->where('topic_id',$value)->delete();																				
			}			
		}		
		$restriction = DB::table('ticket_topics')->get();
			return view('tickets.admin.ajaxTopics', ['topics' => $restriction]); 					
	}
	
	public function showClients(){
		$restriction = DB::table('ticket_restrictions')->get();
		$users = DB::table('clients')->get();
		return view('tickets.admin.showClients',['clients'=>$users,'restrictions' => $restriction]);
	}

	public function showAgents(){
		$restriction = DB::table('ticket_restrictions')->get();
		$users = DB::table('admin')->get();
		return view('tickets.admin.showAgents',['agents'=>$users,'restrictions' => $restriction]);
	}
	
	public function showRestriction(){
		$users = DB::table('ticket_restrictions')->get();
		return view('tickets.admin.showRestrictions',['restrictions' => $users]);
	}
	
	public function showTicketStatus(){
		$restriction = DB::table('ticket_restrictions')->get();
		$users = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')->paginate(15);
		return view('tickets.admin.ticketReport',['tickets' => $users,'restrictions' => $restriction]);
	}

	public function printTicketClosed(){
		$users = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')->get();
		return view('tickets.admin.printTicketClosed',['tickets' => $users]);
	}

	public function updateRestriction(Request $request){
		$array = [];
		
		foreach ($request as $key => $restriction ) {			
			foreach ($restriction as $name => $value) {
				if($value == 'admin')																				
				$users = DB::table('ticket_restrictions')->where('id',$name)->update(['admin'=> 1,'agent'=> 0]);
				
				if($value == 'both')																				
				$users = DB::table('ticket_restrictions')->where('id',$name)->update(['admin'=> 1,'agent'=> 1]);				
				}		
		}
		
		return $array;
	}

	public function showTicketReply(){
		
		$restriction = DB::table('ticket_restrictions')->get();
		
		return view('tickets.admin.ticketReply',['restrictions' => $restriction]);
	}
	
	public function deleteTicket(Request $request){
		$users = DB::table('tickets')->where('id',$request['ticket_id'])->delete();	
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')->where('user_type','agent')->get();
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')->orderBy('created_at','desc')->get();
        	return view('tickets.admin.refreshTicket',['tickets' => $tickets,'agent' => $agents]);
	}
	
	
//{!!html_entity_decode($post->body)!!}
	
}
