<?php

namespace App\Http\Controllers\InputAuth;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Auth;
use DB;

class PasswordController extends Controller {
	/*
	 * |--------------------------------------------------------------------------
	 * | Password Reset Controller
	 * |--------------------------------------------------------------------------
	 * |
	 * | This controller is responsible for handling password reset requests
	 * | and uses a simple trait to include this behavior. You're free to
	 * | explore this trait and override any methods you wish to tweak.
	 * |
	 */
	
	use ResetsPasswords;
	protected $linkRequestView = "inventoryAuth/passwords/email";
	protected $resetView = 'inventoryAuth/passwords/reset';
	protected $guard = "inventory";
	protected $broker = 'inventory';
	protected $redirectTo = '/inventory/thankyoupage';
	protected $table = 'admin';
	/**
	 * Create a new password controller instance.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->middleware ( 'web' );
	}
	public function getEmail() {
		return view ( 'inventoryAuth.passwords.email' );
	}
	public function forgotpassTypage() {
		return view ( 'inventory.forgotpass_typage' );
	}
	public function showResetForm($token)
	{
		$reset = DB::table('password_resets')->where('token',$token)->first();
	
		if ($reset == null) {
			abort(404);
		}
	
		session(['resetEmail' => $reset->email,'token' => $token]);
	
	
		return view($this->resetView);
		 
	}
	
	
	
}
