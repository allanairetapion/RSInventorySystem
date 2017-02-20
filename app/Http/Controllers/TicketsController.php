<?php
namespace App\Http\Controllers;
use App\Tickets as Ticket;
use App\TicketMessages as TicketMessages;
use App\TicketTopics as TicketTopics;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Validator;
use App\Client as Client;
use App\AdminProfile as AProfile;
use App\ClientProfile as CProfile;
use app\user;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;



class TicketsController extends Controller{

	public function __construct(){
        $this->middleware('user');
   }	 
	public function landingPage(Request $request){
	 	if(Auth::guard('user')->check()){
	 		$tickets = Ticket::leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')
	 		->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')
	 		->where('sender_id',Auth::guard('user')->user()->id)
	 		->orderBy('tickets.created_at','desc')
	 		->take(10)->get();
	 		
	 		
	 		$pendingTickets = DB::table('tickets')->where('ticket_status',"!=",'Closed')->where('sender_id',Auth::guard('user')->user()->id)->get();
	 		$users = DB::table('ticket_topics')->where('status',1)->get();	
			return view("tickets.landingPage",['topics' => $users,'tickets' => $tickets,'pendingTickets' => $pendingTickets]);
		}
		else {
			return redirect('tickets/login');
		}
	 }		  	 	 
	public function showCreateTicket(){
		$users = DB::table('ticket_topics')->where('status',1)->get();
		return view('tickets.createTicket',['topics' => $users]);
	}	
	public function createTicket(Request $request){
		$validator = Validator::make($request->all(),[
        	'topic' => 'required',
        	'subject'  => 'required|min:6|max:255',    
            'summary' =>'required|min:15',            
        ]);

        if ($validator->fails()) {
            return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()),400); 
          
        }
		else {
			
			
			$ida;
		$tickets;
		do{
			$ida = mt_rand(0, 99999999);					
		}
		while (Ticket::where('id')->exists());
		$filecount = 1;
		$attachmentpath = "";
		if($request['attachment'] != null){
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
		};
			$user = Ticket::create([
			'id' => $ida,
            'sender_id' => Auth::guard('user')->user()->id,
            'topic_id' => $request['topic'],
            'subject' => $request['subject'],
            'summary' => $request['summary'],
            'ticket_status' => 'Open',            
            'department' =>Auth::guard('user')->user()->department,
					'attachments' => $attachmentpath
        ]);
			
			return response()->json(['response' => '']); 
			
			
		}
	}
	public function showTicketStatus(){
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')->where('sender_id',Auth::guard('user')->user()->id)->paginate(15);
		$closedby = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')->leftJoin('admin_profiles','tickets.closed_by','=','admin_profiles.agent_id')->where('sender_id',Auth::guard('user')->user()->id)->paginate(15);
		
		return view('tickets.ticketStatus',['tickets' => $tickets,'closedby' => $closedby]);
		
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
		
		for ($i=0; $i < count($topic_id); $i++) {
			
			 $a = DB::table('tickets')->where('topic_id',$topic_id[$i])->where('sender_id','=',Auth::guard('user')->user()->id)->count();	
			
			 $trendingTopics[] = $a;
		 	}
		 
		for ($i=0; $i < count($topic_id) ; $i++) { 
				$topIssues[$trendingDescription[$i]] = $trendingTopics[$i];
				
			}
			
		return $topIssues;
	}	
		
		public function editAccount(){
			return view('tickets.editAccount');
		}
		
	public function changePersonalInfo(Request $request){
		$validator = Validator::make($request->all(),
				['id' => 'exists:clients']);
		
		if($validator->fails()){
			return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));    
		}else{
			
		$changePersonalInfo = DB::table('clients')->leftJoin('client_profiles','clients.id','=','client_profiles.client_id')
		->where('id',$request['id'])
		->update(['email' => $request['email'],'first_name' => $request['fname'],'last_name' => $request['lname'],
		'clients.updated_at' => Carbon::now(),'client_profiles.updated_at' => Carbon::now()]);
		
		return response()->json(array('success'=> true));	
		
		}
		
	}
	
	public function changePassword(Request $request){
		
		if (Auth::guard('user')->attempt(['email' => Auth::guard('user')->user()->email, 'password' => $request['oldPassword']]))
        {
            $validator = Validator::make($request->all(),['id' => 'exists:clients','password' => 'required|min:6|confirmed']);
			
			if($validator->fails()){
				return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));    
			}else{
				$changePassword = DB::table('clients')->where('id',$request['id'])->update(['password' => bcrypt($request['password']),'updated_at' => Carbon::now()]);
				return response()->json(array('success'=> true));
			}
        }
		else{
			return response()->json(array('success'=> false,'errors' => ['oldPassword' => 'Wrong Password. Please try again.']));
		}
	}
	
	public function ticketDetails($id) {
		
		$ticket = Ticket::leftJoin ( DB::raw ( '(select topic_id,description from ticket_topics) ticket_topics' ), 'tickets.topic_id', "=", 'ticket_topics.topic_id' )
		->where ( 'id', $id )->where('sender_id',Auth::guard('user')->user()->id)
		->leftJoin ( DB::raw ( '(select agent_id, first_name as assign_FN, last_name as assign_LN
				from admin_profiles) admin_profiles' ), 'tickets.assigned_support', '=', 'admin_profiles.agent_id' )
				->leftJoin ( DB::raw ( '(select agent_id, first_name as close_FN, last_name as close_LN
				from admin_profiles) admin_profiles2' ), 'tickets.assigned_support', '=', 'admin_profiles2.agent_id' )
		->first ();
		
		if($ticket == null){
			abort(404);
		}
		
		
		
		session ( [
				'subject' => $ticket->subject,
				'department' => $ticket->department,
				'date_sent' => $ticket->created_at,
				'sender' => $ticket->sender_id,
				'date_modified' => $ticket->updated_at,
				'summary' => $ticket->summary,
				'topic_id' => $ticket->topic_id,
				'topic' => $ticket->description,
				'id' => $ticket->id,
				'assigned_support' => $ticket->assign_FN . ' ' . $ticket->assign_LN,
				'status' => $ticket->ticket_status,
				'priority' => $ticket->priority_level,
				'closed_by' => $ticket->close_FN . ' ' . $ticket->close_LN,
				'closing_report' => $ticket->closing_report
		] );
		
			$first = AProfile::select ( 'agent_id as id', 'first_name', 'last_name' );
			$second = CProfile::select ( 'client_id as id', 'first_name', 'last_name' )->union ( $first );
			
			$messages = TicketMessages::where('ticket_id',$id)
			->leftJoin ( DB::raw ( "({$second->toSql()}) as names" ), function ($join) {
			$join->on ( 'sender', '=', 'names.id' );
		} )->get();
		
			return view('tickets.viewTicketDetails',['messages'=> $messages ]);
		
	}
	
	public function suggestTopic(Request $request){
		$validator = Validator::make ( $request->all (), [
				'topic' => 'required|min:5|max:30|unique:ticket_topics,description',
		] );
		
		if ($validator->fails ()) {
			return response ()->json ( array (
					'success' => false,
					'errors' => $validator->getMessageBag ()->toArray ()
			) );
		} else {
				
			$user = TicketTopics::create ( [
					'description' => $request ['topic'],
					'status' => 0,
					'date_created' => Carbon::now (),
					'date_updated' => Carbon::now ()
			] );
			
			$topics = TicketTopics::where('description',$request['topic'])->first();
			return response ()->json ( array (
					'success' => true,
					'response' => $topics
			) );
		}
	}
	
	public function ticketReply(Request $request){
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
			$message = new TicketMessages;
			$message->ticket_id = $request ['ticket_id'];
			$message->message = $request ['message'];
			$message->sender = Auth::guard('user')->user()->id;
			$message->save();
				
			return response ()->json ( array (
					'success' => true
			) );
		}
	}
}

