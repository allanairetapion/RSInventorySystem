<?php

namespace App\Http\Controllers;

use DB;
use App\Tickets as Ticket;
use App\TicketLogs as TicketLogs;
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

class TicketsAdmin extends Controller {
	public function __construct() {
		$this->middleware ( 'admin', [ 
				'except' => [ 
						'logout',
						'getLogout' 
				] 
		] );
	}
	public function index() {
		$today = Carbon::today ();
		
		
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->get ();
		
		
				
				$ticketsNoSupport = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'assigned_support', '=', "" )->get ();
				
				return view ( 'tickets.admin.dashboard', [ 
						'noSupport' => $ticketsNoSupport,
						'agent' => $agents 
				] );
			
	}
	public function createAgent() {
		if(Auth::guard('admin')->user()->user_type == 'admin'){
		return view ( 'tickets.admin.createAgent');
		}else{
			abort(403);
		}
	}
	public function checkPassword(Request $request) {
		if (Auth::guard ( 'admin' )->attempt ( [ 
				'email' => Auth::guard ( 'admin' )->user ()->email,
				'password' => $request ['password'] 
		] )) {
			return "true";
		} else {
			return "false";
		}
	}
	public function checkEmail(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'firstname' => 'required|min:3|alpha|max:255',
				'lastname' => 'required|min:2|alpha|max:255',
				'email' => 'required|email|max:255|unique:admin|unique:clients',
				'user_type' => 'required' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			return response ()->json ( [ 
					'response' => '' 
			] );
		}
	}
	public function showCreateTicket() {
		$users = DB::table ( 'ticket_topics' )->where ( 'status', 1 )->get ();
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->get ();
		return view ( 'tickets.admin.createTicket', ['topics' => $users,'agent' => $agents] );
	}
	public function createTicket(Request $request) {
			$validator = Validator::make ( $request->all (), [ 
					'topic' => 'required',
					'subject' => 'required|min:6|max:255',
					'summary' => 'required|min:10|max:10000' 
			] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			
			$ticket_status;
			if ($request ['assigned_support'] == null) {
				$request ['assigned_support'] = 0;
			}
			$ida;
			
			do {
				$ida = mt_rand ( 0, 99999999 );
			} while ( Ticket::where ( 'id' )->exists () );
			
			$user = Ticket::create ( [ 
					'id' => $ida,
					'sender_id' => Auth::guard ( 'admin' )->user ()->id,
					'topic_id' => $request ['topic'],
					'subject' => $request ['subject'],
					'summary' => $request ['summary'],
					'ticket_status' => 'Open',
					'assigned_support' => $request ['assigned_support'],
					'department' => 'Support' 
			] );
			return response ()->json ( [ 
					'response' => '' 
			] );
		}
	}
	public function showTopics() {
		$topics = DB::table ( 'ticket_topics' )->get ();
		if (Auth::guard ( 'admin' )->user ()->user_type == 'admin') {
			return view ( 'tickets.admin.newTopics', ['topics' => $topics] );
		} else {
			abort ( 403 );
		}
	}
	public function addTopic(Request $request) {
		
		$validator = Validator::make ( $request->all (), [ 
				'description' => 'required|min:5|max:30|unique:ticket_topics',
				'priority' => 'required' 
		] );
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			
			$user = TicketTopics::create ( [ 
					'description' => $request ['description'],
					'priority_level' => $request ['priority'],
					'status' => 1,
					'date_created' => Carbon::now (),
					'date_updated' => Carbon::now () 
			] );
			$topics = DB::table ( 'ticket_topics' )->orderBy ( 'topic_id', 'desc' )->get ();
			return response ()->json ( array (
					'response' => $topics 
			) );
		}
	}
	public function updateSelection(Request $request) {
		$users = DB::table ( 'ticket_topics' )->update ( [ 
				'status' => 0 
		] );
		
		foreach ( $request ['topics'] as $key => $topic ) {
			
			$users = DB::table ( 'ticket_topics' )->where ( 'topic_id', $topic )->update ( [ 
					'status' => 1,
					'date_updated' => Carbon::now () 
			] );
		}
	}
	public function deleteTopic(Request $request) {
		$deleteTopic = DB::table ( 'ticket_topics' )->where ( 'topic_id', $request ['deleteTopic'] )->delete ();
		$topics = DB::table ( 'ticket_topics' )->get ();
		return response ()->json ( array (
				'response' => $topics 
		) );
	}
	public function deleteTicket(Request $request) {
		if (Auth::guard ( 'admin' )->user ()->user_type == 'admin') {
			foreach ( $request ['tickets'] as $key => $ticket ) {
				
				$users = DB::table ( 'tickets' )->where ( 'id', $ticket )->delete ();
			}
		}
		return response ()->json ( array (
				'success' => "true" 
		) );
	}
	public function showClients() {
		$users = DB::table ( 'clients' )->leftJoin ( 'client_profiles', 'clients.id', '=', 'client_profiles.client_id' )->get ();
		return view ( 'tickets.admin.showClients', [ 
				'clients' => $users,] );
	}
	public function showAgents() {
		
		$users = DB::table ( 'admin' )->leftJoin ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->get ();
		return view ( 'tickets.admin.showAgents', [ 
				'agents' => $users,] );
	}
	public function showRestriction() {
		
		if(Auth::guard('admin')->user()->user_type == 'admin'){
			$users = DB::table ( 'ticket_restrictions' )->get ();
			return view ( 'tickets.admin.showRestrictions', [
					'restrictions' => $users
			] );
		}else{
			abort(403);
		}
		
	}
	public function showTicketReport() {
		$topics = DB::table ( 'ticket_topics' )->get ();
		
		$tickets = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->orderBy ( 'updated_at', 'desc' )->paginate ( 15 );
		
		foreach($tickets as $ticket){
			$name = DB::table('admin_profiles')->where('agent_id', $ticket->sender_id)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $ticket->sender_id)->first();
			}
			$ticket->sender_id = $name->first_name.' '.$name->last_name;
		}
		
		$assigned_to = DB::table ( 'tickets' )->leftJoin ( 'admin_profiles', 'tickets.assigned_support', '=', 'admin_profiles.agent_id' )->orderBy ( 'tickets.updated_at', 'desc' )->get ();
		
		$closed_by = DB::table ( 'tickets' )->leftJoin ( 'admin_profiles', 'tickets.closed_by', '=', 'admin_profiles.agent_id' )->orderBy ( 'tickets.updated_at', 'desc' )->get ();
		
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->orderBy ( 'last_name' )->get ();
		
		return view ( 'tickets.admin.ticketReport', [ 
				'topics' => $topics,
				'tickets' => $tickets,
				'assigned_to' => $assigned_to,
				'closed_by' => $closed_by,
				'agent' => $agents 
		] );
	}
	public function updateRestriction(Request $request) {
		$array = [ ];
		
		foreach ( $request as $key => $restriction ) {
			foreach ( $restriction as $name => $value ) {
				if ($value == 'admin')
					$users = DB::table ( 'ticket_restrictions' )->where ( 'id', $name )->update ( [ 
							'admin' => 1,
							'agent' => 0 
					] );
				
				if ($value == 'both')
					$users = DB::table ( 'ticket_restrictions' )->where ( 'id', $name )->update ( [ 
							'admin' => 1,
							'agent' => 1 
					] );
			}
		}
		
		return $array;
	}
	public function showTicketDetails($id) {
		$topics = DB::table ( 'ticket_topics' )->get ();
		$restriction = DB::table('ticket_restrictions')->get();
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->where ( 'user_type', 'agent' )->orderBy ( 'last_name' )->get ();
		
		$ticket = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', "=", 'ticket_topics.topic_id' )->where ( 'id', $id )->first ();
		
		if ($ticket->ticket_status == 'Open') {
			$tickets = DB::table ( 'tickets' )->where ( 'id', $id )->update ( ['ticket_status' => 'Pending']);
			$ticket = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', "=", 'ticket_topics.topic_id' )->where ( 'id', $id )->first ();
		}
		if ($ticket->ticket_status != 'Open') {
			$messages = TicketLogs::where('ticket_id',$id)->get();
			
			foreach($messages as $message){
				$name = DB::table('admin_profiles')->where('agent_id', $message['sender'])->first();
				if($name == null){
					$name = DB::table('admin_profiles')->where('client_id', $message['sender'])->first();
				}
				$message['sender'] = $name->first_name.' '.$name->last_name;
			}	
			
			
		}
		
		$sender_email = DB::table ( 'admin' )->select ( 'email' )->where ( 'id', $ticket->sender_id )->first ();
		if ($sender_email == null) {
			$sender_email = DB::table ( 'clients' )->select ( 'email' )->where ( 'id', $ticket->sender_id )->first ();
		}
		if ($sender_email == null) {
			$sender_email = [ 
					'email' => '' 
			];
		}
		
		$assignedTo = DB::table ( 'tickets' )->leftJoin ( 'admin_profiles', 'tickets.assigned_support', '=', 'admin_profiles.agent_id' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', "=", 'ticket_topics.topic_id' )->where ( 'id', $id )->first ();
		
		$closedBy = DB::table ( 'tickets' )->leftJoin ( 'admin_profiles', 'tickets.closed_by', '=', 'admin_profiles.agent_id' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', "=", 'ticket_topics.topic_id' )->where ( 'id', $id )->first ();
		
		session ( [ 
				'email' => reset ( $sender_email ),
				'subject' => $ticket->subject,
				'department' => $ticket->department,
				'date_sent' => $ticket->created_at,
				'date_modified' => $ticket->updated_at,
				'summary' => $ticket->summary,
				'topic_id' => $ticket->topic_id,
				'topic' => $ticket->description,
				'id' => $ticket->id,
				'assigned_support' => $assignedTo->first_name . ' ' . $assignedTo->last_name,
				'status' => $ticket->ticket_status,
				'priority' => $ticket->priority_level,
				'closed_by' => $closedBy->first_name . ' ' . $closedBy->last_name,
				'closing_report' => $closedBy->closing_report 
		] );
		
		return view ( 'tickets.admin.viewTicketDetails', [ 'restrictions' => $restriction,'agent' => $agents,'messages'=> $messages ] );
	}
	public function showTicketReply($id = null) {
		if ($id == null) {
			abort(404);
		}
		$ticket = DB::table ( 'tickets' )->where ( 'id', $id )->first ();
		if ($ticket == null) {
			abort(404);
		}
		$restriction = DB::table('ticket_restrictions')->get();
		$sender_email = DB::table ( 'admin' )->select ( 'email' )->where ( 'id', $ticket->sender_id )->first ();
		
		if ($sender_email == null) {
			$sender_email = DB::table ( 'clients' )->select ( 'email' )->where ( 'id', $ticket->sender_id )->first ();
		}
		if ($sender_email == null) {
			$sender_email = "";
		}
		
		if ((Auth::guard ( 'admin' )->user ()->user_type == "agent" && $restriction [1]->agent == 1) || Auth::guard ( 'admin' )->user ()->user_type == 'admin') {
			session ( [ 'email' => $sender_email->email,'ticket_id' => $ticket->id] );
			return view ( 'tickets.admin.ticketReply');
		} else {
			abort ( 403 );
		}
	}
	public function ticketSearch(Request $request) {
		$dateSort = $request ['dateSort'];
		$status = $request ['statusSearch'];
		$topic = $request ['topicSearch'];
	
		$tickets = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' );
		$topics = DB::table ( 'ticket_topics' )->get ();
		
		if ($status != "") {
			$tickets->where ( 'ticket_status', $status );
		}
		if ($topic != "") {
			$tickets->where ( 'description', $topic );
		}
		
		
			if($dateSort == 1  && ($request['dateStart'] != null && $request['dateEnd'] != null  )){
				$tickets->whereBetween ( 'tickets.created_at', [$request['dateStart'],$request['dateEnd']]);
	
			}elseif ($dateSort == 2  && ($request['dateStart'] != null && $request['dateEnd'] != null  )){
				$tickets->whereBetween ( 'tickets.updated_at', [$request['dateStart'],$request['dateEnd']]);
	
			}
		
			$tickets = $tickets->orderBy ( 'updated_at', 'desc' )->simplePaginate ( 15 );
			foreach($tickets as $ticket){
				$name = DB::table('admin_profiles')->where('agent_id', $ticket->sender_id)->first();
				if($name == null){
					$name = DB::table('client_profiles')->where('client_id', $ticket->sender_id)->first();
				}
				$ticket->sender_id = $name->first_name.' '.$name->last_name;
			}
	
		return view ( 'tickets.admin.showTickets', [ 'tickets' => $tickets,'topics' => $topics] );
	}
	
	public function advancedSearch(Request $request) {
		$dateSort = $request ['dateSort'];	
		$status = $request ['statusSearch'];
		$topic = $request ['topicSearch'];
		$agentSent = $request ['agentSent'];
		$agentClosed = $request ['agentClosed'];
		
		$tickets = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' );
		
		$assigned_to = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( 'admin_profiles', 'tickets.assigned_support', '=', 'admin_profiles.agent_id' );
		
		$closed_by = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( 'admin_profiles', 'tickets.closed_by', '=', 'admin_profiles.agent_id' );
		
		
		if ($status != "") {
			$tickets->where ( 'ticket_status', $status );
			$assigned_to->where( 'ticket_status', $status );
			$closed_by->where ( 'ticket_status', $status );
		}
		if ($topic != "") {
			$tickets->where ( 'description', $topic );
			$assigned_to->where( 'description', $topic );
			$closed_by->where ( 'description', $topic );
		}
		if ($agentSent != "") {
				
			$tickets->where('assigned_support',$agentSent);
			$assigned_to->where('assigned_support',$agentSent);
			$closed_by->where('assigned_support',$agentSent);
				
				
		}
		if ($agentClosed != "") {
			$tickets->where ( 'closed_by', $agentClosed );
			$assigned_to->where ( 'closed_by', $agentClosed );
			$closed_by->where ( 'closed_by', $agentClosed );
		}
		
		
			if($dateSort == 1 && ($request['dateStart'] != null && $request['dateEnd'] != null  )) {
				$tickets->whereBetween ( 'tickets.created_at', [$request['dateStart'],$request['dateEnd']]);
				$assigned_to->whereBetween ( 'tickets.created_at', [$request['dateStart'],$request['dateEnd']]);
				$closed_by->whereBetween ( 'tickets.created_at', [$request['dateStart'],$request['dateEnd']]);
			}elseif ($dateSort == 2  && ($request['dateStart'] != null && $request['dateEnd'] != null  )){
				$tickets->whereBetween ( 'tickets.updated_at', [$request['dateStart'],$request['dateEnd']]);
				$assigned_to->whereBetween ( 'tickets.updated_at', [$request['dateStart'],$request['dateEnd']]);
				$closed_by->whereBetween ( 'tickets.updated_at', [$request['dateStart'],$request['dateEnd']]);
			}	
		
		
		$tickets = $tickets->get();
		
		foreach($tickets as $ticket){
			$name = DB::table('admin_profiles')->where('agent_id', $ticket->sender_id)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $ticket->sender_id)->first();
			}
			$ticket->sender_id = $name->first_name.' '.$name->last_name;
		}
		
		return response ()->json ( array (
				'response' => $tickets,
				'assigned' => $assigned_to->get(),
				'closed' => $closed_by->get() 
		) );
	}
	
	public function showTickets() {
		$topics = DB::table ( 'ticket_topics' )->get ();
				$closed_by = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( 'admin_profiles', 'tickets.closed_by', '=', 'admin_profiles.agent_id' )->orderBy ( 'created_at', 'desc' )->paginate ( 15 );
		
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->orderBy ( 'last_name' )->get ();
		
		$tickets = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->orderBy ( 'updated_at', 'desc' )->simplePaginate ( 15 );
		
		foreach($tickets as $ticket){
			$name = DB::table('admin_profiles')->where('agent_id', $ticket->sender_id)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $ticket->sender_id)->first();
			}
			$ticket->sender_id = $name->first_name.' '.$name->last_name;
		}
		
		return view ( 'tickets.admin.showTickets', ['tickets' => $tickets,
				'topics' => $topics,
				'closed_by' => $closed_by,
				'agent' => $agents 
		] );
	}
	public function showTicketsAssigned() {
		$topics = DB::table ( 'ticket_topics' )->get ();
		$closed_by = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( 'admin_profiles', 'tickets.closed_by', '=', 'admin_profiles.agent_id' )->orderBy ( 'created_at', 'desc' )->paginate ( 15 );
		
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->orderBy ( 'last_name' )->get ();
		
		$tickets = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'ticket_status','!=', 'Closed' )->where ( 'assigned_support', Auth::guard ( 'admin' )->user ()->id )->orderBy ( 'updated_at', 'desc' )->simplePaginate ( 15 );
		foreach($tickets as $ticket){
			$name = DB::table('admin_profiles')->where('agent_id', $ticket->sender_id)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $ticket->sender_id)->first();
			}
			$ticket->sender_id = $name->first_name.' '.$name->last_name;
		}
		
		return view ( 'tickets.admin.showTickets', [ 
				'tickets' => $tickets,
				'topics' => $topics,
				'closed_by' => $closed_by,
				'agent' => $agents 
		] );
	}
	public function showTicketsOpen() {
		$topics = DB::table ( 'ticket_topics' )->get ();
		$closed_by = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( 'admin_profiles', 'tickets.closed_by', '=', 'admin_profiles.agent_id' )->orderBy ( 'created_at', 'desc' )->paginate ( 15 );
		
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->orderBy ( 'last_name' )->get ();
		
		$tickets = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'ticket_status', 'Open' )->whereBetween ( 'updated_at', [ 
				Carbon::yesterday (),
				Carbon::tomorrow () 
		] )->orderBy ( 'updated_at', 'desc' )->simplePaginate ( 15 );
		
		foreach($tickets as $ticket){
			$name = DB::table('admin_profiles')->where('agent_id', $ticket->sender_id)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $ticket->sender_id)->first();
			}
			$ticket->sender_id = $name->first_name.' '.$name->last_name;
		}
		
		return view ( 'tickets.admin.showTickets', [
				'tickets' => $tickets,
				'topics' => $topics,
				'closed_by' => $closed_by,
				'agent' => $agents 
		] );
	}
	public function showTicketsPending() {
		$topics = DB::table ( 'ticket_topics' )->get ();
		$closed_by = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( 'admin_profiles', 'tickets.closed_by', '=', 'admin_profiles.agent_id' )->orderBy ( 'created_at', 'desc' )->paginate ( 15 );
		
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->orderBy ( 'last_name' )->get ();
		
		$tickets = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'ticket_status', 'Pending' )->whereBetween ( 'updated_at', array (
				Carbon::yesterday (),
				Carbon::today ()->endOfDay () 
		) )->orderBy ( 'created_at', 'desc' )->simplePaginate ( 15 );
		
		foreach($tickets as $ticket){
			$name = DB::table('admin_profiles')->where('agent_id', $ticket->sender_id)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $ticket->sender_id)->first();
			}
			$ticket->sender_id = $name->first_name.' '.$name->last_name;
		}
		
		return view ( 'tickets.admin.showTickets', [ 
				'tickets' => $tickets,
				'topics' => $topics,
				'closed_by' => $closed_by,
				'agent' => $agents 
		] );
	}
	public function showTicketsUnresolved() {
		$topics = DB::table ( 'ticket_topics' )->get ();		
		$closed_by = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( 'admin_profiles', 'tickets.closed_by', '=', 'admin_profiles.agent_id' )->orderBy ( 'created_at', 'desc' )->paginate ( 15 );		
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->orderBy ( 'last_name' )->get ();		
		$tickets = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'ticket_status', 'Unresolved' )->orderBy ( 'updated_at', 'desc' )->simplePaginate ( 15 );
		
		foreach($tickets as $ticket){
			$name = DB::table('admin_profiles')->where('agent_id', $ticket->sender_id)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $ticket->sender_id)->first();
			}
			$ticket->sender_id = $name->first_name.' '.$name->last_name;
		}
		
		return view ( 'tickets.admin.showTickets', [ 
				'tickets' => $tickets,
				'topics' => $topics,
				'closed_by' => $closed_by,
				'agent' => $agents 
		] );
	}
	public function showTicketsClosed() {
		$topics = DB::table ( 'ticket_topics' )->get ();
		
		$closed_by = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( 'admin_profiles', 'tickets.closed_by', '=', 'admin_profiles.agent_id' )->orderBy ( 'created_at', 'desc' )->paginate ( 15 );
		
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->orderBy ( 'last_name' )->get ();
		
		
		$tickets = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'ticket_status', 'Closed' )->orderBy ( 'updated_at', 'desc' )->simplePaginate ( 15 );
		
		foreach($tickets as $ticket){
			$name = DB::table('admin_profiles')->where('agent_id', $ticket->sender_id)->first();
			if($name == null){
				$name = DB::table('client_profiles')->where('client_id', $ticket->sender_id)->first();
			}
			$ticket->sender_id = $name->first_name.' '.$name->last_name;
		}
		
		return view ( 'tickets.admin.showTickets', [ 
				'tickets' => $tickets,
				'topics' => $topics,
				'closed_by' => $closed_by,
				'agent' => $agents 
		] );
	}
	public function sendReply(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'ticket_id' => 'required|exists:tickets,id',
				'email' => 'required|email',
				'message' => 'required|min:15' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			
			/*Mail::raw ( html_entity_decode ( $request ['message'] ), function (\Illuminate\Mail\Message $message) use($request) {
				$message->subject ( $request ['subject'] );
				$message->replyTo ( Auth::guard ( 'admin' )->user ()->email );
				$message->to ( $request ['email'] );
			} );
			*/
			$message = new TicketLogs;
			$message->ticket_id = $request ['ticket_id'];
			$message->message = $request ['message'];
			$message->sender = Auth::guard('admin')->user()->id;
			$message->save();
			
			return response ()->json ( array (
					'success' => true 
			) );
		}
	}
	public function changeTicketStatus(Request $request) {
		$changeStatus = DB::table ( 'tickets' )->where ( 'id', $request ['id'] )->update ( [ 
				'ticket_status' => $request ['ticket_status'],
				'closing_report' => '' 
		] );
		
		return response ()->json ( array (
				'success' => "true" 
		) );
	}
	public function forwardTicket(Request $request) {
		$forwardTo = DB::table ( 'tickets' )->where ( 'id', $request ['id'] )->update ( [ 
				'assigned_support' => $request ['agent'],
				'ticket_status' => 'Open' 
		] );
		
		if ($forwardTo != null) {
			return response ()->json ( array (
					'success' => true 
			) );
		} else {
			return response ()->json ( array (
					'success' => false 
			) );
		}
	}
	public function ticketStatus(){
		$status = ['x','Open','Pending','Closed','Unresolved'];
		$statusTotal = [];
	
		for($i = 1; $i < count ( $status ); $i ++) {
			$total = DB::table('tickets')->where('ticket_status',$status[$i])->count();
			$statusTotal[$status[$i]] = $total;
		}
		return $statusTotal;
	}
	
	public function topIssue(Request $request) {
		$today = Carbon::today ();
		
		$topic_id = array ();
		$trendingTopics = array ();
		$trendingDescription = array ();
		$topics = DB::table ( 'ticket_topics' )->select ( 'topic_id' )->get ();
		$topicsDesc = DB::table ( 'ticket_topics' )->select ( 'description' )->get ();
		$topIssues = [ ];
		
		foreach ( $topicsDesc as $desc ) {
			foreach ( $desc as $key => $value ) {
				$trendingDescription [] = $value;
			}
		}
		
		foreach ( $topics as $topic ) {
			foreach ( $topic as $key => $value ) {
				$topic_id [] = $value;
			}
		}
		
		for($i = 0; $i < count ( $topic_id ); $i ++) {
			$trendingTopics [] = DB::table ( 'tickets' )->where ( 'topic_id', $topic_id [$i] )->count ();
		}
		
		for($i = 0; $i < count ( $topic_id ); $i ++) {
			$topIssues [$trendingDescription [$i]] = $trendingTopics [$i];
		}
		
		return $topIssues;
	}
	public function ticketSummary(Request $request) {
		
			$monthsDataSent = [ ];
			array_push ( $monthsDataSent, 'Sent' );
			$monthsDataClosed = [ ];
			array_push ( $monthsDataClosed, 'Closed' );
			$xAxis = [ ];
			array_push ( $xAxis, 'x' );
			
			for($i = 3; $i >= 0; $i --) {
				$date = Carbon::today ();
				$dateStart = Carbon::today ()->subMonths ( $i )->startOfMonth ();
				$dateEnd = Carbon::today ()->subMonths ( $i )->endOfMonth ();
				
				array_push ( $monthsDataSent, DB::table ( 'tickets' )->whereBetween ( 'created_at', [ 
						$dateStart,
						$dateEnd 
				] )->count () );
				array_push ( $monthsDataClosed, DB::table ( 'tickets' )->where ( 'ticket_status', 'Closed' )->whereBetween ( 'created_at', [ 
						$dateStart,
						$dateEnd 
				] )->count () );
				
				$dateNum = $dateStart->month;
				$monthName = $dateStart->format ( 'F' );
				array_push ( $xAxis, $monthName );
			}
			return [ 
					$xAxis,
					$monthsDataSent,
					$monthsDataClosed 
			];
		
		
	}
	public function topSupport(Request $request) {
		if ($request ['topSupport'] == 'Week') {
			$date = Carbon::today ()->startOfWeek ();
		} elseif ($request ['topSupport'] == 'Month') {
			$date = Carbon::today ()->startOfMonth ();
		} else {
			$date = Carbon::today ()->startOfYear ();
		}
		
		$agents = DB::table ( 'admin' )->select ( 'id' )->get ();
		$agentIDs = [ ];
		$topSupport = [ ];
		foreach ( $agents as $key => $value ) {
			foreach ( $value as $a => $b ) {
				$agentName = DB::table ( 'admin_profiles' )->where ( 'agent_id', $b )->first ();
				$total = DB::table ( 'tickets' )->where ( 'ticket_status', 'Closed' )->where ( 'closed_by', $b )->where ( 'updated_at', '>=', $date )->count ();
				$topSupport [] = [ 
						'total' => $total,
						'name' => $agentName->first_name . ' ' . $agentName->last_name 
				];
			}
		}
		
		rsort ( $topSupport );
		
		return $topSupport;
	}
	public function assignSupport(Request $request) {
		foreach ( $request ['support'] as $key => $value ) {
			if ($value ['assigned_support'] != '')
				$assignSupport = DB::table ( 'tickets' )->where ( 'id', '=', $value ['id'] )->update ( [ 
						'assigned_support' => $value ['assigned_support'],
						'ticket_status' => "Open" 
				] );
		}
		return response ()->json ( array (
				'success' => "true" 
		) );
	}
	public function changeClientPassword(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'password' => 'required|min:6',
				'id' => 'exists:clients' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$changeClientPassword = DB::table ( 'clients' )->where ( 'id', $request ['id'] )->update ( [ 
					'password' => bcrypt ( $request ['password'] ) 
			] );
			return response ()->json ( array (
					'success' => true 
			) );
		}
	}
	public function changeClientStatus(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'exists:clients' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$changeClientStatus = DB::table ( 'clients' )->where ( 'id', $request ['id'] )->update ( [ 
					'status' => $request ['status'] 
			] );
			return response ()->json ( array (
					'success' => true 
			) );
		}
	}
	public function changeAgentUserType(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'exists:admin' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$changeAgentStatus = DB::table ( 'admin' )->where ( 'id', $request ['id'] )->update ( [ 
					'user_type' => $request ['userType'] 
			] );
			return response ()->json ( array (
					'success' => true 
			) );
		}
	}
	public function showCreateClient() {
		$restriction = DB::table ( 'ticket_restrictions' )->get ();
		
		return view ( 'tickets.admin.createClient', [ 
				'restrictions' => $restriction 
		] );
	}
	public function closeTicket(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'exists:tickets',
				'closing_report' => 'required|min:30' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$closeTicket = DB::table ( 'tickets' )->where ( 'id', $request ['id'] )->update ( [ 
					'closing_report' => $request ['closing_report'],
					'ticket_status' => 'Closed',
					'closed_by' => Auth::guard ( 'admin' )->user ()->id,
					'closed_at' => Carbon::now () 
			] );
			
			return response ()->json ( array (
					'success' => true 
			) );
		}
	}
	public function editAccount() {
		$restriction = DB::table ( 'ticket_restrictions' )->get ();
		
		return view ( 'tickets.admin.editAccount', [ 
				'restrictions' => $restriction 
		] );
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
		if (Auth::guard ( 'admin' )->attempt ( [ 
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
	public function printTicketDetails($id) {
		$ticket = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', "=", 'ticket_topics.topic_id' )->where ( 'id', $id )->first ();
		
		if ($ticket->ticket_status == 'Open') {
			$tickets = DB::table ( 'tickets' )->where ( 'id', $id )->update ( ['ticket_status' => 'Pending']);
			$ticket = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', "=", 'ticket_topics.topic_id' )->where ( 'id', $id )->first ();
		}
		if ($ticket->ticket_status != 'Open') {
			$messages = TicketLogs::where('ticket_id',$id)->get();
			
			foreach($messages as $message){
				$name = DB::table('admin_profiles')->where('agent_id', $message['sender'])->first();
				if($name == null){
					$name = DB::table('admin_profiles')->where('client_id', $message['sender'])->first();
				}
				$message['sender'] = $name->first_name.' '.$name->last_name;
			}	
			
			
		}
		
		$sender_email = DB::table ( 'admin' )->select ( 'email' )->where ( 'id', $ticket->sender_id )->first ();
		if ($sender_email == null) {
			$sender_email = DB::table ( 'clients' )->select ( 'email' )->where ( 'id', $ticket->sender_id )->first ();
		}
		if ($sender_email == null) {
			$sender_email = [ 
					'email' => '' 
			];
		}
		
		$assignedTo = DB::table ( 'tickets' )->leftJoin ( 'admin_profiles', 'tickets.assigned_support', '=', 'admin_profiles.agent_id' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', "=", 'ticket_topics.topic_id' )->where ( 'id', $id )->first ();
		
		$closedBy = DB::table ( 'tickets' )->leftJoin ( 'admin_profiles', 'tickets.closed_by', '=', 'admin_profiles.agent_id' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', "=", 'ticket_topics.topic_id' )->where ( 'id', $id )->first ();
		
		session ( [ 
				'email' => reset ( $sender_email ),
				'subject' => $ticket->subject,
				'department' => $ticket->department,
				'date_sent' => $ticket->created_at,
				'date_modified' => $ticket->updated_at,
				'summary' => $ticket->summary,
				'topic_id' => $ticket->topic_id,
				'topic' => $ticket->description,
				'id' => $ticket->id,
				'assigned_support' => $assignedTo->first_name . ' ' . $assignedTo->last_name,
				'status' => $ticket->ticket_status,
				'priority' => $ticket->priority_level,
				'closed_by' => $closedBy->first_name . ' ' . $closedBy->last_name,
				'closing_report' => $closedBy->closing_report 
		] );
		return view ( 'tickets.printTicket', [ 
				'messages' => $messages
		] );
	}
	public function editTopicDetails(Request $request) {
		$editTopic = DB::table ( 'ticket_topics' )->where ( 'topic_id', $request ['editTopic'] )->first ();
		
		return response ()->json ( array (
				'success' => true,
				'editTopic' => $editTopic 
		) );
	}
	public function editTopic(Request $request) {
		$editTopic = DB::table ( 'ticket_topics' )->where ( 'topic_id', $request ['editTopic_id'] )->update ( [ 
				'description' => $request ['description'],
				'priority_level' => $request ['priority'] 
		] );
		
		$topic = DB::table ( 'ticket_topics' )->where ( 'topic_id', $request ['editTopic_id'] )->first ();
		return response ()->json ( array (
				'success' => true,
				'topic' => $topic 
		) );
	}
	
	
	public function ticketCount() {
		$newtickets = DB::table ( 'tickets' )->where ( 'ticket_status', 'Pending' )->where ( 'created_at', '>=', Carbon::today () )->count ();
		
		$opentickets = DB::table ( 'tickets' )->where ( 'ticket_status', 'Open' )->whereBetween ( 'updated_at', [ 
				Carbon::yesterday (),
				Carbon::tomorrow () 
		] )->count ();
		
		$pendingtickets = DB::table ( 'tickets' )->where ( 'ticket_status', 'Pending' )->whereBetween ( 'updated_at', array (
				Carbon::yesterday (),
				Carbon::today ()->endOfDay () 
		) )->count ();
		
		$overduetickets = DB::table ( 'tickets' )->where ( 'ticket_status', 'Unresolved' )->count ();
		
		$closedticketsToday = DB::table ( 'tickets' )->where ( 'ticket_status', 'Closed' )->where ( 'closed_at', '>=', Carbon::today () )->count ();
		
		$closedtickets = DB::table ( 'tickets' )->where ( 'ticket_status', 'Closed' )->count ();
		
		$assignedtickets = DB::table ( 'tickets' )->leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'ticket_status','!=', 'Closed' )->where ( 'assigned_support', Auth::guard ( 'admin' )->user ()->id )->orderBy ( 'created_at', 'desc' )->count ();
		
		return response ()->json ( array (
				'success' => true,
				'newTickets' => $newtickets,
				'openTickets' => $opentickets,
				'pendingTickets' => $pendingtickets,
				'overdueTickets' => $overduetickets,
				'closedTicketsToday' => $closedticketsToday,
				'closedTickets' => $closedtickets,
				'assignedTickets' => $assignedtickets 
		) );
	}
	
	public function clientDelete(Request $request){
		if(DB::table ( 'clients' )->where ( 'id', $request ['id'] )->exists()){
			$deleteClient = DB::table ( 'clients' )->where ( 'id', $request ['id'] )->delete ();
			
			return response ()->json ( array (
					'success' => true,
					
			) );
		}else{
			return response ()->json ( array (
					'success' => false,
					
			) );
		}
		
	}
	
}
