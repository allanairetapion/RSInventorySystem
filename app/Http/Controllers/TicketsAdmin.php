<?php

namespace App\Http\Controllers;
use DB;
use App\Tickets as Ticket;
use app\Admin;
use App\TicketTopics;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use View;
use Mail;

class TicketsAdmin extends Controller
{
    public function __construct(){
        $this->middleware('admin', ['except' => ['logout', 'getLogout']]);
   }
	public function index(){
		$today = Carbon::today();
		
		$restriction = DB::table('ticket_restrictions')->get();
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->get();
	
		
			
		if(Auth::guard('admin')){
			if(Auth::guard('admin')->user()->user_type == 'admin'){
				
				
				$ticketsNoSupport = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
				->where('assigned_support','=',"")->get();
	        	
	        	return view('tickets.admin.dashboard',[	        	
	        	'noSupport' => $ticketsNoSupport,
	        	'restrictions' => $restriction,        	        	
	        	'agent' =>$agents]);
				
        	}else{
        		
        		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')->where('assigned_support',Auth::guard('admin')->user()->id)->where('ticket_status','!=','Closed')->get();
        		
        		return view('tickets.admin.dashboardAgent',['restrictions' => $restriction,'tickets' => $tickets]);
        	}
		}
		else {
			
			return redirect('/admin/login');
		}
    }
	
	public function createAgent(){
		$restriction = DB::table('ticket_restrictions')->get();
		return view('tickets.admin.createAgent',['restrictions' => $restriction]);
	}
	
	public function checkPassword(Request $request){
		if (Auth::guard('admin')->attempt(['email' => Auth::guard('admin')->user()->email, 'password' => $request['password']]))
        {
            return "true";
        }
		else{
			return "false";
		}
	}	
	
	public function checkEmail(Request $request){
		$validator = Validator::make($request->all(),[
        	'firstname' => 'required|min:3|alpha|max:255',
            'lastname' => 'required|min:2|alpha|max:255',
            'email' => 'required|email|max:255|unique:admin|unique:clients',
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
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->get();
		
		return view('tickets.admin.createTicket', ['topics' => $users , 'agent' => $agents,'restrictions' => $restriction]);
	}
	
	public function createTicket(Request $request){
		if(Auth::guard('admin')->user()->user_type == 'admin'){
			$validator = Validator::make($request->all(),[	
				'assigned_support' => 'required',		
	        	'topic' => 'required',
	        	'subject'  => 'required|min:6|max:255',    
	            'summary' =>'required|min:10|max:500',                    
	        ]);
		}else{
			$validator = Validator::make($request->all(),[					
        	'topic' => 'required',
        	'subject'  => 'required|min:6|max:255',    
            'summary' =>'required|min:10|max:500',                    
        ]);
		}

        if ($validator->fails()) {
            return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));           
        }
		else {
			
			$ticket_status;
			if($request['assigned_support'] == null){
				$ticket_status = 'Pending';
				$request['assigned_support'] = 0;
			}else{
				$ticket_status = 'Open';
				$request['assigned_support'] = 0;
			}
			$ida;
		$tickets;
		
    	do{
			$ida = mt_rand(0, 99999999);					
		}
		while (Ticket::where('id')->exists());
			
			
			$user = Ticket::create([
			'id' => $ida,
            'sender' => Auth::guard('admin')->user()->adminProfile ? Auth::guard('admin')->user()
            ->adminProfile->first_name.' '.Auth::guard('admin')->user()->adminProfile->last_name : '',
            'sender_id' => Auth::guard('admin')->user()->id,
            'topic_id' => $request['topic'],
            'subject' => $request['subject'],
            'summary' => $request['summary'],
            'ticket_status' => $ticket_status,
            'assigned_support' => $request['assigned_support'],           
            'department' =>'Support',
        ]);			
			return response()->json(['response' => '']); 			
		}
	}
	
	public function showTopics(){
		$users = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();
		
		if((Auth::guard('admin')->user()->user_type == "agent" && $restriction[2]->agent == 1) || Auth::guard('admin')->user()->user_type == 'admin'){
			return view('tickets.admin.newTopics', ['topics' => $users,'restrictions' => $restriction]);
		}else{
			abort(403);
		}
        return view('tickets.admin.newTopics', ['topics' => $users,'restrictions' => $restriction]);
	}
	
	public function addTopic(Request $request){
		$validator = Validator::make($request->all(),[
        	'description' => 'required|min:5|max:30|unique:ticket_topics', 
        	'priority' => 'required',         
        ]);
		if ($validator->fails()) {
            return response()->json(array('success'=> false, 'errors' => $validator->getMessageBag()->toArray() ));           
        }
		else {
			
			$user = TicketTopics::create([			
            'description' =>$request['description'],
            'priority_level' =>$request['priority'],
            'status' => 1,
            'date_created' => Carbon::now(),
            'date_updated' => Carbon::now(),
        ]);
			$topics = DB::table('ticket_topics')->orderBy('topic_id','desc')->get();
			return response()->json(array('response'=> $topics)); 
		}
	}
 
	public function updateSelection(Request $request){
		$users = DB::table('ticket_topics')->update(['status'=> 0]);
		
		foreach ($request['topics'] as $key => $topic ) {
		
				$users = DB::table('ticket_topics')->where('topic_id',$topic)->update(['status'=> 1,'date_updated' => Carbon::now()]);
			
		}
	}
		
	public function deleteTopic(Request $request){
		
			
				$deleteTopic = DB::table('ticket_topics')->where('topic_id',$request['deleteTopic'])->delete();																				
				
		
				
		$topics = DB::table('ticket_topics')->get();
		return response()->json(array('response'=> $topics)); 		
	}
	
	public function deleteTicket(Request $request){
		if(Auth::guard('admin')->user()->user_type == 'admin'){
			foreach ($request['tickets'] as $key => $ticket ) {
				
					$users = DB::table('tickets')->where('id',$ticket)->delete();																				
					
			}
		}		
		return response()->json(array('success'=> "true"));		
	}
	
	public function showClients(){
		$restriction = DB::table('ticket_restrictions')->get();
		$users = DB::table('clients')->leftJoin('client_profiles','clients.id','=','client_profiles.client_id')->get();
		return view('tickets.admin.showClients',['clients'=>$users,'restrictions' => $restriction]);
	}

	public function showAgents(){
		$restriction = DB::table('ticket_restrictions')->get();
		$users = DB::table('admin')->leftJoin('admin_profiles','admin.id','=','admin_profiles.agent_id')->get();
		return view('tickets.admin.showAgents',['agents'=>$users,'restrictions' => $restriction]);
	}
	
	public function showRestriction(){
		$users = DB::table('ticket_restrictions')->get();
		return view('tickets.admin.showRestrictions',['restrictions' => $users]);
	}
	
	public function showTicketReport(){
		$topics = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();
		
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
		->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')
		->orderBy('created_at','desc')
		->paginate(15);
		
		$closed_by = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
		->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
		->orderBy('created_at','desc')
		->paginate(15);
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->orderBy('last_name')->get();
		
		return view('tickets.admin.ticketReport',['topics'=> $topics,'tickets' => $tickets,'closed_by' => $closed_by,'restrictions' => $restriction,
		'agent' => $agents]);
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
	public function showTicketDetails($id){
		$topics = DB::table('ticket_topics')->get();
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->where('user_type','agent')->orderBy('last_name')->get();
		
		$restriction = DB::table('ticket_restrictions')->get();
		
		$ticket = DB::table('tickets')
		->leftJoin('ticket_topics','tickets.topic_id',"=",'ticket_topics.topic_id')->where('id',$id)->first();
		
		if($ticket->ticket_status == 'Pending'){
			$tickets = DB::table('tickets')->where('id',$id)->update(['ticket_status' => 'Open']);
			$ticket = DB::table('tickets')
			->leftJoin('ticket_topics','tickets.topic_id',"=",'ticket_topics.topic_id')->where('id',$id)->first();
		}
		
		$sender_email = DB::table('admin')->select('email')->where('id',$ticket->sender_id)->first();
		
		if($sender_email == null){
			$sender_email = DB::table('clients')->select('email')->where('id',$ticket->sender_id)->first();			 			 
		}
		if($sender_email == null){
			$sender_email = ['email' => ''];
		}
		
		$assignedTo = DB::table('tickets')->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')
		->leftJoin('ticket_topics','tickets.topic_id',"=",'ticket_topics.topic_id')->where('id',$id)->first();
		
		$closedBy = DB::table('tickets')->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
		->leftJoin('ticket_topics','tickets.topic_id',"=",'ticket_topics.topic_id')->where('id',$id)->first();
		
		session([
		'email' => reset($sender_email),'subject' => $ticket->subject,
		'date_sent' => $ticket->created_at,'date_modified' => $ticket->updated_at, 'summary' => $ticket->summary,
		'topic_id' => $ticket->topic_id,'topic' => $ticket->description,
		'id' => $ticket->id,'assigned_support' => $assignedTo->first_name.' '.$assignedTo->last_name,
		'status' => $ticket->ticket_status,'priority' => $ticket->priority_level,
		'closed_by' => $closedBy->first_name.' '.$closedBy->last_name,
		'closing_report' => $closedBy->closing_report]);
		
		return view('tickets.admin.viewTicketDetails',['topics' => $topics,'restrictions' => $restriction,'agent' => $agents]);
	}
	public function showTicketReply($id = null){
		$restriction = DB::table('ticket_restrictions')->get();
		if($id == null){
			session(['email' => '']);
		return view('tickets.admin.ticketReply',['restrictions' => $restriction]);
		}
		$ticket = DB::table('tickets')->where('id',$id)->first();
		
		$sender_email = DB::table('admin')->select('email')->where('id',$ticket->sender_id)->first();
		
		if($sender_email == null){
			$sender_email = DB::table('clients')->select('email')->where('id',$ticket->sender_id)->first();			 			 
		}
		if($sender_email == null){
			$sender_email = "";
		}
		
		if((Auth::guard('admin')->user()->user_type == "agent" && $restriction[1]->agent == 1) || Auth::guard('admin')->user()->user_type == 'admin'){
			$restriction = DB::table('ticket_restrictions')->get();
			session(['email' => $sender_email->email,]);
			return view('tickets.admin.ticketReply',['restrictions' => $restriction]);
		}else{
			abort(403);
		}
        
	}
	
	
	
	public function advancedSearch(Request $request){
		$dateSent = $request['dateSent'];
		$dateClosed = $request['dateClosed'];
		$status = $request['statusSearch'];
		$topic = $request['topicSearch'];
		$agentSent = $request['agentSent'];
		$agentClosed = $request['agentClosed'];
		
		if($request['email'] != '' || $request['email'] != null){
			$sender = DB::table('clients')->where('email',$request['email'])->first();
			
			if($sender == null){
				$sender = DB::table('admin')->where('email',$request['email'])->first();
			}
			
			$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')
			->where('sender_id',$sender->id)->orderBy('created_at','desc');
			
			$closed_by = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
			->where('sender_id',$sender->id)->orderBy('created_at','desc');
		
		}else{
			$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')->orderBy('created_at','desc');
			
			$closed_by = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')->orderBy('created_at','desc');
		}

		
		
		if($dateSent != ""){
			$tickets->where('created_at','like',"%$dateSent%");
			$closed_by->where('created_at','like',"%$dateSent%");
		}
		if($dateClosed != ""){
			$tickets->where('closed_at','like',"%$dateClosed%");
			$closed_by->where('closed_at','like',"%$dateClosed%");
		}
		if($status!= ""){
			$tickets->where('ticket_status',$status);
			$closed_by->where('ticket_status',$status);
		}
		if($topic!= ""){
			$tickets->where('description',$topic);
			$closed_by->where('description',$topic);
		}
		if($agentSent != ""){
			$tickets->where('assigned_support',$agentSent);
			$closed_by->where('assigned_support',$agentSent);
		}
		if($agentClosed != ""){
			$tickets->where('closed_by',$agentClosed);
			$closed_by->where('closed_by',$agentClosed);
		}
		
		$data = $tickets->get();
		
		
		
		return response()->json(array('response'=> $data,'closed_by' => $closed_by->get()));        
	}
	
	public function showTickets(){
		
		
		
		$topics = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();	
		
		$closed_by = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
		->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
		->orderBy('created_at','desc')
		->paginate(15);
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->orderBy('last_name')->get();
		
			$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->orderBy('created_at','desc')->simplePaginate(15);
			
			return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets,'topics'=> $topics,'closed_by' => $closed_by,
		'agent' => $agents]);
		
		
	}
	public function showTicketsAssigned(){
							
		$topics = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();	
		
		$closed_by = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
			->orderBy('created_at','desc')
			->paginate(15);
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->orderBy('last_name')->get();
		
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->where('ticket_status','open')->where('assigned_support',Auth::guard('admin')->user()->id)
			->orderBy('created_at','desc')->simplePaginate(15);
			
		return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets,'topics'=> $topics,'closed_by' => $closed_by,
					'agent' => $agents]);
		
			
	}
	public function showTicketsOpen(){
		$topics = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();	
		
		$closed_by = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
			->orderBy('created_at','desc')
			->paginate(15);
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->orderBy('last_name')->get();
		
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->where('ticket_status','Open')->whereBetween('updated_at',[Carbon::yesterday(),Carbon::tomorrow()])->orderBy('created_at','desc')->simplePaginate(15);
			
		return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets,'topics'=> $topics,'closed_by' => $closed_by,
					'agent' => $agents]);
		
		
	}
	public function showTicketsPending(){
		$topics = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();	
		
		$closed_by = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
			->orderBy('created_at','desc')
			->paginate(15);
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->orderBy('last_name')->get();
		
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->where('ticket_status','Pending')->whereBetween('created_at',array(Carbon::yesterday(),Carbon::today()->endOfDay()))->orderBy('created_at','desc')->simplePaginate(15);

			
		return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets,'topics'=> $topics,'closed_by' => $closed_by,
					'agent' => $agents]);
		
		
	}
	public function showTicketsUnresolved(){
		$topics = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();	
		
		$closed_by = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
			->orderBy('created_at','desc')
			->paginate(15);
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->orderBy('last_name')->get();
		
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->where('ticket_status','!=','Closed')->whereNotBetween('updated_at',array(Carbon::yesterday(),Carbon::tomorrow()))->orderBy('created_at','desc')->simplePaginate(15);
			
			
		return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets,'topics'=> $topics,'closed_by' => $closed_by,
					'agent' => $agents]);
					
	
	}
	public function showTicketsClosed(){
		$topics = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();	
		
		$closed_by = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
			->orderBy('created_at','desc')
			->paginate(15);
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->orderBy('last_name')->get();
		
		if(Auth::guard('admin')->user()->user_type == 'admin'){
			$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->where('ticket_status','Closed')->orderBy('created_at','desc')->simplePaginate(15);
			
			
		}else{
			$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->where('ticket_status','Closed')->where('closed_by',Auth::guard('admin')->user()->id)->orderBy('created_at','desc')->simplePaginate(15);
			
		}		
			
		return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets,'topics'=> $topics,'closed_by' => $closed_by,
					'agent' => $agents]);
					
					
	
		
	}
	
	public function sendReply(Request $request){
		$validator = Validator::make($request->all(),['email' => 'required|email','message' => 'required|min:15']);
		
		if($validator->fails()){
			return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));    
		}else{
			
			Mail::raw(html_entity_decode($request['message']), function(\Illuminate\Mail\Message $message) use ($request) {
				$message->subject($request['subject']);
		    	$message->replyTo( Auth::guard('admin')->user()->email);
		    	$message->to($request['email']);
				});
				return response()->json(array('success'=> true));
											
		}
	}

	public function changeTicketStatus(Request $request){
				
		$changeStatus = DB::table('tickets')->where('id',$request['id'])->update([
		'topic_id'=> $request['topic'],
		'priority' => $request['priority'],
		'ticket_status' => $request['ticket_status'],
		'assigned_support' => $request['assignedTo'],
		'closing_report' => '']);		
		
		return response()->json(array('success'=> "true"));		
	}
	
	public function forwardTicket(Request $request){
		$forwardTo = DB::table('tickets')->where('id',$request['id'])
		->update(['assigned_support'=> $request['agent'],'ticket_status' => 'Open']);
		
		if($forwardTo != null){
		return response()->json(array('success'=> true));	
		}else{
			return response()->json(array('success'=> false));	
		}
	}
	public function deleteViewedTicket(Request $request){
		$deleteTicket = DB::table('tickets')->where('id',$request['id'])->delete();
	}
	public function topIssue(Request $request){
		$today = Carbon::today();
		
		$topic_id = array();
		$trendingTopics = array();
		$trendingDescription = array();
		$topics = DB::table('ticket_topics')->select('topic_id')->get();
		$topicsDesc = DB::table('ticket_topics')->select('description')->get();
		$topIssues = [];
		
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
			if($request['topIssue'] == "Week"){ 
			 $a = DB::table('tickets')->where('topic_id',$topic_id[$i])->where('created_at','>=',$today->startOfWeek())->count();	
			 }elseif($request['topIssue'] == "Month"){
			 $a = DB::table('tickets')->where('topic_id',$topic_id[$i])->where('created_at','>=',$today->startOfMonth())->count();	
			 }else{
			 $a = DB::table('tickets')->where('topic_id',$topic_id[$i])->where('created_at','>=',$today->startOfYear())->count();	
			 }
			 $trendingTopics[] = $a;
		 	}
		 
		for ($i=0; $i < count($topic_id) ; $i++) { 
				$topIssues[$trendingDescription[$i]] = $trendingTopics[$i];
				
			}
			
		return $topIssues;
	}

	public function ticketStat(Request $request){
		
		if(Auth::guard('admin')->user()->user_type == "admin"){
		$monthsDataSent = [];
		array_push($monthsDataSent,'Sent');
		$monthsDataClosed = [];
		array_push($monthsDataClosed,'Closed');
		$xAxis = [];
		array_push($xAxis,'x');
		
		for ($i=3; $i >= 0 ; $i--) {
			$date = Carbon::today();
			$dateStart = Carbon::today()->subMonths($i)->startOfMonth();
			$dateEnd = Carbon::today()->subMonths($i)->endOfMonth();						
						
			array_push($monthsDataSent,DB::table('tickets')->whereBetween('created_at',[$dateStart,$dateEnd])->count());
			array_push($monthsDataClosed,DB::table('tickets')->where('ticket_status','Closed')->whereBetween('created_at',[$dateStart,$dateEnd])->count());
			
			$dateNum = $dateStart->month;
			$monthName = $dateStart->format('F'); 
			array_push($xAxis,$monthName);
		}						
		return [$xAxis,$monthsDataSent,$monthsDataClosed];
		
		}else{
			$assignedTickets=[];
			array_push($assignedTickets,'Assigned');
			$closedTickets=[];
			array_push($closedTickets,'Closed');
			$xAxis = [];
			array_push($xAxis,'x');
			
			for($i = 13; $i >= 0; $i--){
				$date = Carbon::today()->subDays($i);
				$dateStart = Carbon::today()->subDays($i)->startOfDay();
				$dateEnd = Carbon::today()->subDays($i)->endOfDay();
				
				array_push($assignedTickets,DB::table('tickets')->where('assigned_support',Auth::guard('admin')->user()->id)
				->whereBetween('created_at',[$dateStart,$dateEnd])->count()); 
				
				array_push($closedTickets,DB::table('tickets')->where('closed_by',Auth::guard('admin')->user()->id)
				->whereBetween('closed_at',[$dateStart,$dateEnd])->count()); 
				
				$date = $date->format('d M');
				array_push($xAxis,$date);
			}
			
			return [$xAxis,$assignedTickets,$closedTickets];
		}
		
	}
	
	public function topSupport(Request $request){
		if($request['topSupport'] == 'Week'){
			$date = Carbon::today()->startOfWeek();
		}
		elseif ($request['topSupport'] == 'Month'){
			$date = Carbon::today()->startOfMonth();
		}
		else{
			$date = Carbon::today()->startOfYear();
		}
		$agents = DB::table('admin')->select('id')->where('user_type','agent')->get();
		$agentIDs = [];
		$topSupport = [];
		foreach ($agents as $key => $value) {
			foreach ($value as $a => $b) {
				$agentIDs[] = $b;
			}
		}
		
		foreach ($agentIDs as $key => $value) {
			$agentName = DB::table('admin_profiles')->where('agent_id',$value)->first();
			$total = DB::table('tickets')->where('ticket_status','Closed')->where('assigned_support',$value)->where('created_at','>=',$date)->count();
			$topSupport[] = ['total' => $total, 'name' => $agentName->first_name.' '.$agentName->last_name];
		}
		rsort($topSupport);
		
		return $topSupport;
	}
	
	public function assignSupport(Request $request){
		foreach ($request['support'] as $key => $value) {
			if($value['assigned_support'] != '')
				$assignSupport= DB::table('tickets')->where('id','=',$value['id'])->update(['assigned_support' => $value['assigned_support'],'ticket_status' => "Open"]);
			
		}
		return response()->json(array('success'=> "true"));
	}
	
	public function changeClientPassword(Request $request){
		$validator = Validator::make($request->all(),['password' => 'required|min:6','id' => 'exists:clients']);
		
		if($validator->fails()){
			return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));    
		}else{
			$changeClientPassword = DB::table('clients')->where('id',$request['id'])->update(['password' => bcrypt($request['password'])]);
			return response()->json(array('success'=> true));
		}
	}
	
	public function changeClientStatus(Request $request){
		$validator = Validator::make($request->all(),['id' => 'exists:clients']);
		
		if($validator->fails()){
			return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));    
		}else{
			$changeClientStatus = DB::table('clients')->where('id',$request['id'])->update(['status' => $request['status']]);
			return response()->json(array('success'=> true));
		}
	}
	
	public function changeAgentUserType(Request $request){
		$validator = Validator::make($request->all(),['id' => 'exists:admin']);
		
		if($validator->fails()){
			return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));    
		}else{
			$changeAgentStatus = DB::table('admin')->where('id',$request['id'])->update(['user_type' => $request['userType']]);
			return response()->json(array('success'=> true));
		}
		
	}
	
	public function showCreateClient(){
		$restriction = DB::table('ticket_restrictions')->get();
		
		return view('tickets.admin.createClient',['restrictions' => $restriction]);
	}

	public function closeTicket(Request $request){
		$validator = Validator::make($request->all(),['id' => 'exists:tickets','closing_report' => 'required|min:30']);
		
		if($validator->fails()){
			return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));    
		}else{
		$closeTicket = DB::table('tickets')->where('id',$request['id'])->update(['closing_report' => $request['closing_report'],
		'ticket_status' => 'Closed','closed_by' => Auth::guard('admin')->user()->id,'closed_at' => Carbon::now()]);
		
		return response()->json(array('success'=> true));	
		}
	}
	
	public function editAccount(){
		$restriction = DB::table('ticket_restrictions')->get();
		
		return view('tickets.admin.editAccount',['restrictions' => $restriction]);
	}
	public function changePersonalInfo(Request $request){
		$validator = Validator::make($request->all(),['id' => 'exists:admin']);
		
		if($validator->fails()){
			return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));    
		}else{
		$changePersonalInfo = DB::table('admin')->leftJoin('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->where('id',$request['id'])
		->update(['email' => $request['email'],'first_name' => $request['fname'],'last_name' => $request['lname'],
		 'admin.updated_at' => Carbon::now(),'admin_profiles.updated_at' => Carbon::now()]);
		
		return response()->json(array('success'=> true));	
		}
		
	}
	
	public function changePassword(Request $request){
		
		if (Auth::guard('admin')->attempt(['email' => Auth::guard('admin')->user()->email, 'password' => $request['oldPassword']]))
        {
            $validator = Validator::make($request->all(),['id' => 'exists:admin','password' => 'required|min:6|confirmed']);
			
			if($validator->fails()){
				return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));    
			}else{
				$changePassword = DB::table('admin')->where('id',$request['id'])->update(['password' => bcrypt($request['password']),'updated_at' => Carbon::now()]);
				return response()->json(array('success'=> true));
			}
        }
		else{
			return response()->json(array('success'=> false,'errors' => ['oldPassword' => 'Wrong Password. Please try again.']));
		}
	}
	
	
	public function printTicketDetails($id){
		$topics = DB::table('ticket_topics')->get();
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->where('user_type','agent')->orderBy('last_name')->get();
		
		$restriction = DB::table('ticket_restrictions')->get();
		
		$ticket = DB::table('tickets')
		->leftJoin('ticket_topics','tickets.topic_id',"=",'ticket_topics.topic_id')->where('id',$id)->first();
		
		$sender_email = DB::table('admin')->where('id',$ticket->sender_id)->first();
		if($sender_email == null){
			$sender_email = DB::table('clients')->where('id',$ticket->sender_id)->first();
		}
		
		$assignedTo = DB::table('tickets')->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')
		->leftJoin('ticket_topics','tickets.topic_id',"=",'ticket_topics.topic_id')->where('id',$id)->first();
		
		$closedBy = DB::table('tickets')->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
		->leftJoin('ticket_topics','tickets.topic_id',"=",'ticket_topics.topic_id')->where('id',$id)->first();
		
		session([
		'email' => $sender_email->email,'subject' => $ticket->subject,
		'date_sent' => $ticket->created_at,'date_modified' => $ticket->updated_at, 'summary' => $ticket->summary,
		'topic_id' => $ticket->topic_id,'topic' => $ticket->description,
		'id' => $ticket->id,'assigned_support' => $assignedTo->first_name.' '.$assignedTo->last_name,
		'status' => $ticket->ticket_status,'priority' => $ticket->priority,
		'closed_by' => $closedBy->first_name.' '.$closedBy->last_name,
		'closing_report' => $closedBy->closing_report]);
		
		return view('tickets.printTicket',['topics' => $topics,'restrictions' => $restriction,'agent' => $agents]);
		
	}

	public function editTopicDetails(Request $request){
		
		$editTopic = DB::table('ticket_topics')->where('topic_id',$request['editTopic'])->first();
		
		return response()->json(array('success'=> true,'editTopic' => $editTopic));
	}
	
	public function editTopic(Request $request){
		$editTopic = DB::table('ticket_topics')->where('topic_id',$request['editTopic_id'])->update(['description' => $request['description'],'priority_level' => $request['priority']]);
		
		return response()->json(array('success'=> true));
	}
	
	public function ticketCount(){
		
		$newtickets = DB::table('tickets')->where('ticket_status','Pending')
				->where('created_at','>=',Carbon::today())->count();
		
		$opentickets = DB::table('tickets')->where('ticket_status','Open')
				->whereBetween('updated_at',[Carbon::yesterday(),Carbon::tomorrow()])->count();
				
		$pendingtickets = DB::table('tickets')->where('ticket_status','Pending')
				->whereBetween('created_at',array(Carbon::yesterday(),Carbon::today()->endOfDay()))->count();
				
		$overduetickets = DB::table('tickets')->where('ticket_status','!=','Closed')
				->whereNotBetween('updated_at',array(Carbon::yesterday(),Carbon::tomorrow()))->count();
				
		$closedtickets = DB::table('tickets')->where('ticket_status','Closed')
				->where('closed_at','>=',Carbon::today())->count();
				
		$assignedtickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->where('ticket_status','open')->where('assigned_support',Auth::guard('admin')->user()->id)
			->orderBy('created_at','desc')->count();
				
		return response()->json(array('success'=> true,'newTickets' => $newtickets,'openTickets' => $opentickets,'pendingTickets' => $pendingtickets,'overdueTickets' => $overduetickets,'closedTickets' => $closedtickets,'assignedTickets' => $assignedtickets));
	}

	public function advancedEmailSearch(Request $request){
		$dateSent = $request['dateSent'];
		$dateClosed = $request['dateClosed'];
		$status = $request['statusSearch'];
		$topic = $request['topicSearch'];
		$agentSent = $request['agentSent'];
		$agentClosed = $request['agentClosed'];
		
		if($request['email'] != '' || $request['email'] != null){
			$sender = DB::table('clients')->where('email',$request['email'])->first();
			
			if($sender == null){
				$sender = DB::table('admin')->where('email',$request['email'])->first();
			}
			
			$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')
			->where('sender_id',$sender->id)->orderBy('created_at','desc');
			
			
		
		}else{
			$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')->orderBy('created_at','desc');
			
		
		}

		
		
		if($dateSent != ""){
			$tickets->where('created_at','like',"%$dateSent%");
			
		}
		if($dateClosed != ""){
			$tickets->where('closed_at','like',"%$dateClosed%");
			
		}
		if($status!= ""){
			$tickets->where('ticket_status',$status);
			
		}
		if($topic!= ""){
			$tickets->where('description',$topic);
			
		}
		if($agentSent != ""){
			$tickets->where('assigned_support',$agentSent);
			
		}
		if($agentClosed != ""){
			$tickets->where('closed_by',$agentClosed);
			
		}
		
		
		$topics = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();	
		
		$closed_by = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
		->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
		->orderBy('created_at','desc')
		->paginate(15);
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->orderBy('last_name')->get();
		
			$tickets = $tickets->simplePaginate(15);
			
			return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets,'topics'=> $topics,'closed_by' => $closed_by,
		'agent' => $agents]);
		
	}
	
}
