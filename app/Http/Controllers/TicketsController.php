<?php
namespace App\Http\Controllers;
use App\Tickets;
use App\Http\Controllers;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;
use Validator;
use App\Client as Client;
use App\ClientProfile as ClientProfile;
use app\user;
use Illuminate\Support\Facades\Mail;



class TicketsController extends Controller{

	public function __construct(){
        $this->middleware('user');
   }	 
	public function landingPage(Request $request){
	 	if(Auth::guard('user')->check()){
	 		$users = DB::table('ticket_topics')->where('status',1)->get();	
			return view("tickets.landingPage",['topics' => $users]);
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
			
			$user = Tickets::create([
            'sender' => Auth::guard('user')->user()->clientProfile ? Auth::guard('user')->user()->clientProfile->first_name.' '.Auth::guard('user')->user()->clientProfile->last_name : '',
            'sender_email' => Auth::guard('user')->user()->email,
            'topic_id' => $request['topic'],
            'subject' => $request['subject'],
            'summary' => $request['summary'],
            'ticket_status' => 'Open',
            'priority' => 'Medium',
            'department' =>Auth::guard('user')->user()->department,
        ]);
			
			return response()->json(['response' => '']); 
			
			
		}
	}
	public function showTicketStatus(){
		$user = DB::table('tickets')->leftJoin('ticket_topics','tickets.topic_id','=','ticket_topics.topic_id')->where('sender_email',Auth::guard('user')->user()->email)->get();
		
		return view('tickets.ticketStatus',['tickets' => $user]);
		
	}	
		
		 
}

?>