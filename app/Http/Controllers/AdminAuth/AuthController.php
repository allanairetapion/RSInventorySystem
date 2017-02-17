<?php

namespace App\Http\Controllers\AdminAuth;
use App\AdminProfile;
use App\Admin as Admins;
use App\User as Clients;
use DB;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
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
	protected $guard ='admin';
    protected $redirectTo = '/admin/index';
    protected $loginPath = '/admin/login';
	protected $redirectAfterLogout = '/admin/login';
	protected $loginView ="/admin/login";
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
	public function showLoginForm()
	{
		if(Auth::guard ( 'admin' )->check ()) {
			return redirect('/admin/index');
		}
		else if(Auth::guard ( 'inventory' )->check ()){
			if(Auth::guard('inventory')->user()->user_type == "admin"){
				Auth::guard ( 'admin' )->loginUsingId ( Auth::guard ( 'inventory' )->user ()->id);
				return redirect('/admin/index');
			}
			
		}
		else{
				return view('tickets.admin.login');
		}
	}
	public function showRegistrationForm()
	{
		return view('tickets.admin.register');
	} 
	
	public function login(Request $request)
    {
       
		
		$activate = Admins::select('email')->whereNull('password')->get();
		
		if($activate != null){
			
			$checkEmail = Admins::where('email',$request['email'])->first();
			
			if($checkEmail != null){
				if($checkEmail->password == null){
					Session::flash('message', "Your account is not activated.");
					return redirect('/admin/login')->withInput();
				}
				else{
					$this->validateLogin($request);
				}
			}
			else{
				Session::flash('message', "Email does'nt exist in our records.");
				return redirect('/admin/login')->withInput();
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
    public function logout()
    {
    	Auth::guard($this->getGuard())->logout();
    	Auth::guard('inventory')->logout();
    	return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/');
    }
    
}
