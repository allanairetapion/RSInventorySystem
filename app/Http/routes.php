<?php

/*
 * |--------------------------------------------------------------------------
 * | Application Routes
 * |--------------------------------------------------------------------------
 * |
 * | Here is where you can register all of the routes for an application.
 * | It's a breeze. Simply tell Laravel the URIs it should respond to
 * | and give it the controller to call when that URI is requested.
 * |
 */
use App\Tickets as Tickets;

// General
Route::post ( '/checkEmail', 'HomeController@checkEmail' );
Route::get ( '/dropzone', 'HomeController@dropzone' );

Route::post ( 'dropzone/store', [ 
		'as' => 'dropzone.store',
		'uses' => 'HomeController@dropzoneStore' 
] );
// Login
Route::get ( '/inventory/login', 'InputAuth\AuthController@showLoginForm' );
Route::post ( '/inventory/login', 'InputAuth\AuthController@postLogin' );
Route::get ( '/inventory/logout', 'InputAuth\AuthController@getLogout' );
// Register
Route::post ( '/inventory/register', "InputAuth\AuthController@register" );
// Inventory Forgot Password
Route::get ( "/inventory/forgotPassword", 'InputAuth\PasswordController@getEmail' );
Route::post ( "/inventory/forgotPassword", 'InputAuth\PasswordController@postEmail' );
Route::get ( "/inventory/forgotPassword/Thankyou", "InputAuth\PasswordController@forgotpassTypage" );
// Inventory Change Password
Route::get ( "inventory/changePassword/{token}", 'InputAuth\PasswordController@showResetForm' );
Route::post ( "inventory/changePassword", 'InputAuth\PasswordController@postReset' );
Route::get ( "/inventory/thankyoupage", "inventoryController@showNewPassTy" );

Route::group ( [ 
		'middleware' => 'inventory' 
], function () {
	
	Route::get ( '/inventory/index', 'inventoryController@showIndex' );
	Route::get ( "/uniqueId", 'HomeController@uniqueId' );
	Route::get ( "/inventory/itemInfo", 'inventoryController@itemInfo' );
	Route::get ( "/inventory/borrowInfo", 'inventoryController@borrowInfo' );	
	Route::get ( "/inventory/addItems", 'inventoryController@showAddItem' );
	Route::get ( "/inventory/manageAccounts", 'inventoryController@showManageAccounts' );
	Route::get ( "/inventory/borrow", "inventoryController@showBorrow" );
	Route::get ( "/inventory/return", "inventoryController@showReturn" );
	Route::get ( "/inventory/detailed", "inventoryController@showDetailed" );
	Route::get ( "/inventory/issues", "inventoryController@showIssues" );
	Route::get ( "/inventory/broken", "inventoryController@showBroken" );
	Route::get ( "/inventory/deployed", "inventoryController@showDeployed" );
	Route::get ( "/inventory/summaryMonYrs", "inventoryController@showSummaryMonYrs" );
	Route::get ( "/inventory/summaryAll", "inventoryController@showSummaryAll" );
	Route::get ( "/inventory/detailed/search", "inventoryController@detailSearch" );
	Route::get ( "/inventory/detailed/advancedSearch", "inventoryController@detailAdvancedSearch" );
	Route::get ( "/inventory/borrow/search", "inventoryController@borrowSearch" );
	Route::get ( "/inventory/borrow/advancedSearch", "inventoryController@borrowAdvancedSearch" );
	Route::get ( "/inventory/deploy/search", "inventoryController@deploySearch" );
	Route::get ( "/inventory/deploy/advancedSearch", "inventoryController@deployAdvancedSearch" );
	Route::get ( "/inventory/return/search", "inventoryController@returnSearch" );
	Route::get ( "/inventory/return/advancedSearch", "inventoryController@returnAdvancedSearch" );
	Route::get ( "/inventory/issue/search", "inventoryController@issueSearch" );
	Route::get ( "/inventory/issue/advancedSearch", "inventoryController@issueAdvancedSearch" );
	Route::get ( "/inventory/broken/search", "inventoryController@brokenSearch" );
	Route::get ( "/inventory/broken/advancedSearch", "inventoryController@brokenAdvancedSearch" );
	Route::get ( "/inventory/agents", "inventoryController@showAgents" );
	Route::get ( "/inventory/createAgent", "inventoryController@showCreateAgent" );
	Route::get ( "/inventory/maintenance", "inventoryController@showMaintenance" );
	// Route::get("/skin-config.html", function(){return view("inventory.skin-config");} );
	Route::get ( "/inventory/itemTypeSummary", 'inventoryController@itemTypeSummary' );
	Route::get ( "/inventory/items/{id}", "inventoryController@viewItemDetails" );
	Route::get ( "/inventory/editAccount", "inventoryController@showEditAccount" );
	Route::get ( "/inventory/agents/{id}", "inventoryController@showAgentProfile" );
	Route::get ( "/inventory/addItem/search", "inventoryController@addItemSearch" );
	Route::get ( "/inventory/addItem/advancedSearch", "inventoryController@addItemAdvancedSearch" );
	Route::get("/inventory/detailed/itemLevel","inventoryController@itemLevel");
	Route::get("/inventory/detailed/stockItems","inventoryController@stockItems");
	Route::get("/inventory/maintenanceItems","inventoryController@getMaintenaceItems");
	Route::get("/inventory/maintenanceItem/{id}","inventoryController@maintenanceItem");
	Route::get("/inventory/maintenanceSched/{id}","inventoryController@viewMaintenanceDetail");
	Route::get("/inventory/maintenanceSchedules","inventoryController@maintenanceSchedules");
	Route::get ("/inventory/maintenanceDashboard","inventoryController@maintenanceDashboard");
	
	Route::post ( "/inventory/addItem", "inventoryController@addItem" );
	Route::post ( "/inventory/borrowItem", "inventoryController@borrowItem" );
	Route::post ( "/inventory/deployItem", "inventoryController@deployItem" );
	Route::post ( "/inventory/returnItem", "inventoryController@returnItem" );
	Route::post ( "/inventory/issueItem", "inventoryController@issueItem" );
	Route::post ( "/inventory/repairItem", "inventoryController@repairItem" );
	Route::post ( "/inventory/verifyPassword", 'inventoryController@checkPassword' );
	Route::post ( "/inventory/brokenItem", "inventoryController@brokenItem" );
	Route::post ( "/inventory/addSchedule", "inventoryController@addSchedule" );
	Route::post ( "/inventory/addActivity", "inventoryController@addActivity" );
	Route::post ( "/inventory/addItemPhoto", "inventoryController@addItemPhoto" );
	
	Route::put ( "/inventory/brokenMark", "inventoryController@updateBroken" );
	Route::put ( "/inventory/updateItemDetail", "inventoryController@updateItemDetail" );
	Route::put ( '/inventory/changePersonalInfo', 'inventoryController@changePersonalInfo' );
	Route::put ( '/inventory/changePassword', 'inventoryController@changePassword' );
	Route::put ( '/inventory/changeProfilePicture', 'inventoryController@changeProfilePicture' );
	Route::put ( '/inventory/updateItemDetails', 'inventoryController@updateItemDetails' );
	Route::put ('/inventory/updateMaintenanceSched','inventoryController@updateMaintenanceSchedule');
	
	Route::delete ( "/inventory/deleteItemPhoto", "inventoryController@deleteItemPhoto" );
} );

// Tickets
Route::get ( '/checkNew', 'HomeController@checkNew' );
Route::get ( '/search', 'HomeController@postSearch' );

// Tickets Client
// Client Registration Route

Route::post ( '/tickets/signUp', 'Auth\AuthController@register' );
Route::get ( '/tickets/signUpSuccess', 'Auth\AuthController@showSignUpSuccess' );
// Client Login Route
Route::get ( '/tickets/login', 'Auth\AuthController@showLoginForm' );
Route::post ( '/tickets/login', 'Auth\AuthController@postLogin' );
Route::get ( '/tickets/logout', 'Auth\AuthController@logout' );
// Client Forgot Password (get email)
Route::get ( "/tickets/forgotPassword", 'Auth\PasswordController@getEmail' );
Route::post ( "/tickets/forgotPassword", 'Auth\PasswordController@postEmail' );
// Client Change Password
Route::get ( "/tickets/changePassword/{token}", 'Auth\PasswordController@showResetForm' );
Route::post ( "/tickets/changePassword", 'Auth\PasswordController@postReset' );
Route::get ( "/tickets/changePasswordSuccess", "Auth\AuthController@showChangePasswordSuccess" );
// Client Activation
Route::post ( '/tickets/sendActivate', 'Auth\PasswordController@ActivatesendResetLinkEmail' );
Route::get ( '/tickets/activate/{token}', 'Auth\PasswordController@showActivateResetForm' );
Route::post ( '/tickets/activate', 'Auth\PasswordController@Activate' );

// Client Pages
Route::group ( [ 
		'middleware' => 'user' 
], function () {
	Route::get ( "/tickets/", "TicketsController@landingPage" );
	Route::get ( "/tickets/createTicket", 'TicketsController@showCreateTicket' );
	Route::get ( '/tickets/ticketStatus', 'TicketsController@showTicketStatus' );
	Route::get ( '/tickets/topIssue', 'TicketsController@topIssue' );
	Route::get ( '/tickets/editAccount', 'TicketsController@editAccount' );
	Route::get ( '/tickets/{id}', 'TicketsController@ticketDetails' );
	
	Route::post ( '/tickets/createTicket', 'TicketsController@createTicket' );
	Route::post ( '/tickets/suggestTopic', 'TicketsController@suggestTopic' );
	Route::post ( '/tickets/ticketReply', 'TicketsController@ticketReply' );
	
	Route::put ( '/tickets/changePersonalInfo', 'TicketsController@changePersonalInfo' );
	Route::put ( '/tickets/changePassword', 'TicketsController@changePassword' );
} );

// End Client

// Tickets Admin
// Admin Login Routes...
Route::get ( '/admin/login', 'AdminAuth\AuthController@showLoginForm' );
Route::post ( '/admin/login', 'AdminAuth\AuthController@postLogin' );
Route::get ( '/admin/logout', 'AdminAuth\AuthController@logout' );
// Admin register
Route::post ( '/admin/register', 'AdminAuth\AuthController@register' );
// Admin Forgot Password (get email)
Route::get ( "/admin/forgotPassword", 'AdminAuth\PasswordController@getEmail' );
Route::post ( "/admin/forgotPassword", 'AdminAuth\PasswordController@postEmail' );
// Admin Change Password
Route::get ( "/admin/changePassword/{token}", 'AdminAuth\PasswordController@showResetForm' );
Route::post ( "/admin/changePassword", 'AdminAuth\PasswordController@postReset' );
Route::get ( "/admin/changePasswordSuccess", "AdminAuth\PasswordController@showChangePasswordSuccess" );
// Admin Account Activation
Route::post ( '/admin/sendActivate', 'AdminAuth\PasswordController@ActivatesendResetLinkEmail' );
Route::get ( '/admin/activate/{token}', 'AdminAuth\PasswordController@showActivateResetForm' );
Route::post ( '/admin/activate', 'AdminAuth\PasswordController@Activate' );
Route::get ( '/admin/activateSuccess', 'AdminAuth\PasswordController@activateSuccess' );

// Admin Pages
Route::group ( [ 
		'middleware' => 'admin' 
], function () {
	
	Route::get ( '/admin/index', 'TicketsAdmin@index' );
	Route::get ( '/admin/createAgent', 'TicketsAdmin@createAgent' );
	Route::get ( '/admin/createTicket', 'TicketsAdmin@showCreateTicket' );
	Route::get ( '/admin/topics', 'TicketsAdmin@showTopics' );
	Route::get ( '/admin/clients/', 'TicketsAdmin@showClients' );
	Route::get ( '/admin/agents', 'TicketsAdmin@showAgents' );
	Route::get ( '/admin/restrictions', 'TicketsAdmin@showRestriction' );
	Route::get ( '/admin/ticketReport', 'TicketsAdmin@showTicketReport' );
	Route::get ( '/admin/tickets', 'TicketsAdmin@showTickets' );
	Route::get ( '/admin/tickets-Assigned', 'TicketsAdmin@showTicketsAssigned' );
	Route::get ( '/admin/tickets-Open', 'TicketsAdmin@showTicketsOpen' );
	Route::get ( '/admin/tickets-Pending', 'TicketsAdmin@showTicketsPending' );
	Route::get ( '/admin/tickets-Unresolved', 'TicketsAdmin@showTicketsUnresolved' );
	Route::get ( '/admin/tickets-Closed', 'TicketsAdmin@showTicketsClosed' );
	Route::get ( '/admin/tickets/{id}', 'TicketsAdmin@showTicketDetails' );
	Route::get ( '/admin/printTickets/{id}', 'TicketsAdmin@printTicketDetails' );
	Route::get ( '/admin/ticketReply', 'TicketsAdmin@showTicketReply' );
	Route::get ( '/admin/ticketReply/{id}', 'TicketsAdmin@showTicketReply' );
	Route::get ( '/admin/printTicketClosed', 'TicketsAdmin@printTicketClosed' );
	Route::get ( '/admin/topIssue', 'TicketsAdmin@topIssue' );
	Route::get ( '/admin/ticketSummary', 'TicketsAdmin@ticketSummary' );
	Route::get ( '/admin/topSupport', 'TicketsAdmin@topSupport' );
	Route::get ( '/admin/createClient', 'TicketsAdmin@showCreateClient' );
	Route::get ( '/admin/editAccount', 'TicketsAdmin@editAccount' );
	Route::get ( '/admin/topicInfo', 'TicketsAdmin@editTopicDetails' );
	Route::get ( '/admin/ticketCount', 'TicketsAdmin@ticketCount' );
	Route::get ( '/admin/ticketStatus', 'TicketsAdmin@ticketStatus' );
	Route::get ( '/admin/department', 'TicketsAdmin@showDepartment' );
	Route::get ( '/admin/ticketSearch', 'TicketsAdmin@ticketSearch' );
	Route::get ( '/admin/departmentInfo', 'TicketsAdmin@departmentInfo' );
	Route::get ( '/admin/ticketStatus/info', 'TicketsAdmin@ticketStatusInfo' );
	Route::get ( '/admin/topIssue/info', 'TicketsAdmin@topIssueInfo' );
	Route::get ( '/admin/agents/{id}', 'TicketsAdmin@agentProfile' );
	Route::get ( '/agents/ticketStats', 'TicketsAdmin@agentTicketStats' );
	
	Route::post ( '/admin/createTicket', 'TicketsAdmin@createTicket' );
	Route::post ( '/admin/addTopic', 'TicketsAdmin@addTopic' );
	Route::post ( '/admin/advancedSearch', 'TicketsAdmin@advancedSearch' );
	Route::post ( '/admin/ticketReply', 'TicketsAdmin@sendReply' );
	Route::post ( '/admin/createClient', 'Auth\AuthController@createClient' );
	Route::post ( '/admin/addDepartment', 'TicketsAdmin@addDepartment' );
	Route::post ( '/admin/verifyPassword', 'TicketsAdmin@checkPassword' );
	
	Route::put ( '/admin/updateSelection', 'TicketsAdmin@updateSelection' );
	Route::put ( '/admin/editTopic', 'TicketsAdmin@editTopic' );
	Route::put ( '/admin/editDepartment', 'TicketsAdmin@editDepartment' );
	Route::put ( '/admin/updateRestriction', 'TicketsAdmin@updateRestriction' );
	Route::put ( '/admin/ticketStatus', 'TicketsAdmin@changeTicketStatus' );
	Route::put ( '/admin/forwardTicket', 'TicketsAdmin@forwardTicket' );
	Route::put ( '/admin/assignSupport', 'TicketsAdmin@assignSupport' );
	Route::put ( '/admin/changeClientPassword', 'TicketsAdmin@changeClientPassword' );
	Route::put ( '/admin/changeClientStatus', 'TicketsAdmin@changeClientStatus' );
	Route::put ( '/admin/changeAgentUserType', 'TicketsAdmin@changeAgentUserType' );
	Route::put ( '/admin/closeTicket', 'TicketsAdmin@closeTicket' );
	Route::put ( '/admin/changePersonalInfo', 'TicketsAdmin@changePersonalInfo' );
	Route::put ( '/admin/changePassword', 'TicketsAdmin@changePassword' );
	Route::put ( '/admin/changeProfilePicture', 'TicketsAdmin@changeProfilePicture' );
	Route::put ( '/admin/updateDepartment', 'TicketsAdmin@updateDepartment' );
	
	Route::delete ( '/admin/deleteTopic', 'TicketsAdmin@deleteTopic' );
	Route::delete ( '/admin/deleteTicket', 'TicketsAdmin@deleteTicket' );
	Route::delete ( '/admin/clientDelete', 'TicketsAdmin@clientDelete' );
} );

Route::auth ();

Route::any ( '/captcha-test', function (Request $request) {
	if (Request::getMethod () == 'POST') {
		$rules = [ 
				'captcha' => 'required|captcha' 
		];
		$validator = Validator::make ( $request::all (), $rules );
		if ($validator->fails ()) {
			echo '<p style="color: #ff0000;">Incorrect!</p>';
		} else {
			echo '<p style="color: #00ff30;">Matched :)</p>';
		}
	}
	
	return captcha_src ();
} );
