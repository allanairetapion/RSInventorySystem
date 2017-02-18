<?php

namespace App\Http\Controllers\InputAuth;


use App\Admin as Admins;
use App\AdminProfile;
use App\User as Clients;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Session;
use Carbon\Carbon;

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
 protected function validator(array $data)
    {
        return Validator::make($data, [
        	'user_type' => 'required',
            'firstname' => 'required|min:3|max:255|alpha',
            'lastname' => 'required|min:2|max:255|alpha',
             'email' => 'required|email|max:255|unique:clients|unique:admin',
            
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
  

    protected function create(array $data)
    {
    	$ida;
    	do{
			$ida = mt_rand(0, 9999);
			$ida = Carbon::today()->year . $ida;						
		}
		while (Admins::where('id',$ida)->exists() && Clients::where('id',$ida)->exists());
		
		
        $user = Admins::create([
         	'id' => $ida,         	
            'user_type' => $data['user_type'],
            'email' => $data['email'],
            'status' => 'Not Activated',            
        ]);
		
		$admin_profiles = AdminProfile::create([
			'first_name' => $data['firstname'],
			'agent_id' => $ida,
			'last_name' => $data['lastname'],			
		]);
		
		return $user;
    }
    
	public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        $this->create($request->all());

        
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
