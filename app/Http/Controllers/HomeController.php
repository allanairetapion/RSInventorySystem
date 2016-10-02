<?php

namespace App\Http\Controllers;
use Response;
use Validator;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
class HomeController extends Controller {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this -> middleware('web');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return view('home');
	}

	public function search() {
		return view('tickets.search');
	}

	public function postSearch(Request $request) {
		$term = Input::get('term');
		$data = DB::table("clients") -> select('department') -> where('department', 'LIKE','%' .$term . '%')->distinct() -> get();
		$return_array = array();
		foreach ($data as $v) {
			$return_array[] =['value' => $v -> department];
		}
		return $return_array;
	}
	
	public function checkNew(){
		$data = DB::table('tickets')->orderBy('created_at','desc')->first();
		if($data->ticket_status == 'New')
		return Response::json("true");
	}
	
	public function uniqueId(Request $request){
		$term = Input::get('term');
		$data = DB::table("items") -> select('unique_id') -> where('unique_id', 'LIKE','%' .$term . '%')->distinct() -> get();
		$return_array = array();
		foreach ($data as $v) {
			$return_array[] = ['value' => $v->unique_id];
		}
		return $return_array;
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
	
	
	public function dropzone()
	
	{
	
		return view('dropzone-view');
	
	}
	
	
	/**
	
	* Image Upload Code
	
	*
	
	* @return void
	
	*/
	
	public function dropzoneStore(Request $request)
	
	{
	
		$image = $request->file('file');
		foreach ($image as $file){
		$imageName = time().$file->getClientOriginalName();
	
		$file->move(public_path('images'),$imageName);
		}
		return response()->json(['success'=>$imageName]);
	
	}

}
