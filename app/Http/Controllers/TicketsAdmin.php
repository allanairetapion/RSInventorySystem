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
		->where('user_type','agent')->get();
	
		
			
			if(Auth::guard('admin')){
			$newtickets = DB::table('tickets')->where('ticket_status','Pending')
			->where('created_at','>=',Carbon::today())->get();
			
			$pendingtickets = DB::table('tickets')->where('ticket_status','Open')
			->whereBetween('created_at',array(Carbon::today(),Carbon::tomorrow()))->get();
			
			$overdueTickets = DB::table('tickets')->where('ticket_status','!=','Closed')
			->whereNotBetween('created_at',array(Carbon::today(),Carbon::tomorrow()->addDay()))->get();
			
			$closedtickets = DB::table('tickets')->where('ticket_status','Closed')
			->whereBetween('created_at',array(Carbon::today(),Carbon::tomorrow()))->get();
			
			$ticketsNoSupport = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
			->where('assigned_support','=',"")->get();
        	
        	return view('tickets.admin.dashboard',['newTickets' => $newtickets,
        	'pendingTickets' => $pendingtickets,
        	'overdueTickets' => $overdueTickets,
        	'closedTickets' => $closedtickets,
        	'noSupport' => $ticketsNoSupport,
        	'restrictions' => $restriction,        	        	
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
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->where('user_type','agent')->get();
		
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
			
			
    	do{
    		
			$ida = DB::table('tickets')->select('id')->max('id') + 1;
			
		}
		
		while (DB::table('admin')->where('id',$ida)->first() != null);	
			
			
			$user = Tickets::create([
			'id' => $ida,
            'sender' => Auth::guard('admin')->user()->adminProfile ? Auth::guard('admin')->user()
            ->adminProfile->first_name.' '.Auth::guard('admin')->user()->adminProfile->last_name : '',
            'sender_id' => Auth::guard('admin')->user()->id,
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
            return response()->json(array('success'=> false, 'errors' => $validator->getMessageBag()->toArray() ));           
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
	
	public function showTicketStatus(){
		$topics = DB::table('ticket_topics')->get();
		$restriction = DB::table('ticket_restrictions')->get();
		
		$users = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
		->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')
		->orderBy('created_at','desc')
		->paginate(15);
		
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->where('user_type','agent')->orderBy('last_name')->get();
		
		return view('tickets.admin.ticketReport',['topics'=> $topics,'tickets' => $users,'restrictions' => $restriction,
		'agent' => $agents]);
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
	public function showTicketDetails($id){
		$topics = DB::table('ticket_topics')->get();
		$agents = DB::table('admin')->join('admin_profiles','admin.id','=','admin_profiles.agent_id')
		->where('user_type','agent')->orderBy('last_name')->get();
		
		$restriction = DB::table('ticket_restrictions')->get();
		
		$ticket = DB::table('tickets')->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')
		->leftJoin('ticket_topics','tickets.topic_id',"=",'ticket_topics.topic_id')->where('id',$id)->first();
		
		$sender_email = DB::table('admin')->where('id',$ticket->sender_id)->first();
		if($sender_email == null){
			$sender_email = DB::table('clients')->where('id',$ticket->sender_id)->first();
		}
		
		$closedBy = DB::table('tickets')->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')
		->leftJoin('ticket_topics','tickets.topic_id',"=",'ticket_topics.topic_id')->where('id',$id)->first();
		
		session([
		'email' => $sender_email->email,'subject' => $ticket->subject,
		'date_sent' => $ticket->created_at,'summary' => $ticket->summary,
		'topic_id' => $ticket->topic_id,'topic' => $ticket->description,
		'id' => $ticket->id,'assigned_support' => $ticket->first_name.' '.$ticket->last_name,
		'agent_id' => $ticket->agent_id, 'status' => $ticket->ticket_status,
		'priority' => $ticket->priority,'closed_by' => $closedBy->first_name.' '.$closedBy->last_name,
		'closing_report' => $closedBy->closing_report]);
		
		return view('tickets.admin.viewTicketDetails',['topics' => $topics,'restrictions' => $restriction,'agent' => $agents]);
	}
	public function showTicketReply($id){
		$ticket = DB::table('tickets')->where('id',$id)->first();
		
		$sender_email = DB::table('admin')->where('id',$ticket->sender_id)->first();
		if($sender_email == null){
			$sender_email = DB::table('clients')->where('id',$ticket->sender_id)->first();
		}
		
		$restriction = DB::table('ticket_restrictions')->get();
		session(['email' => $sender_email->email,]);
		return view('tickets.admin.ticketReply',['restrictions' => $restriction]);
	}
	
	public function deleteTicket(Request $request){
		foreach ($request as $key => $topic ) {
			foreach ($topic as $key => $value) {
				$users = DB::table('tickets')->where('id',$value)->delete();																				
			}			
		}		
		return response()->json(array('success'=> "true"));		
	}
	
	public function advancedSearch(Request $request){
		$dateSent = $request['dateSent'];
		$dateClosed = $request['dateClosed'];
		$status = $request['statusSearch'];
		$topic = $request['topicSearch'];
		$agentSent = $request['agentSent'];
		$agentClosed = $request['agentClosed'];

		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
		->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')->orderBy('created_at','desc');
		
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
		
		$data = $tickets->get();
			
		
		return response()->json(array('response'=> $data));        
	}
	
	public function showTickets(){
		$restriction = DB::table('ticket_restrictions')->get();
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
		->orderBy('created_at','desc')->simplePaginate(15);
		return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets]);
	}
	
	public function showTicketsOpen(){
		$restriction = DB::table('ticket_restrictions')->get();
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
		->where('ticket_status','open')->orderBy('created_at','desc')->simplePaginate(15);
		return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets]);
	}
	public function showTicketsPending(){
		$restriction = DB::table('ticket_restrictions')->get();
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
		->where('ticket_status','pending')->orderBy('created_at','desc')->simplePaginate(15);
		return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets]);
	}
	public function showTicketsClosed(){
		$restriction = DB::table('ticket_restrictions')->get();
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
		->where('ticket_status','closed')->orderBy('created_at','desc')->simplePaginate(15);
		return view('tickets.admin.showTickets',['restrictions' => $restriction,'tickets' => $tickets]);
	}
	
	public function sendReply(Request $request){				
		
		Mail::raw(html_entity_decode($request['summary']), function(\Illuminate\Mail\Message $message) use ($request) {
		$message->subject($request['subject']);
    	$message->replyTo( Auth::guard('admin')->user()->email);
    	$message->to($request['email']);
		
		});		
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
		
		$monthsDataSent = [];
		array_push($monthsDataSent,'Sent');
		$monthsDataClosed = [];
		array_push($monthsDataClosed,'Closed');
		$monthsText = [];
		array_push($monthsText,'x');
		
		for ($i=3; $i >= 0 ; $i--) {
			$date = Carbon::today();
			$dateStart = Carbon::today()->subMonths($i)->startOfMonth();
			$dateEnd = Carbon::today()->subMonths($i)->endOfMonth();
			
			$dateNum = $dateStart->month;
			$monthName = $dateStart->format('F'); 
			array_push($monthsText,$monthName);
			
			
			array_push($monthsDataSent,DB::table('tickets')->whereBetween('created_at',[$dateStart,$dateEnd])->count());
			array_push($monthsDataClosed,DB::table('tickets')->where('ticket_status','Closed')->whereBetween('created_at',[$dateStart,$dateEnd])->count());
		}
		
		
		
		return [$monthsText,$monthsDataSent,$monthsDataClosed];
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
		->update(['email' => $request['email'],'first_name' => $request['fname'],'last_name' => $request['lname']]);
		
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
				$changePassword = DB::table('admin')->where('id',$request['id'])->update(['password' => bcrypt($request['password'])]);
				return response()->json(array('success'=> true));
			}
        }
		else{
			return response()->json(array('success'=> false,'errors' => ['oldPassword' => 'Wrong Password. Please try again.']));
		}
	}
//{!!html_entity_decode($post->body)!!}
	
}
