<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/


 Route::get('/inventory', 'inventorySysController@showInventory');
 
 
//Route::get('/', "UserController@showDashboard");

Route::get('/inventory/index', ['middleware' => 'inventory','uses' =>'inventorySysController@showIndex']);



/* Login */
Route::get('/inventory/login', 'InputAuth\AuthController@showLoginForm');
Route::post('/inventory/login', 'InputAuth\AuthController@postLogin');
Route::get('/inventory/logout', 'InputAuth\AuthController@getLogout');

/* Register */


Route::get('/inventory/register', 'InputAuth\AuthController@showRegistrationForm');
Route::get('/register', 'inventorySysController@refereshCapcha');
Route::post('/inventory/register', "InputAuth\AuthController@register");
Route::get('/inventory/signuptypage', 'InputAuth\AuthController@showRegisterty');


/* Verify */

Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'InputAuth\AuthController@confirm'
]);


Route::get('/inventory/verified',"InputAuth\AuthController@showVerified");

Route::get('/inventory/resend/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'InputAuth\AuthController@resendVerLink'
]);

Route::get('/inventory/resend2/{confirmationCode}', [
    'uses' => 'InputAuth\AuthController@resendVerLink2'
]);

/* Password Resets */

Route::get("/inventory/forgotPassword", 'InputAuth\PasswordController@getEmail');
Route::post("/inventory/forgotPassword",'InputAuth\PasswordController@postEmail');
Route::get("/inventory/forgotPassword/Thankyou", "InputAuth\PasswordController@forgotpassTypage");

Route::get("inventory/changePassword/{token}",'InputAuth\PasswordController@getReset');
Route::post("inventory/changePassword",'InputAuth\PasswordController@postReset');
Route::get("/inventory/thankyoupage", "inventorySysController@showNewPassTy");
Route::get("/inventory/change_pass", "inventorySysController@changePass");


/* Item input */
Route::get("/inventory/addItems", 'inventorySysController@showInputItem');

Route::get("/inventory/manageAccounts", 'inventorySysController@showManageAccounts');

Route::get("/inventory/borrow", 'inventorySysController@showBorrow');












//Tickets


//Tickets Client
//Client Registration Route
Route::get('tickets/signUp', 'Auth\AuthController@showRegistrationForm');
Route::post('tickets/signUp', 'Auth\AuthController@register');
Route::get('tickets/signUpSuccess','TicketsController@showSignUpSuccess');
//Client Login Route
Route::get('tickets/login', 'Auth\AuthController@showLoginForm');
Route::post('/tickets/login', 'Auth\AuthController@postLogin');
Route::get('tickets/logout','Auth\AuthController@logout');
//Client Forgot Password (get email)
Route::get("/tickets/forgotPassword", 'Auth\PasswordController@getEmail');
Route::post("/tickets/forgotPassword",'Auth\PasswordController@postEmail');
//Client Change Password
Route::get("tickets/changePassword/{token}",'Auth\PasswordController@getReset');
Route::post("tickets/changePassword",'Auth\PasswordController@postReset');
Route::get("tickets/changePasswordSuccess","TicketsController@showChangePasswordSuccess");
//Client Activation
Route::post('/tickets/sendActivate','Auth\PasswordController@ActivatesendResetLinkEmail');
Route::get('/tickets/activate/{token}','Auth\PasswordController@getActivateReset');
Route::post('/tickets/activate','Auth\PasswordController@Activate');



//Client Pages
Route::group(['middleware' => 'user'], function () {
	Route::get("/tickets/landingPage","TicketsController@landingPage");
	Route::get("/tickets/createTicket",'TicketsController@showCreateTicket');

	Route::post('/tickets/createTicket','TicketsController@createTicket');
});

//End Client

//Tickets Admin
//Admin Login Routes...
Route::get('/admin/login','AdminAuth\AuthController@showLoginForm');
Route::post('/admin/login','AdminAuth\AuthController@postLogin');
Route::get('/admin/logout','AdminAuth\AuthController@logout');
//Admin Registration Routes...
Route::get('admin/register', 'AdminAuth\AuthController@showRegistrationForm');
Route::post('admin/register', 'AdminAuth\AuthController@register');
//Admin Forgot Password (get email)
Route::get("/admin/forgotPassword", 'AdminAuth\PasswordController@getEmail');
Route::post("/admin/forgotPassword",'AdminAuth\PasswordController@postEmail');
//Admin Change Password
Route::get("admin/changePassword/{token}",'AdminAuth\PasswordController@getReset');
Route::post("admin/changePassword",'AdminAuth\PasswordController@postReset');
Route::get("admin/changePasswordSuccess","AdminAuth\PasswordController@showChangePasswordSuccess");
//Admin Account Activation
Route::post('/admin/sendActivate','AdminAuth\PasswordController@ActivatesendResetLinkEmail');
Route::get('/admin/activate/{token}','AdminAuth\PasswordController@getActivateReset');
Route::post('/admin/activate','AdminAuth\PasswordController@Activate');
Route::get('/admin/activateSuccess','AdminAuth\PasswordController@activateSuccess');



//Admin Pages
Route::group(['middleware' => 'admin'], function () {
	
	Route::get('/checkPassword/{password}','TicketsAdmin@checkPassword');
	Route::get('/admin', 'TicketsAdmin@index');
	Route::get('/admin/createAgent','TicketsAdmin@createAgent');
	Route::get('/admin/createTicket','TicketsAdmin@showCreateTicket');
	
	Route::post('/admin/createTicket','TicketsAdmin@createTicket');	
	Route::post('/checkEmail','TicketsAdmin@checkEmail');

});


// End Tickets
Route::get('/home', 'HomeController@index');

Route::auth();

Route::get('/home', 'HomeController@index');

Route::any('captcha-test', function(Request $request)
    {
        if (Request::getMethod() == 'POST')
        {
            $rules = ['captcha' => 'required|captcha'];
            $validator = Validator::make($request::all(), $rules);
            if ($validator->fails())
            {
                echo '<p style="color: #ff0000;">Incorrect!</p>';
            }
            else
            {
                echo '<p style="color: #00ff30;">Matched :)</p>';
            }
        }

        $form = '<form method="post" action="captcha-test">';
        $form .= '<input type="hidden" name="_token" value="' . csrf_token() . '">';
        $form .= '<p>' . captcha_img() . '</p>';
        $form .= '<p><input type="text" name="captcha"></p>';
        $form .= '<p><button type="submit" name="check">Check</button></p>';
        $form .= '</form>';
        return $form;
    });




Route::get('/home', 'HomeController@index');
