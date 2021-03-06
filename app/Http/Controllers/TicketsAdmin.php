<?php

namespace App\Http\Controllers;

use DB;
use App\Tickets as Tickets;
use App\TicketMessages as TicketMessages;
use App\TicketTopics as Topics;
use App\Admin as Admin;
use App\User as Clients;
use App\AdminProfile as AProfile;
use App\ClientProfile as CProfile;
use App\Departments as Department;
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
		
		$ticketsNoSupport = Tickets::leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'assigned_support', '=', "" )->orderBy ( 'updated_at', 'desc' )->get ();
		Tickets::where ( 'ticket_status', '!=', 'Closed' )->whereNotBetween ( 'updated_at', array (
				Carbon::yesterday (),
				Carbon::tomorrow ()
		) )->update (['ticket_status' => 'Unresolved']);
		return view ( 'tickets.admin.dashboard', [ 
				'noSupport' => $ticketsNoSupport,
				'agent' => $agents 
		] );
	}
	public function createAgent() {
		if (Auth::guard ( 'admin' )->user ()->user_type == 'admin') {
			return view ( 'tickets.admin.createAgent' );
		} else {
			abort ( 403 );
		}
	}
	public function checkPassword(Request $request) {
		if (Auth::guard ( 'admin' )->attempt ( [ 
				'email' => Auth::guard ( 'admin' )->user ()->email,
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
	public function showCreateTicket() {
		$users = Topics::where ( 'status', 1 )->get ();
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->get ();
		return view ( 'tickets.admin.createTicket', [ 
				'topics' => $users,
				'agent' => $agents 
		] );
	}
	public function createTicket(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'topic' => 'required',
				'priority' => 'required',
				'subject' => 'required|min:6|max:255',
				'summary' => 'required|min:15|max:10000' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 400 );
		} else {
			
			$ticket_status;
			if ($request ['assigned_support'] == null) {
				$request ['assigned_support'] = 0;
			}
			$ida;
			
			do {
				$ida = mt_rand ( 0, 99999999 );
			} while ( Tickets::where ( 'id' )->exists () );
			
			$filecount = 1;
			$attachmentpath = "";
			if ($request ['attachment'] != null) {
				$image = $request->file ( 'attachment' );
				$imgvalidatoR = Validator::make ( $image, [ 
						'attachment' => 'image|max:10485760' 
				] );
				foreach ( $image as $file ) {
					
					$imageName = $ida . $file->getClientOriginalName ();
					
					$file->move ( public_path ( '/img/attachment/' ), $imageName );
					$filecount ++;
					$attachmentpath = $attachmentpath . "/img/attachment/" . $imageName . ",";
				}
			}
			
			$user = Tickets::create ( [ 
					'id' => $ida,
					'sender_id' => Auth::guard ( 'admin' )->user ()->id,
					'topic_id' => $request ['topic'],
					'priority_level' => $request ['priority'],
					'subject' => $request ['subject'],
					'summary' => $request ['summary'],
					'ticket_status' => 'Open',
					'assigned_support' => $request ['assigned_support'],
					'department' => 'Support',
					'attachments' => $attachmentpath 
			] );
			return response ()->json ( [ 
					'response' => "" 
			] );
		}
	}
	public function showTopics() {
		$topics = Topics::get ();
		if (Auth::guard ( 'admin' )->user ()->user_type == 'admin') {
			return view ( 'tickets.admin.newTopics', [ 
					'topics' => $topics 
			] );
		} else {
			abort ( 403 );
		}
	}
	public function addTopic(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'description' => 'required|min:5|max:30|unique:ticket_topics,description',
				'priority' => 'required' 
		] );
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			
			$user = Topics::create ( [ 
					'description' => $request ['description'],
					'priority_level' => $request ['priority'],
					'status' => 1,
					'date_created' => Carbon::now (),
					'date_updated' => Carbon::now () 
			] );
			$topics = Topics::orderBy ( 'topic_id', 'desc' )->first ();
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
	public function deleteTicket(Request $request) {
		if (Auth::guard ( 'admin' )->user ()->user_type == 'admin') {
			foreach ( $request ['tickets'] as $key => $ticket ) {
				
				$ticket = Tickets::where ( 'id', $ticket )->delete ();
			}
		}
		return response ()->json ( array (
				'success' => "true" 
		) );
	}
	public function showClients() {
		$users = Clients::leftJoin ( 'client_profiles', 'clients.id', '=', 'client_profiles.client_id' )->get ();
		return view ( 'tickets.admin.showClients', [ 
				'clients' => $users 
		] );
	}
	public function showAgents() {
		$users = Admin::leftJoin ( DB::raw ( '(select agent_id, first_name, last_name from admin_profiles) agents' ), 'admin.id', '=', 'agents.agent_id' )->get ();
		return view ( 'tickets.admin.showAgents', [ 
				'agents' => $users 
		] );
	}
	public function showRestriction() {
		if (Auth::guard ( 'admin' )->user ()->user_type == 'admin') {
			$users = DB::table ( 'ticket_restrictions' )->get ();
			return view ( 'tickets.admin.showRestrictions', [ 
					'restrictions' => $users 
			] );
		} else {
			abort ( 403 );
		}
	}
	public function showTicketReport() {
		$topics = Topics::get ();
		
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		
		$tickets = Tickets::leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( DB::raw ( '(select agent_id, first_name as assign_FN, last_name as assign_LN
				from admin_profiles) assignedTo' ), 'tickets.assigned_support', '=', 'assignedTo.agent_id' )->leftJoin ( DB::raw ( '(select agent_id, first_name as close_FN, last_name as close_LN
				from admin_profiles) closedBy' ), 'tickets.assigned_support', '=', 'closedBy.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} )->get ();
		
		return view ( 'tickets.admin.ticketReport', [ 
				'topics' => $topics,
				'tickets' => $tickets,
				'agent' => $first->get () 
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
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		
		$topics = Topics::get ();
		$restriction = DB::table ( 'ticket_restrictions' )->get ();
		$agents = DB::table ( 'admin' )->join ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->where ( 'user_type', 'agent' )->orderBy ( 'last_name' )->get ();
		
		$ticket = Tickets::leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', "=", 'ticket_topics.topic_id' )
		->leftJoin ( DB::raw ( '(SELECT agent_id,first_name as assignFN, last_name as assignLN from admin_profiles) assignedSupport' ), function ($join) {
			$join->on ( 'tickets.assigned_support', '=', 'assignedSupport.agent_id' );
		} )->leftJoin ( DB::raw ( '(SELECT agent_id,first_name as closedFN, last_name as closedLN from admin_profiles) closedBy' ), function ($join) {
			$join->on ( 'tickets.closed_by', '=', 'closedBy.agent_id' );
		} )
		->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'sender_id', '=', 'names.nameid' );
		} )
		->where ( 'tickets.id', $id )->first ();
		
		if ($ticket->ticket_status == "Open") {
			$updateTicket = Tickets::find ( $id );
			$updateTicket->ticket_status = "Pending";
			$updateTicket->assigned_support = Auth::guard ( 'admin' )->user ()->id;
			$updateTicket->save ();
		}
		
		
		if ($ticket->ticket_status != 'Open') {
			$messages = TicketMessages::where ( 'ticket_id', $id )->get ();
			
			foreach ( $messages as $message ) {
				$name = DB::table ( 'admin_profiles' )->where ( 'agent_id', $message ['sender'] )->first ();
				if ($name == null) {
					$name = DB::table ( 'client_profiles' )->where ( 'client_id', $message ['sender'] )->first ();
				}
				$message ['sender'] = $name->first_name . ' ' . $name->last_name;
			}
			
			return view ( 'tickets.admin.viewTicketDetails', [ 
					'restrictions' => $restriction,
					'agent' => $agents,
					'messages' => $messages,
					'ticket' => $ticket 
			] );
		}
		
		return view ( 'tickets.admin.viewTicketDetails', [ 
				'restrictions' => $restriction,
				'agent' => $agents,
				'ticket' => $ticket 
		] );
	}
	public function showTicketReply($id = null) {
		if ($id == null) {
			abort ( 404 );
		}
		$ticket = Tickets::where ( 'id', $id )->first ();
		if ($ticket == null) {
			abort ( 404 );
		}
		$restriction = DB::table ( 'ticket_restrictions' )->get ();
		$sender_email = DB::table ( 'admin' )->select ( 'email' )->where ( 'id', $ticket->sender_id )->first ();
		
		if ($sender_email == null) {
			$sender_email = DB::table ( 'clients' )->select ( 'email' )->where ( 'id', $ticket->sender_id )->first ();
		}
		if ($sender_email == null) {
			$sender_email = "";
		}
		
		if ((Auth::guard ( 'admin' )->user ()->user_type == "agent" && $restriction [1]->agent == 1) || Auth::guard ( 'admin' )->user ()->user_type == 'admin') {
			session ( [ 
					'email' => $sender_email->email,
					'ticket_id' => $ticket->id 
			] );
			return view ( 'tickets.admin.ticketReply' );
		} else {
			abort ( 403 );
		}
	}
	public function ticketSearch(Request $request) {
		$columns = [ 
				"tickets.id",
				"ticket_topics.description",
				"ticket_status",
				"department",
				"first_name",
				"last_name",
				"assign_FN",
				"assign_LN",
				"close_FN",
				"close_LN" 
		];
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		
		$tickets = Tickets::leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( DB::raw ( '(select agent_id, first_name as assign_FN, last_name as assign_LN
				from admin_profiles) assignedTo' ), 'tickets.assigned_support', '=', 'assignedTo.agent_id' )->leftJoin ( DB::raw ( '(select agent_id, first_name as close_FN, last_name as close_LN
				from admin_profiles) closedBy' ), 'tickets.assigned_support', '=', 'closedBy.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} );
		$tickets = $tickets->newQuery ();
		foreach ( $columns as $column ) {
			$tickets->orWhere ( $column, 'like', '%' . $request ['search'] . '%' );
		}
		return response ()->json ( array (
				'success' => true,
				'response' => $tickets->get () 
		) );
	}
	public function advancedSearch(Request $request) {
		$dateSort = $request ['dateSort'];
		$status = $request ['statusSearch'];
		$topic = $request ['topicSearch'];
		$agentSent = $request ['agentSent'];
		$agentClosed = $request ['agentClosed'];
		
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		
		$tickets = Tickets::leftJoin ( 'ticket_topics', 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( DB::raw ( '(select agent_id, first_name as assign_FN, last_name as assign_LN
				from admin_profiles) assignedTo' ), 'tickets.assigned_support', '=', 'assignedTo.agent_id' )->leftJoin ( DB::raw ( '(select agent_id, first_name as close_FN, last_name as close_LN
				from admin_profiles) closedBy' ), 'tickets.assigned_support', '=', 'closedBy.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} );
		
		if ($status != "") {
			$tickets->where ( 'ticket_status', $status );
		}
		if ($topic != "") {
			$tickets->where ( 'description', $topic );
		}
		if ($agentSent != "") {
			$tickets->where ( 'assigned_support', $agentSent );
		}
		if ($agentClosed != "") {
			$tickets->where ( 'closed_by', $agentClosed );
		}
		
		if ($dateSort == 1 && ($request ['dateStart'] != null && $request ['dateEnd'] != null)) {
			$tickets->whereBetween ( 'tickets.created_at', [ 
					$request ['dateStart'],
					$request ['dateEnd'] 
			] );
		} elseif ($dateSort == 2 && ($request ['dateStart'] != null && $request ['dateEnd'] != null)) {
			$tickets->whereBetween ( 'tickets.updated_at', [ 
					$request ['dateStart'],
					$request ['dateEnd'] 
			] );
		}
		
		$tickets = $tickets->get ();
		
		return response ()->json ( array (
				'response' => $tickets 
		) );
	}
	public function showTickets() {
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		$topics = Topics::get ();
		
		$tickets = Tickets::leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} )->orderBy ( 'updated_at', 'desc' )->get();
		
		return view ( 'tickets.admin.showTickets', [ 
				'tickets' => $tickets,
				'topics' => $topics 
		] );
	}
	public function showTicketsAssigned() {
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		
		$topics = Topics::get ();
		$tickets = Tickets::leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'ticket_status', '!=', 'Closed' )->where ( 'assigned_support', Auth::guard ( 'admin' )->user ()->id )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} )->orderBy ( 'updated_at', 'desc' )->get();
		
		return view ( 'tickets.admin.showTickets', [ 
				'tickets' => $tickets,
				'topics' => $topics 
		] );
	}
	public function showTicketsOpen() {
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		$topics = Topics::get ();
		
		$tickets = Tickets::leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', '=', 'ticket_topics.topic_id' )
		->where ( 'ticket_status', 'Open' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} )->orderBy ( 'updated_at', 'desc' )->get();
		
		return view ( 'tickets.admin.showTickets', [ 
				'tickets' => $tickets,
				'topics' => $topics 
		] );
	}
	public function showTicketsPending() {
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		
		$topics = Topics::get ();
		
		$tickets = Tickets::leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'ticket_status', 'Pending' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} )->get();
		
		return view ( 'tickets.admin.showTickets', [ 
				'tickets' => $tickets,
				'topics' => $topics 
		] );
	}
	public function showTicketsUnresolved() {
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		
		$topics = Topics::get ();
		
		$tickets = Tickets::leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'ticket_status', 'Unresolved' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} )->orderBy ( 'updated_at', 'desc' )->get();
		
		return view ( 'tickets.admin.showTickets', [ 
				'tickets' => $tickets,
				'topics' => $topics 
		] );
	}
	public function showTicketsClosed() {
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		$topics = Topics::get ();
		$tickets = Tickets::leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->where ( 'ticket_status', 'Closed' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} )->orderBy ( 'updated_at', 'desc' )->get();
		
		return view ( 'tickets.admin.showTickets', [ 
				'tickets' => $tickets,
				'topics' => $topics 
		] );
	}
	public function sendReply(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'ticket_id' => 'required|exists:tickets,id',
				'email' => 'required|email',
				'message' => 'required|min:15|max:10000' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			
			/*
			 * Mail::raw ( html_entity_decode ( $request ['message'] ), function (\Illuminate\Mail\Message $message) use($request) {
			 * $message->subject ( $request ['subject'] );
			 * $message->replyTo ( Auth::guard ( 'admin' )->user ()->email );
			 * $message->to ( $request ['email'] );
			 * } );
			 */
			$message = new TicketMessages ();
			$message->ticket_id = $request ['ticket_id'];
			$message->message = $request ['message'];
			$message->sender = Auth::guard ( 'admin' )->user ()->id;
			$message->save ();
			
			return response ()->json ( array (
					'success' => true 
			) );
		}
	}
	public function changeTicketStatus(Request $request) {
		$changeStatus = Tickets::where ( 'id', $request ['id'] )->update ( [ 
				'ticket_status' => $request ['ticket_status'] 
		] );
		
		return response ()->json ( array (
				'success' => "true" 
		) );
	}
	public function forwardTicket(Request $request) {
		$forwardTo = Tickets::where ( 'id', $request ['id'] )->update ( [ 
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
	// Dashboard
	public function ticketStatus() {
		$status = [ 
				'x',
				'Open',
				'Pending',
				'Closed',
				'Unresolved' 
		];
		$statusTotal = [ ];
		
		for($i = 1; $i < count ( $status ); $i ++) {
			$total = DB::table ( 'tickets' )->where ( 'ticket_status', $status [$i] )->count ();
			$statusTotal [$status [$i]] = $total;
		}
		return $statusTotal;
	}
	public function ticketStatusInfo(Request $request) {
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		
		if ($request ['id'] != "Closed") {
			$tickets = Tickets::where ( 'ticket_status', $request ['id'] )->leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ),  'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( DB::raw ( '(select agent_id, first_name as assign_FN, last_name as assign_LN
			from admin_profiles) assignedTo' ), 'tickets.assigned_support', '=', 'assignedTo.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
				$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
			} )->orderBy ( 'updated_at', 'desc' )->get ();
		} else {
			$tickets = Tickets::where ( 'ticket_status', $request ['id'] )->leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ),  'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( DB::raw ( '(select agent_id, first_name as close_FN, last_name as close_LN
				from admin_profiles) closedBy' ), 'tickets.assigned_support', '=', 'closedBy.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
				$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
			} )->orderBy ( 'updated_at', 'desc' )->get ();
		}
		
		return response ()->json ( array (
				'success' => true,
				'tickets' => $tickets 
		) );
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
			$trendingTopics [] = Tickets::where ( 'topic_id', $topic_id [$i] )->count ();
		}
		
		for($i = 0; $i < count ( $topic_id ); $i ++) {
			$topIssues [$trendingDescription [$i]] = $trendingTopics [$i];
		}
		
		return $topIssues;
	}
	public function topIssueInfo(Request $request) {
		$topics = Topics::where ( 'description', $request ['id'] )->first ();
		
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		
		$tickets = Tickets::where ( 'tickets.topic_id', $topics->topic_id )->leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->leftJoin ( DB::raw ( '(select agent_id, first_name as assign_FN, last_name as assign_LN
			from admin_profiles) assignedTo' ), 'tickets.assigned_support', '=', 'assignedTo.agent_id' )->leftJoin ( DB::raw ( '(select agent_id, first_name as close_FN, last_name as close_LN
				from admin_profiles) closedBy' ), 'tickets.assigned_support', '=', 'closedBy.agent_id' )->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} )->orderBy ( 'updated_at', 'desc' )->get ();
		
		return response ()->json ( array (
				'success' => true,
				'tickets' => $tickets,
				'topic' => $topics 
		) );
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
			
			array_push ( $monthsDataSent, Tickets::whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count () );
			array_push ( $monthsDataClosed, Tickets::where ( 'ticket_status', 'Closed' )->whereBetween ( 'created_at', [ 
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
				$total = Tickets::where ( 'ticket_status', 'Closed' )->where ( 'closed_by', $b )->where ( 'updated_at', '>=', $date )->count ();
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
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'exists:tickets,id' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			
			$assignSupport = Tickets::find ( $request ['id'] );
			$assignSupport->assigned_support = $request ['support'];
			$assignSupport->save ();
			
			return response ()->json ( array (
					'success' => true 
			) );
		}
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
					'user_type' => $request ['userType'],
					'updated_at' => Carbon::now () 
			] );
			
			$agent_profile = DB::table ( 'admin_profiles' )->where ( 'agent_id', $request ['id'] )->update ( [ 
					'updated_at' => Carbon::now () 
			] );
			
			return response ()->json ( array (
					'success' => true,
					'input' => [ 
							'id' => $request ['id'],
							'user' => $request ['userType'] 
					] 
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
				'id' => 'exists:tickets,id',
				'closing_report' => 'required|min:15' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		} else {
			$closeTicket = Tickets::where ( 'id', $request ['id'] )->update ( [ 
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
	// Edit Account
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
	//
	public function printTicketDetails($id) {
		$ticket = Tickets::leftJoin ( 'ticket_topics', 'tickets.topic_id', "=", 'ticket_topics.topic_id' )->leftJoin ( DB::raw ( '(SELECT agent_id,first_name as assignFN, last_name as assignLN from admin_profiles) assignedSupport' ), function ($join) {
			$join->on ( 'tickets.assigned_support', '=', 'assignedSupport.agent_id' );
		} )->leftJoin ( DB::raw ( '(SELECT agent_id,first_name as closedFN, last_name as closedLN from admin_profiles) closedBy' ), function ($join) {
			$join->on ( 'tickets.closed_by', '=', 'closedBy.agent_id' );
		} )->where ( 'id', $id )->first ();
		
		if ($ticket == null) {
			abort ( 404 );
		}
		if ($ticket->assigned_support == null) {
			$updateTicket = Tickets::find ( $id );
			$updateTicket->ticket_status = "Pending";
			$updateTicket->assigned_support = Auth::guard ( 'admin' )->user ()->id;
			$updateTicket->save ();
		}
		$sendername = DB::table ( 'admin_profiles' )->where ( 'agent_id', $ticket->sender_id )->first ();
		if ($sendername == null) {
			$sendername = DB::table ( 'client_profiles' )->where ( 'client_id', $ticket->sender_id )->first ();
		}
		$ticket->sender_id = $sendername->first_name . ' ' . $sendername->last_name;
		
		if ($ticket->ticket_status != 'Open') {
			$messages = TicketMessages::where ( 'ticket_id', $id )->get ();
			
			foreach ( $messages as $message ) {
				$name = DB::table ( 'admin_profiles' )->where ( 'agent_id', $message ['sender'] )->first ();
				if ($name == null) {
					$name = DB::table ( 'client_profiles' )->where ( 'client_id', $message ['sender'] )->first ();
				}
				$message ['sender'] = $name->first_name . ' ' . $name->last_name;
			}
			
			return view ( 'tickets.printTicket', [ 
					
					'messages' => $messages,
					'ticket' => $ticket 
			] );
		}
		
		return view ( 'tickets.printTicket', [ 
				
				'ticket' => $ticket 
		] );
	}
	public function editTopicDetails(Request $request) {
		$editTopic = Topics::where ( 'topic_id', $request ['editTopic'] )->first ();
		
		return response ()->json ( array (
				'success' => true,
				'editTopic' => $editTopic 
		) );
	}
	public function topicDelete(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'required|exists:ticket_topics,topic_id' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 404 );
		}
		
		$topic = Topics::where ( 'topic_id', $request ['id'] );
		$topic->delete ();
		
		return response ()->json ( [ 
				'success' => true 
		] );
	}
	public function editTopic(Request $request) {
		$editTopic = Topics::where ( 'topic_id', $request ['editTopic_id'] )->update ( [ 
				'description' => $request ['description'],
				'priority_level' => $request ['priority'] 
		] );
		
		$topic = Topics::where ( 'topic_id', $request ['editTopic_id'] )->first ();
		return response ()->json ( array (
				'success' => true,
				'topic' => $topic 
		) );
	}
	public function ticketCount() {
		$opentickets = Tickets::where ( 'ticket_status', 'Open' )->count ();
		
		$pendingtickets = Tickets::where ( 'ticket_status', 'Pending' )->count ();
		
		$overduetickets = Tickets::where ( 'ticket_status', 'Unresolved' )->count ();
		
		$closedticketsToday = Tickets::where ( 'ticket_status', 'Closed' )->where ( 'closed_at', '>=', Carbon::today () )->count ();
		
		$closedtickets = Tickets::where ( 'ticket_status', 'Closed' )->count ();
		
		$assignedtickets = Tickets::where ( 'ticket_status', '!=', 'Closed' )->where ( 'assigned_support', Auth::guard ( 'admin' )->user ()->id )->orderBy ( 'created_at', 'desc' )->count ();
		
		return response ()->json ( array (
				'success' => true,
				'openTickets' => $opentickets,
				'pendingTickets' => $pendingtickets,
				'overdueTickets' => $overduetickets,
				'closedTicketsToday' => $closedticketsToday,
				'closedTickets' => $closedtickets,
				'assignedTickets' => $assignedtickets 
		) );
	}
	public function clientDelete(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'required|exists:clients,id' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			), 404 );
		}
		
		$client = Clients::find ( $request ['id'] );
		$client->delete ();
		
		$client = CProfile::where ( 'client_id', $request ['id'] );
		$client->delete ();
		
		return response ()->json ( [ 
				'success' => true 
		] );
	}
	public function agentDelete(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'required|exists:admin,id' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
		}
		
		$agent = Admin::find ( $request ['id'] );
		$agent->delete ();
		
		$agent = AProfile::where ( 'agent_id', $request ['id'] );
		$agent->delete ();
		return response ()->json ( [ 
				'success' => true 
		] );
	}
	// Department
	public function showDepartment() {
		$departments = Department::all ();
		return view ( 'tickets.admin.showDepartment', [ 
				'departments' => $departments 
		] );
	}
	public function addDepartment(Request $request, Department $department) {
		$validator = Validator::make ( $request->all (), [ 
				'department' => 'required|unique:departments,department|max:255',
				'head' => 'required|max:255',
				'description' => 'max:255' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
			
			return false;
		}
		
		$department = new Department ();
		$department->department = $request ['department'];
		$department->head = $request ['head'];
		$department->department_description = $request ['description'];
		$department->save ();
		
		$newDepartment = Department::orderBy ( 'created_at', 'desc' )->first ();
		return response ()->json ( array (
				'success' => true,
				'response' => $newDepartment 
		) );
	}
	public function departmentInfo(Request $request) {
		$department = Department::where ( 'id', $request ['department'] )->first ();
		
		return response ()->json ( array (
				'success' => true,
				'department' => $department 
		) );
	}
	public function editDepartment(Request $request) {
		$validator = Validator::make ( $request->all (), [ 
				'id' => 'exists:departments,id' 
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray () 
			) );
			
			return false;
		}
		
		$department = Department::find ( $request ['id'] );
		$department->department = $request ['department'];
		$department->head = $request ['head'];
		$department->department_description = $request ['description'];
		$department->save ();
		
		$newDepartment = Department::where ( 'id', $request ['id'] )->first ();
		return response ()->json ( array (
				'success' => true,
				'department' => $newDepartment 
		) );
	}
	
	// Admin Aget Profile
	public function agentProfile($id = null) {
		if ($id == null) {
			abort ( 404 );
		}
		$profile = Admin::leftJoin ( 'admin_profiles', 'admin.id', '=', 'admin_profiles.agent_id' )->find ( $id );
		if ($profile == null) {
			abort ( 404 );
		}
		$ticketCounts = Tickets::select ( DB::raw ( 'count(id) as ticketCount,ticket_status' ) )->where ( 'assigned_support', $id )->orWhere ( 'closed_by', $id )->groupBy ( 'ticket_status' )->get ();
		$first = AProfile::select ( 'agent_id as nameid', 'first_name', 'last_name' );
		$second = CProfile::select ( 'client_id as nameid', 'first_name', 'last_name' )->union ( $first );
		$topics = Topics::get ();
		
		$tickets = Tickets::leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'tickets.sender_id', '=', 'names.nameid' );
		} )->leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', '=', 'ticket_topics.topic_id' )->orderBy ( 'updated_at', 'desc' )->where ( 'assigned_support', $id )->orWhere ( 'closed_by', $id )->get ();
		return view ( 'tickets.admin.agentProfile', [ 
				'profile' => $profile,
				'ticketCounts' => $ticketCounts,
				'tickets' => $tickets 
		] );
	}
	public function agentTicketStats(Request $request) {
		$ticketsAssigned = [ ];
		$ticketsClosed = [ ];
		$ticketsUnresolved = [ ];
		
		array_push ( $ticketsAssigned, 'Assigned' );
		array_push ( $ticketsClosed, 'Closed' );
		array_push ( $ticketsUnresolved, 'Unresolved' );
		
		$xAxis = [ ];
		array_push ( $xAxis, 'x' );
		
		for($i = 11; $i >= 0; $i --) {
			$date = Carbon::today ();
			$dateStart = Carbon::today ()->subMonths ( $i )->startOfMonth ();
			$dateEnd = Carbon::today ()->subMonths ( $i )->endOfMonth ();
			
			$assigned_ticket = Tickets::where ( 'assigned_support', $request ['id'] )->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			array_push ( $ticketsAssigned, $assigned_ticket );
			
			$closed_ticket = Tickets::where ( 'closed_by', $request ['id'] )->where ( 'ticket_status', 'Closed' )->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			array_push ( $ticketsClosed, $closed_ticket );
			
			$unresolved_ticket = Tickets::where ( 'assigned_support', $request ['id'] )->where ( 'ticket_status', 'Unresolved' )->whereBetween ( 'created_at', [ 
					$dateStart,
					$dateEnd 
			] )->count ();
			array_push ( $ticketsUnresolved, $unresolved_ticket );
			
			$dateNum = $dateStart->month;
			$monthName = $dateStart->format ( 'M y' );
			array_push ( $xAxis, $monthName );
		}
		return [ 
				$xAxis,
				$ticketsAssigned,
				$ticketsClosed,
				$ticketsUnresolved 
		];
	}
}
