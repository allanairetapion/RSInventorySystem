<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use DB;
use App\Admin;
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
	protected $ActivateresetView = "/tickets/admin/passwords/activate";
	protected $linkRequestView ="/tickets/admin/passwords/email";
	protected $resetView ='/tickets/admin/passwords/reset';
    protected $guard ='admin';
    protected $redirectTo = '/admin/changePasswordSuccess';
	protected $table = 'admin';
	protected $broker = 'admin';
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

	public function showResetForm($token)
    {
    	$reset = DB::table('adminpassword_resets')->where('token',$token)->first();
		
        if ($reset == null) {
            abort(404);
        }

        session(['resetEmail' => $reset->email,'token' => $token]);

        
        return view($this->resetView);
       
    }
	
	
	///For Activation :D
	public function ActivatesendResetLinkEmail(Request $request)
    {
    		
        $this->validate($request, ['email' => 'required|email']);

        $broker = $this->ActivategetBroker();

        $response = Password::broker($broker)->sendResetLink(
            $request->only('email'), $this->ActivateresetEmailBuilder()
        );
    }

	public function ActivategetBroker()
    {
    	$activateBroker = "activateAdmin";
        return $activateBroker;
    }
	
	protected function ActivateresetEmailBuilder()
    {
        return function (Message $message) {
            $message->subject('Activate your account');
        };
    }
	
	
	public function showActivateResetForm($token = null)
    {
        $activate = DB::table('adminpassword_resets')->where('token',$token)->first();
		
		if(is_null($activate)){
			abort(404);
		}
		session(['activateEmail' => $activate->email,'token' => $token]);
		
        return view('tickets.admin.passwords.activate')->with(compact('token', 'email'));       
    }
	
	public function activate(Request $request)
    {
        $this->validate(
            $request,
            $this->getResetValidationRules(),
            $this->getResetValidationMessages(),
            $this->getResetValidationCustomAttributes()
        );
		
		
        $credentials = $request->only(
            'email', 'password', 'password_confirmation', 'token'
        );

        $broker = $this->getBroker();

        $response = Password::broker($broker)->reset($credentials, function ($user, $password) {
            $this->activateAccount($user, $password);
			
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect('admin/activateSuccess');

            default:
                return $this->getResetFailureResponse($request, $response);
        }
    }
	
	protected function activateAccount($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),            
        ])->save();				        
    }
	
	protected function resetEmailBuilder()
    {
        return function (Message $message) {
            $message->subject($this->getEmailSubject())->from('rsnoreply@remotestaff.com' , "Remote Staff");
        };
    }
	public function activateSuccess(){
		Auth::guard('admin')->logout();
		return view('tickets.admin.activateSuccess');
		
	}
	public function showChangePasswordSuccess (){
		Auth::guard('admin')->logout();
		return view('tickets.admin.changePasswordSuccess');
	}
	protected function getResetFailureResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors();
    }
	
}
