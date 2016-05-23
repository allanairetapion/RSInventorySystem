<?php

namespace App\Http\Controllers\Auth;
use App\ClientProfile;
use App\User;
use DB;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Session;


class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
	protected $guard ='user';
    protected $redirectTo = 'tickets/landingPage';
    protected $loginPath = '/login';
	protected $redirectAfterLogout = '/tickets/login';
	protected $loginView ="/tickets/login";
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
        	'dept' => 'required',
            'fname' => 'required|max:255',
            'lname' => 'required|min:2|max:255',
            'email' => 'required|email|max:255|unique:clients',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            'captcha' => 'required|captcha',
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
		
         $user = User::create([
            'department' => $data['dept'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'status' => 'Not Activated',
        ]);
		
		$client_profiles = ClientProfile::create([
			'first_name' => $data['fname'],
			'client_id' => $user->id,
			'last_name' => $data['lname'],
		]);
		
		return $user;
    }
	
	public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return redirect('tickets/signUp')
                        ->withErrors($validator)
                        ->withInput();
            
        }

       $this->create($request->all());
	   
        return redirect('tickets/signUpSuccess')->with($request->all());
    }
	
	
	public function showLoginForm(){
		if(Auth::guard('user')->check()){
			return redirect('tickets/landingPage');
		}
			return view("auth.login");
		
	}
	
	public function showRegistrationForm()
{
    return view('auth.register');
} 
	
	public function login(Request $request)
    {
        
		$activate = DB::table('clients')->where('status','Not Activated')->get();
		
		if($activate != null){
			$checkEmail = DB::table('clients')->where('email',$request['email'])->first();
			
			if ($checkEmail != Null){
				if($checkEmail->status != "Activated" ){
					Session::flash('message', "Your account is not activated.");
					return redirect('/tickets/login')->withInput();
				}
				else{
					$this->validateLogin($request);
				}
			}
			else{
				Session::flash('message', "Email does'nt exist in our records.");
				return redirect('/tickets/login')->withInput();
			}
			
		}
		
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
    
}
