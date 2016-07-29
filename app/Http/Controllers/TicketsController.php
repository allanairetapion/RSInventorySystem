<?php
namespace App\Http\Controllers;
use App\Tickets as Ticket;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Validator;
use App\Client as Client;
use App\ClientProfile as ClientProfile;
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
	 		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')->where('sender_id',Auth::guard('user')->user()->id)->take(11)->get();
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
            'summary' =>'required|min:10',            
        ]);

        if ($validator->fails()) {
            return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray())); 
          
        }
		else {
			
			
			$ida;
		$tickets;
		do{
			$ida = mt_rand(0, 99999999);					
		}
		while (Ticket::where('id')->exists());
			
		
			$user = Ticket::create([
			'id' => $ida,
            'sender' => Auth::guard('user')->user()->clientProfile ? Auth::guard('user')->user()->clientProfile->first_name.' '.Auth::guard('user')->user()->clientProfile->last_name : '',
            'sender_id' => Auth::guard('user')->user()->id,
            'topic_id' => $request['topic'],
            'subject' => $request['subject'],
            'summary' => $request['summary'],
            'ticket_status' => 'Open',            
            'department' =>Auth::guard('user')->user()->department,
        ]);
			
			return response()->json(['response' => '']); 
			
			
		}
	}
	public function showTicketStatus(){
		$tickets = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')->leftJoin('admin_profiles','tickets.assigned_support','=','admin_profiles.agent_id')->where('sender_id',Auth::guard('user')->user()->id)->paginate(15);
		
		return view('tickets.ticketStatus',['tickets' => $tickets]);
		
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
		$validator = Validator::make($request->all(),['id' => 'exists:clients']);
		
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
}

