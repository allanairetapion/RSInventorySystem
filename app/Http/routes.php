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

Route::get('/', "TicketsController@landingPage");

Route::get('/InventorySys/index',"indexhtmlcontroller@showIndex");
	

Route::get('/dashboard', "UserController@showDashboard");
//Tickets
Route::get("/tickets/dashboard", "TicketsController@showDashboard");
Route::get("/tickets/welcome", "TicketsController@showWelcome");

Route::get("/tickets/forgotPassword", ['as' => 'auth.passwords.email', 'uses' => 'Auth\PasswordController@getEmail']);
Route::post("/tickets/forgotPassword", ['as' => 'auth.passwords.email', 'uses' => 'Auth\PasswordController@postEmail']);

Route::get("/tickets/landingPage", "TicketsController@landingPage");

Route::get("tickets/changePassword",['as' => 'auth.passwords.reset','uses' => 'Auth\PasswordController@getReset']);
Route::post("tickets/changePassword",['as' => 'auth.passwords.reset','uses' => 'Auth\PasswordController@postReset'   ]);

Route::get("tickets/changePasswordSuccess","TicketsController@showChangePasswordSuccess");


Route::get("tickets/signUpSuccess","TicketsController@showSignUpSuccess");



Route::get('tickets/signUp', ['as' => 'auth.register', 'uses' => 'Auth\AuthController@showRegistrationForm']);
Route::post('tickets/signUp', ['as' => 'auth.register', 'uses' => 'Auth\AuthController@register']);


Route::get('tickets/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@showLoginForm']);
Route::post('/tickets/login', ['as' => 'auth.login', 'uses' => 'Auth\AuthController@login']);
Route::get('tickets/logout', ['as' => 'auth.logout', 'uses' => 'Auth\AuthController@logout']);


Route::get("/tickets/landingPage","TicketsController@landingPage");

//Route::post('/tickets/forgotPassword', "TicketsController@processForgot");
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

