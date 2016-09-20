<?php

namespace App\Http\Controllers\Auth;
use App\ClientProfile;
use App\User as Users;
use App\Admin as Admins;
use DB;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Mail;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;

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
    protected $redirectTo = 'tickets/';
    protected $loginPath = '/login';
	protected $redirectAfterLogout = '/tickets/login';
	protected $loginView ="auth/login";
	protected $registerView = "auth/register";
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
        	'dept' => 'required|max:255',
            'first_name' => 'required|min:3|max:255|alpha',
            'last_name' => 'required|min:2|max:255|alpha',
            'email' => 'required|email|max:255|unique:clients|unique:admin',
            'password' => 'required|min:6|confirmed',            
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
		$ida;
    	do{
			$ida = mt_rand(0, 9999);
			$ida = Carbon::today()->year . $ida;						
		}
		while (Admins::where('id',$ida)->exists() && Clients::where('id',$ida)->exists());
		
		
         $user = Users::create([
         	'id' => $ida,
            'department' => $data['dept'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'status' => 'Not Activated',
            'date_registered' => Carbon::now(),
        ]);
		
		$client_profiles = ClientProfile::create([
			'first_name' => $data['first_name'],
			'client_id' => $ida,
			'last_name' => $data['last_name'],
			'date_registered' => Carbon::now(),
		]);
		
		$activate = DB::table('password_resets')->insert(['id' => $ida, 'email' => $data['email'],'token' => $data['_token']]);
		
		Mail::send('auth.emails.activate', ['user' => $data], function (\Illuminate\Mail\Message $m) use ($data) {
           

            $m->to($data['email'], $data['first_name'])->subject('Activate your account');
        });
	   
		return $user;
    }
	
	public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
           return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray())); 
            
        }else{
        	
       	$this->create($request->all());
		
		
        return response()->json(array('success'=> true)); 
        }

    }
	
	
	public function showLoginForm(){
		if(Auth::guard('user')->check()){
			return redirect('/tickets');
		}
			 return view($this->loginView);	
	}
	
	public function showRegistrationForm()
{
if (property_exists($this, 'registerView')) {
            return view($this->registerView);
        }
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

	public function showChangePasswordSuccess(){
	 	Auth::guard('user')->logout();
		return view("tickets.changePasswordSuccess");
	}	
	public function showSignUpSuccess(Request $request){
	   		Auth::guard('user')->logout();
			return view("tickets.signUpSuccess");	
	}
	
	public function createClient(Request $request){
		
		$validator= Validator::make($request->all (), [
				'dept' => 'required|max:255',
				'first_name' => 'required|min:3|max:255|alpha',
				'last_name' => 'required|min:2|max:255|alpha',
				'email' => 'required|email|max:255|unique:clients|unique:admin',
				'password' => 'required|min:6|confirmed',
		]);
		
		if ($validator->fails()) {
			return response()->json(array('success'=> false, 'errors' =>$validator->getMessageBag()->toArray()));
		
		}else{
			 
			$this->create($request->all());
		
		
			return response()->json(array('success'=> true));
		}
	}
    
}
