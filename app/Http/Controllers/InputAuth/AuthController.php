<?php

namespace App\Http\Controllers\InputAuth;

use App\IsUser;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Mail;
use Hash;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Session;

class AuthController extends Controller {
	/*
	 * |--------------------------------------------------------------------------
	 * | Registration & Login Controller
	 * |--------------------------------------------------------------------------
	 * |
	 * | This controller handles the registration of new users, as well as the
	 * | authentication of existing users. By default, this controller uses
	 * | a simple trait to add these behaviors. Why don't you explore it?
	 * |
	 */
	use AuthenticatesAndRegistersUsers, ThrottlesLogins;
	
	/**
	 * Where to redirect users after login / registration.
	 *
	 * @var string
	 */
	protected $guard = 'inventory';
	protected $redirectTo = 'inventory/index';
	protected $loginPath = 'inventory/login';
	protected $redirectAfterLogout = '/inventory/login';
	protected $loginView = "/inventory/login";
	
	/**
	 * Create a new authentication controller instance.
	 *
	 * @return void
	 */
	
	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param array $data        	
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	protected function validator(array $data) {
		return Validator::make ( $data, [ 
				'email' => 'email|required|unique:is_users,email',
				'first_name' => 'required|string|min:2',
				'last_name' => 'required|string|min:2',
				'password' => 'required|alpha_num|between:6,100|confirmed',
				'password_confirmation' => 'required|alpha_num|between:6,100',
				'phone_number' => 'digits:11|required|unique:is_users,phone_number',
				'captcha' => 'required|captcha' 
		] );
	}
	
	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param array $data        	
	 * @return User
	 */
	protected function create(array $data) {
		$confirmation_code = str_random ( 30 );
		
		$user = IsUser::create ( [ 
				'first_name' => ucfirst ( $data ['first_name'] ),
				'last_name' => ucfirst ( $data ['last_name'] ),
				'email' => $data ['email'],
				'password' => bcrypt ( $data ['password'] ),
				'phone_number' => $data ['phone_number'],
				'date_created' => date ( 'Y-m-d H:i:s' ),
				'confirmation_code' => $confirmation_code 
		] );
		
		return $user;
		
		$is_users = IsUser::where ( 'email', '=', $data ['email'] )->first ();
		
		$mailData = array (
				'client' => 'cravendelossantos',
				'url' => 'http://laravel.com' 
		);
		
		// \Mail::send('inventory.newpassword', $data, function($message)
		// $url = URL::to('/inventory/verify/'.$confirmation_code);
		Mail::send ( 'inventory.verifyEmail', [ 
				'is_users' => $is_users 
		], function ($message) use($is_users) 

		{
			
			// $m->to($user->email, $user->name)->subject('Your Reminder!');
			$message->to ( $is_users->email, $is_users->name )->subject ( 'Verify your email address' );
			
			$message->from ( 'InventoryManagent@remoteStaff.com', 'RS Inventory Management' );
		} );
		
		// $is_users = IsUser::where('email', '=', $data['email']) -> first();
		return view ( "inventory.signuptypage" );
	}
	public function confirm($confirmation_code) {
		$is_users = IsUser::whereConfirmationCode ( $confirmation_code )->first ();
		
		$is_users->confirmed = 1;
		$is_users->confirmation_code = null;
		$is_users->save ();
		
		return view ( '/inventory/verified', array (
				"is_users" => $is_users 
		) );
	}
	
	public function login(Request $request) {
		 $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        $throttles = $this->isUsingThrottlesLoginsTrait();

        if ($throttles && $lockedOut = $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        $credentials = $this->getCredentials($request);

        if (Auth::guard($this->getGuard())->attempt($credentials, $request->has('remember'))) {
            return $this->handleUserWasAuthenticated($request, $throttles);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        if ($throttles && ! $lockedOut) {
            $this->incrementLoginAttempts($request);
        }

        return $this->sendFailedLoginResponse($request);
		}
	
	public function logout() {
		Auth::guard ( 'inventory' )->logout ();
		
		return redirect ( '/inventory/index' );
	}
	public function showVerified() {
		return view ( 'inventory.verified' );
	}
	public function resendVerLink($confirmation_code) {
		$is_users = IsUser::whereConfirmationCode ( $confirmation_code )->first ();
		
		Mail::send ( 'inventory.verifyEmail', [ 
				'is_users' => $is_users 
		], function ($message) use($is_users) 

		{
			// $m->to($user->email, $user->name)->subject('Your Reminder!');
			$message->to ( $is_users->email, $is_users->name )->subject ( 'Verify your email address' );
			$message->from ( 'InventoryManagent@remoteStaff.com', 'RS Inventory Management' );
		} );
		
		return view ( "inventory.signuptypage", array (
				"is_users" => $is_users 
		) );
	}
	public function resendVerLink2($confirmation_code) {
		$is_users = IsUser::whereConfirmationCode ( $confirmation_code )->first ();
		
		Mail::send ( 'inventory.verifyEmail', [ 
				'is_users' => $is_users 
		], function ($message) use($is_users) 

		{
			
			// $m->to($user->email, $user->name)->subject('Your Reminder!');
			$message->to ( $is_users->email, $is_users->name )->subject ( 'Verify your email address' );
			
			$message->from ( 'InventoryManagent@remoteStaff.com', 'RS Inventory Management' );
		} );
		
		return view ( "/inventory/notVerified", array (
				"is_users" => $is_users 
		) );
	}
	public function register(Request $request) {
		$validator = $this->validator ( $request->all () );
		
		if ($validator->fails ()) {
			$this->throwValidationException ( $request, $validator );
		}
		
		$this->create ( $request->all () );
		
		$is_users = IsUser::where ( 'email', '=', $request ['email'] )->first ();
		
		$mailData = array (
				'client' => 'cravendelossantos',
				'url' => 'http://laravel.com' 
		);
		
		// \Mail::send('inventory.newpassword', $data, function($message)
		// $url = URL::to('/inventory/verify/'.$confirmation_code);
		Mail::send ( 'inventory.verifyEmail', [ 
				'is_users' => $is_users 
		], function ($message) use($is_users) 

		{
			
			// $m->to($user->email, $user->name)->subject('Your Reminder!');
			$message->to ( $is_users->email, $is_users->name )->subject ( 'Verify your email address' );
			
			$message->from ( 'InventoryManagent@remoteStaff.com', 'RS Inventory Management' );
		} );
		
		$is_users = IsUser::where ( 'email', '=', $request ['email'] )->first ();
		
		return redirect ( '/inventory/signuptypage' )->with ( 'data', $is_users ['confirmation_code'] );
	}
	public function showLoginForm() {
		if (Auth::guard ( 'inventory' )->check ()) {
			return redirect ( 'inventory/index' );
		} 

		else {
			return view ( 'inventory.login' );
		}
	}
	public function showRegistrationForm() {
		if (Auth::guard ( 'inventory' )->check ()) {
			return redirect ( 'inventory/index' );
		} 

		else {
			return view ( 'inventory.register_account' );
		}
	}
	public function showRegisterty(Request $request) {
		$post_data = $request->all ();
		$is_users = IsUser::where ( 'email', '=', $request ['email'] )->first ();
		
		return view ( 'inventory.signuptypage' )->with ( 'data', $is_users ['confirmation_code'] );
	}
}
