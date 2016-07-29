<?php

namespace App\Http\Controllers;
use Response;
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

}
