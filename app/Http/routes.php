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
use App\Tickets as Tickets;

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

Route::get("/inventory/borrow","inventorySysController@showBorrow");
Route::get("/inventory/return","inventorySysController@showReturn");
Route::get("/inventory/detailed","inventorySysController@showDetailed");
Route::get("/inventory/issues","inventorySysController@showIssues");
Route::get("/inventory/broken","inventorySysController@showBroken");
Route::get("/inventory/summaryMonYrs","inventorySysController@showSummaryMonYrs");
Route::get("/inventory/summaryAll","inventorySysController@showSummaryAll");
	








//Tickets
Route::get('/checkNew','HomeController@checkNew');
Route::get('/search', 'HomeController@postSearch');

//Tickets Client
//Client Registration Route
Route::get('/tickets/signUp', 'Auth\AuthController@showRegistrationForm');
Route::post('/tickets/signUp', 'Auth\AuthController@register');
Route::get('/tickets/signUpSuccess','Auth\AuthController@showSignUpSuccess');
//Client Login Route
Route::get('/tickets/login', 'Auth\AuthController@showLoginForm');
Route::post('/tickets/login', 'Auth\AuthController@postLogin');
Route::get('/tickets/logout','Auth\AuthController@logout');
//Client Forgot Password (get email)
Route::get("/tickets/forgotPassword", 'Auth\PasswordController@getEmail');
Route::post("/tickets/forgotPassword",'Auth\PasswordController@postEmail');
//Client Change Password
Route::get("/tickets/changePassword/{token}",'Auth\PasswordController@getReset');
Route::post("/tickets/changePassword",'Auth\PasswordController@postReset');
Route::get("/tickets/changePasswordSuccess","Auth\AuthController@showChangePasswordSuccess");
//Client Activation
Route::post('/tickets/sendActivate','Auth\PasswordController@ActivatesendResetLinkEmail');
Route::get('/tickets/activate/{token}','Auth\PasswordController@showActivateResetForm');
Route::post('/tickets/activate','Auth\PasswordController@Activate');



//Client Pages
Route::group(['middleware' => 'user'], function () {
	Route::get("/tickets/landingPage","TicketsController@landingPage");
	Route::get("/tickets/createTicket",'TicketsController@showCreateTicket');
	Route::get('/tickets/ticketStatus','TicketsController@showTicketStatus');
	Route::get('/tickets/topIssue','TicketsController@topIssue');
	Route::get('/tickets/editAccount','TicketsController@editAccount');

	Route::post('/tickets/createTicket','TicketsController@createTicket');
	
	Route::put('/tickets/changePersonalInfo','TicketsController@changePersonalInfo');
	Route::put('/tickets/changePassword','TicketsController@changePassword');
});

//End Client

//Tickets Admin
//Admin Login Routes...
Route::get('/admin/login','AdminAuth\AuthController@showLoginForm');
Route::post('/admin/login','AdminAuth\AuthController@postLogin');
Route::get('/admin/logout','AdminAuth\AuthController@logout');
//Admin Registration Routes...
Route::get('/admin/register', 'AdminAuth\AuthController@showRegistrationForm');
Route::post('/admin/register', 'AdminAuth\AuthController@register');
//Admin Forgot Password (get email)
Route::get("/admin/forgotPassword", 'AdminAuth\PasswordController@getEmail');
Route::post("/admin/forgotPassword",'AdminAuth\PasswordController@postEmail');
//Admin Change Password
Route::get("/admin/changePassword/{token}",'AdminAuth\PasswordController@getReset');
Route::post("/admin/changePassword",'AdminAuth\PasswordController@postReset');
Route::get("/admin/changePasswordSuccess","AdminAuth\PasswordController@showChangePasswordSuccess");
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
	Route::get('/admin/topics','TicketsAdmin@showTopics');
	Route::get('/admin/clients/','TicketsAdmin@showClients');
	Route::get('/admin/agents','TicketsAdmin@showAgents');
	Route::get('/admin/restrictions','TicketsAdmin@showRestriction');
	Route::get('/admin/ticketReport','TicketsAdmin@showTicketStatus');
	Route::get('/admin/tickets','TicketsAdmin@showTickets');
	Route::get('/admin/tickets-Open','TicketsAdmin@showTicketsOpen');
	Route::get('/admin/tickets-Pending','TicketsAdmin@showTicketsPending');
	Route::get('/admin/tickets-Closed','TicketsAdmin@showTicketsClosed');
	Route::get('/admin/tickets/{id}','TicketsAdmin@showTicketDetails');
	Route::get('admin/ticketReply',function(){
		session(['email' => ""]);
		return view('tickets.admin.ticketReply');
	});
	Route::get('/admin/ticketReply/{id}','TicketsAdmin@showTicketReply');
	Route::get('/admin/printTicketClosed','TicketsAdmin@printTicketClosed');
	Route::get('/admin/topIssue','TicketsAdmin@topIssue');
	Route::get('/admin/ticketStat','TicketsAdmin@ticketStat');
	Route::get('/admin/topSupport','TicketsAdmin@topSupport');
	Route::get('/admin/createClient','TicketsAdmin@showCreateClient');
	Route::get('/admin/editAccount','TicketsAdmin@editAccount');
	
	Route::post('/admin/createTicket','TicketsAdmin@createTicket');	
	Route::post('/checkEmail','TicketsAdmin@checkEmail');
	Route::post('/admin/addTopic','TicketsAdmin@addTopic');		
	Route::post('/admin/advancedSearch','TicketsAdmin@advancedSearch');
	Route::post('/admin/ticketReply','TicketsAdmin@sendReply');
	
	Route::put('/admin/updateSelection','TicketsAdmin@updateSelection');
	Route::put('/admin/updateRestriction','TicketsAdmin@updateRestriction');
	Route::put('/admin/ticketStatus','TicketsAdmin@changeTicketStatus');
	Route::put('/admin/forwardTicket','TicketsAdmin@forwardTicket');
	Route::put('/admin/assignSupport','TicketsAdmin@assignSupport');
	Route::put('/admin/changeClientPassword','TicketsAdmin@changeClientPassword');
	Route::put('/admin/changeClientStatus','TicketsAdmin@changeClientStatus');
	Route::put('/admin/changeAgentUserType','TicketsAdmin@changeAgentUserType');
	Route::put('/admin/closeTicket','TicketsAdmin@closeTicket');
	Route::put('/admin/changePersonalInfo','TicketsAdmin@changePersonalInfo');
	Route::put('/admin/changePassword','TicketsAdmin@changePassword');
	
	Route::delete('/admin/deleteTopic','TicketsAdmin@deleteTopic');
	Route::delete('/admin/deleteTicket','TicketsAdmin@deleteTicket');
	Route::delete('/admin/deleteViewedTicket','TicketsAdmin@deleteViewedTicket');	

});

Route::auth();


Route::any('/captcha-test', function(Request $request)
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

        
       
       
        return captcha_src();
    });
