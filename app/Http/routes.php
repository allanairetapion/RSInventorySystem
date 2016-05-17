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
Route::get('tickets/signUp', 'Auth\AuthController@showRegistrationForm');
Route::post('tickets/signUp', 'Auth\AuthController@register');

Route::get('tickets/login', 'Auth\AuthController@showLoginForm');
Route::post('/tickets/login', 'Auth\AuthController@postLogin');
Route::get('tickets/logout','Auth\AuthController@logout');

Route::get("/tickets/forgotPassword", 'Auth\PasswordController@getEmail');
Route::post("/tickets/forgotPassword",'Auth\PasswordController@postEmail');

Route::get("tickets/changePassword/{token}",'Auth\PasswordController@getReset');
Route::post("tickets/changePassword",'Auth\PasswordController@postReset');

Route::get("tickets/changePasswordSuccess","TicketsController@showChangePasswordSuccess");


Route::get("tickets/signUpSuccess","TicketsController@showSignUpSuccess");

Route::get("/tickets/landingPage","TicketsController@landingPage");

Route::get("/tickets/landingPage",array('before' => 'auth', 'uses' =>"TicketsController@landingPage"));

//Tickets Admin
//Login Routes...
Route::get('/admin/login','AdminAuth\AuthController@showLoginForm');
Route::post('/admin/login','AdminAuth\AuthController@postLogin');
Route::get('/admin/logout','AdminAuth\AuthController@logout');
// Registration Routes...
Route::get('admin/register', 'AdminAuth\AuthController@showRegistrationForm');
Route::post('admin/register', 'AdminAuth\AuthController@register');

Route::get("/admin/forgotPassword", 'AdminAuth\PasswordController@getEmail');
Route::post("/admin/forgotPassword",'AdminAuth\PasswordController@postEmail');

Route::get("admin/changePassword/{token}",'AdminAuth\PasswordController@getReset');
Route::post("admin/changePassword",'AdminAuth\PasswordController@postReset');

Route::get("admin/changePasswordSuccess","TicketsAdmin@showChangePasswordSuccess");

Route::get('/admin', ['middleware' => 'admin','uses' =>'TicketsAdmin@index']);
	
Route::get('/admin/createAgent',['middleware' => 'admin', 'uses' => 'TicketsAdmin@createAgent']);


Route::get('/checkPassword/{password}',['middleware' => 'admin','uses' => 'TicketsAdmin@checkPassword']);


 
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
        $form .= '<input type="hidden" name="_token" value=">';
        $form .= '<p>' . captcha_img() . '</p>';
       
        $form .= '<p><button type="submit" name="check">Check</button></p>';
        $form .= '</form>';
        return $form;
    });




Route::get('/home', 'HomeController@index');
