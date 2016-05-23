<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

use App\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */
    
    use ResetsPasswords;
	protected $linkRequestView ="auth/passwords/email";
	protected $resetView ='auth/passwords/reset';
    protected $guard = "user";
	protected $broker = 'user';
    protected $redirectTo = 'tickets/changePasswordSuccess';
	protected $table = 'clients';
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }
	
	protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        
    }
	
	///For Activation :D
	public function ActivatesendResetLinkEmail(Request $request)
    {
    		
        $this->validate($request, ['dept' => 'required',
            'fname' => 'required|max:255',
            'lname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:clients',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',
            ]);

        $broker = $this->ActivategetBroker();

        $response = Password::broker($broker)->sendResetLink(
            $request->only('email'), $this->ActivateresetEmailBuilder()
        );
    }

	public function ActivategetBroker()
    {
    	$activateBroker = "activateUser";
        return $activateBroker;
    }
	
	protected function ActivateresetEmailBuilder()
    {
        return function (Message $message) {
            $message->subject('Activate your account');
        };
    }
	public function getActivateReset(Request $request, $token = null)
    {
        return $this->showActivateResetForm($request, $token);
    }
	
	public function showActivateResetForm(Request $request, $token = null)
    {
        if (is_null($token)) {
            return $this->getEmail();
        }
		$email = $request->input('email');
        return view('auth.passwords.activate')->with(compact('token', 'email'));        
    }

/// When Client clicks activation link on Email
	

    public function Activate(Request $request)
    {
        $this->validate(
            $request,[
            'token' => 'required',
            'email' => 'required|email',
        ]
        );

        $activateUser = User::where('email',$request['email'])->update(['status'=>'Activated']);
    }
	
	
	
}
