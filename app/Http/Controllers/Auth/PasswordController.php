<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use DB;
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
	
	public function showResetForm($token)
    {
    	$reset = DB::table('password_resets')->where('token',$token)->first();
		
		if($reset == null){
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
		
		switch ($response) {
            case Password::RESET_LINK_SENT:
                return "true";

            case Password::INVALID_USER:
            default:
                return "false";
        }
		
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
	
	
	public function showActivateResetForm($token)
    {
    	$activate = DB::table('password_resets')->where('token',$token)->first();
		if(is_null($activate)){
			abort(404);
		}
		$activateUser = User::where('email',$activate->email)->update(['status'=>'Activated']);
		
       	$delete = DB::table('password_resets')->where('token',$token)->delete();
        return view('auth.passwords.activate');        
    }
	
	protected function getResetFailureResponse(Request $request, $response)
    {
        return redirect()->back()
            ->withInput($request->only('email'))
            ->withErrors();
    }

/// When Client clicks activation link on Email
	

   
	
	
	
}
