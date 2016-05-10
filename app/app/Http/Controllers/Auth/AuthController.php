<?php

namespace App\Http\Controllers\Auth;
use App\ClientProfile;
use App\User;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

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
	protected $table = 'clients';
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = 'tickets/signUpSuccess';
    protected $loginPath = '/login';
	protected $redirectAfterLogout = '/tickets/login';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
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
            'lname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:clients',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
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
            'department_id' => $data['dept'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
		
		$client_profiles = ClientProfile::create([
			'first_name' => $data['fname'],
			'client_id' => $user->id,
			'last_name' => $data['lname'],
		]);
		
		return $user;
    }
	
	public function showLoginForm(){
		if (Auth::check()){
	 	     return view("tickets.landingPage");
		}
		else{
			return view("tickets.login");
		}
	}
	
}
