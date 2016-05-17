<?php

namespace App\Http\Controllers\AdminAuth;
use App\AdminProfile;
use App\Admin;
use Auth;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
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
    protected $redirectTo = '/admin';
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
            'fname' => 'required|max:255',
            'lname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admin',
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
		
         $user = Admin::create([
            'user_type' => $data['user_type'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
		
		$admin_profiles = AdminProfile::create([
			'first_name' => $data['fname'],
			'agent_id' => $user->id,
			'last_name' => $data['lname'],
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

        Auth::guard($this->getGuard())->login($this->create($request->all()));

        return redirect('/admin');
    }
	public function showLoginForm()
	{
		return view('tickets.admin.login');
	}
	public function showRegistrationForm()
	{
		return view('tickets.admin.register');
	}  
}
