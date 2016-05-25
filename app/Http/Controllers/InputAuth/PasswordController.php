<?php

namespace App\Http\Controllers\InputAuth;
use App\IsUser as IsUser;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

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
	protected $linkRequestView ="inventoryAuth/passwords/email";
	protected $resetView ='inventoryAuth/passwords/reset';
    protected $guard = "inventory";
	protected $broker = 'inventory';
    protected $redirectTo = '/inventory/thankyoupage';
	protected $table = 'is_users';
    /**
     * Create a new password controller instance.
     *
     * @return void
     */	
    public function __construct()
    {
        $this->middleware('web');
    }



			
	  protected function getResetValidationRules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password'=>'required|alpha_num|between:6,100|confirmed',
        ];
    }
	
	protected function getSendResetLinkEmailSuccessResponse($response)
    {
        	 
        return redirect('/inventory/Thankyou');
    }

    protected function getResetSuccessResponse($response)
    {
        return redirect($this->redirectPath('inventory/thankyoupage'));
    }
}