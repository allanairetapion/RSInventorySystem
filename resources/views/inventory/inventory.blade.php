<!DOCTYPE html>
<html>
<head>

<!-- Metadata -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title')</title>


<link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
<link href="/css/animate.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet">
<link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
<link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
<link href="/css/plugins/summernote/summernote.css" rel="stylesheet">
<link rel="stylesheet" type="text/css"
	href="/css/plugins/jQueryUI/jquery-ui.css" />

<!-- c3 Charts -->
<link href="/css/plugins/c3/c3.min.css" rel="stylesheet">

<link href="/css/plugins/toastr/toastr.min.css" rel="stylesheet">

<link href="/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">


<!-- Mainly scripts -->

<style>
@media screen and (min-width: 768px) {
	.modal-lg {
		width: 950px;
	}
	.modal-sm {
		width: 300px;
	}
}
</style>
</head>

<body>
	<script src="/js/jquery-2.1.1.js"></script>
	<script src="/js/bootstrap.min.js"></script>
	<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
	<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
	<!-- Custom and plugin javascript -->
	<script src="/js/inspinia.js"></script>
	<script src="/js/plugins/pace/pace.min.js"></script>
	<!-- Toastr script -->
	<script src="/js/plugins/toastr/toastr.min.js"></script>
	<script type="text/javascript" src="/js/jquery-ui-1.10.4.min.js"></script>
	<!---Data Table -->
	<script src="/js/plugins/jeditable/jquery.jeditable.js"></script>
	<script src="/js/plugins/dataTables/datatables.min.js"></script>
	<!-- SUMMERNOTE -->
	<script src="/js/plugins/summernote/summernote.min.js"></script>

	<!-- Sweet Alert -->
	<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
	<!-- Input Mask -->
	<script src="/js/plugins/jasny/jasny-bootstrap.min.js"></script>
	<!-- Ladda -->
	<script src="/js/plugins/ladda/spin.min.js"></script>
	<script src="/js/plugins/ladda/ladda.min.js"></script>
	<script src="/js/plugins/ladda/ladda.jquery.min.js"></script>

	<!-- d3 and c3 charts -->
	<script src="/js/plugins/d3/d3.min.js"></script>
	<script src="/js/plugins/c3/c3.min.js"></script>
	<!--Ichecks-->
	<script src="/js/plugins/iCheck/icheck.min.js"></script>

	<!-- Data picker -->
	<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
	<script src="/js/inventory.js"></script>
	<div id="wrapper">

		<nav class="navbar-default navbar-static-side" role="navigation">
			<div class="sidebar-collapse">
				<ul class="nav" id="side-menu">
					@section('sidebar')

					<li class="nav-header">
						<div class="dropdown profile-element">

							<h2 class="text-navy font-bold">RSITS</h2>
							<a data-toggle="dropdown" class="dropdown-toggle" href="#"> <span
								class="clear"> <span class="block m-t-xs"> {{
										Auth::guard('inventory')->user()->adminProfile ?
										Auth::guard('inventory')->user()->adminProfile->first_name.'
										'.Auth::guard('inventory')->user()->adminProfile->last_name :
										'' }} <strong class="font-bold"> </strong>
								</span> <span class="text-muted text-xs block"> {{{
										Auth::guard('inventory')->user()->user_type }}} <b
										class="caret"></b>
								</span>
							</span>
							</a>

							<ul class="dropdown-menu animated fadeInRight m-t-xs">
								<li><a href="profile.html">Profile</a></li>
								<li><a href="contacts.html">Contacts</a></li>
								<li><a href="mailbox.html">Mailbox</a></li>
								<li class="divider"></li>
								<li><a href="/inventory/logout">Logout</a></li>
							</ul>
						</div>
						<div class="logo-element">RS</div>
					</li>
					@section('sidebarDashboard')
					<li><a href="/inventory/index"><i class="fa fa-th-large"></i> <span
							class="nav-label">Dashboard</span></a></li>
					@show
					<li><a href="#"><i class="fa fa-cube"></i> <span
							class="nav-label">Inventory</span> <span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="/inventory/addItems">Add Items</a></li>
							<li><a href="/inventory/borrow">Borrowed Items</a></li>
							<li><a href="/inventory/return">Returned Items </a></li>
							<li><a href="/inventory/issues"></i>Items with Issues</a></li>
							<li><a href="/inventory/broken">Broken Items </a></li>
						</ul>
					</li>
					
					<li>
						<a href="#"> <i class="fa fa-user"></i><span class="nav-label">Agents</span> <span class="fa arrow"></span></a>
						<ul class="nav nav-second-level">
							<li><a href="/inventory/addItems">Agents</a></li>
							<li><a href="/inventory/borrow">Add New User</a></li>
						</ul>
					</li>



					<li><a href="/inventory/detailed"><i class="fa fa-list-alt"></i> <span
							class="nav-label">Detailed Inventory Form</span></a></li>





					<li><a href="/inventory/summaryMonYrs"><i
							class="fa fa-shopping-cart"></i> <span class="nav-label">Summary
								for Month and Year </span></a></li>

					<li><a href="/inventory/summaryAll"><i class="fa fa-shopping-cart"></i>
							<span class="nav-label">Summary of All Items </span></a></li>


					<li class="special_link"><a target="_blank" href="/admin"><i
							class="fa fa-ticket"></i> <span class="nav-label">Ticketing</span></a>
					</li>



				</ul>
				@show
				</ul>

			</div>
		</nav>

		<div id="page-wrapper" class="gray-bg dashbard-1">
			<div class="row border-bottom">
				<nav class="navbar navbar-static-top" role="navigation"
					style="margin-bottom: 0">
					<div class="navbar-header">
						<a class="navbar-minimalize minimalize-styl-2 btn btn-primary "
							href="#"><i class="fa fa-bars"></i> </a>

						<!--
							<form class="navbar-form-custom" action="search_results.html">

							<input type="text" placeholder="{{{ Auth::guard('inventory')->user()->user_type }}} " class="form-control" readonly="readonly">

							</form>-->

					</div>
					<ul class="nav navbar-top-links navbar-right">
						<li><span class="m-r-sm text-muted welcome-message">Remote Staff
								Inventory And Monitoring System</span></li>
						
						</li>
							<li class="dropdown">
									<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-cog"></i> <span class="caret"></span></a>
									<ul role="menu" class="dropdown-menu">
										<li>
											<a href="/inventory/editAccount"><i class="fa fa-edit"></i> Edit Account</a>
										</li>
										<li>
											<a href="/inventory/logout"> <i class="fa fa-sign-out"></i></span> Log out </a>
										</li>
										
									</ul>
								</li>

					</ul>

				</nav>
			</div>

			<div class="row wrapper border-bottom white-bg page-heading">
				@yield('header-page')</div>


			<div class="wrapper wrapper-content">@yield('content')</div>
			<div class="footer">
				<div class="pull-right">RS Operations</div>
				<div>
					<strong>Copyright</strong> Remote Staff Inc. &copy; 2016
				</div>
			</div>




		</div>

	</div>





	@show

</body>

</html>