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

	

Route::get('/dashboard', "UserController@showDashboard");


//inventory

Route::get('/inventory/index',"inventorySysController@showIndex");
	
Route::get('/inventory/login','inventorySysController@showLogin');
Route::get('/inventory/register','inventorySysController@showRegister');
Route::get('/inventory/signuptypage','inventorySysController@showCr8AccTyPage');
Route::get('/inventory/forgotpass','inventorySysController@showForgotpass');
Route::get('/inventory/verification','inventorySysController@showVerify');
Route::get('/inventory/newpassword','inventorySysController@showNewPass');
Route::get("/inventory/thankyoupage","inventorySysController@showNewPassTy");


Route::post("/inventory/login","inventorySysController@processLogin");


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
