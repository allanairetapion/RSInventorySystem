<!DOCTYPE html>
<html>

	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Admin</title>

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
		<link rel="stylesheet" type="text/css" href="/css/plugins/jQueryUI/jquery-ui.css" />

		<!-- c3 Charts -->
		<link href="/css/plugins/c3/c3.min.css" rel="stylesheet">

		<link href="/css/plugins/toastr/toastr.min.css" rel="stylesheet">

		<link href="/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
		
		@section('scripts')
		<!-- Mainly scripts -->
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
	

		<script src="/js/ticketsAdmin.js"></script>

		@show
		@section('style')
		@show
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

	<body class="top-navigation">

		<div id="wrapper">
			<div id="page-wrapper" class="gray-bg">
				<div class="row border-bottom white-bg">
					<nav class="navbar navbar-static-top" role="navigation">
						<div class="navbar-header">
							<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed"type="button">
								<i class="fa fa-reorder"></i>
							</button>
							<a href="/admin" class="navbar-brand">Remote Staff</a>
						</div>
						<div class="navbar-collapse collapse" id="navbar">
							<ul class="nav navbar-nav">
								<li class="dropdown">
									<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Tickets<span class="caret"></span></a>
									<ul role="menu" class="dropdown-menu">
										<li>
											<a href="/admin/tickets">All Tickets</a>
										</li>
										<li>
											<a href="/admin/createTicket">Submit a Ticket</a>
										</li>
										@if (Auth::guard('admin')->user()->user_type == "agent")
										@if($restrictions[2]->agent == 1)
										<li>
											<a href="/admin/topics">Add new topic</a>
										</li>
										@endif
										@else
										<li>
											<a href="/admin/topics">Add new topic</a>
										</li>
										@endif
										<li>
											<a href="/admin/ticketReport">Ticket Report</a>
										</li>
									</ul>
								</li>
								@if(Auth::guard('admin')->user()->user_type == 'admin')
								<li class="dropdown">
									<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Agents <span class="caret"></span></a>
									<ul role="menu" class="dropdown-menu">									
										<li>
											<a href="/admin/agents"> Agents</a>
										</li>								
										<li>
											<a href="/admin/createAgent">Create New User</a>
										</li>
										
									</ul>
								</li>
								@else
								<li class="dropdown">
									<a href="/admin/agents"> Agents </a>
								</li>
								@endif

								<li class="dropdown">
									<a href="/admin/clients"> Clients </a>
								</li>
								@if(Auth::guard('admin')->user()->user_type == 'admin')
								<li class="dropdown">
									<a href="/admin/restrictions"> Restrictions </a>
								</li>
								@endif

							</ul>
							<ul class="nav navbar-top-links navbar-right">

								<li class="active">
									<a>{{ Auth::guard('admin')->user()->adminProfile ? Auth::guard('admin')->user()->adminProfile->first_name.' '.Auth::guard('admin')->user()->adminProfile->last_name : '' }}!</a>
								</li>
								<li>
									<a class="dropdown-toggle"  data-toggle="dropdown" href="#"> <i class="fa fa-fw fa-envelope"></i> <span class="label label-info">1</span></a>
									<ul class="dropdown-menu dropdown-messages">
										<li>
											<div class="dropdown-messages-box">
												<a href="profile.html" class="pull-left"> <img alt="image" class="img-circle" src="/img/a7.jpg"> </a>
												<div class="media-body">
													<small class="pull-right">46h ago</small>
													<strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>.
													<br>
													<small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
												</div>
											</div>
										<li class="divider"></li>
										<li>
											<div class="text-center link-block">
												<a href="/admin/tickets"> <i class="fa fa-envelope"></i> <strong>Read All Messages</strong> </a>
											</div>
										</li>
								</li>
							</ul>
							</li>
							<li class="dropdown">
									<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-cog"></i> <span class="caret"></span></a>
									<ul role="menu" class="dropdown-menu">
										<li>
											<a href="/admin/editAccount"><i class="fa fa-edit"></i> Edit Account</a>
										</li>
										<li>
											<a href="/admin/logout"> <i class="fa fa-sign-out"></i></span> Log out </a>
										</li>
										
									</ul>
								</li>
								
							</ul>
						</div>
					</nav>
				</div>
				<div class="wrapper wrapper-content">

					@section('body')
					@show

				</div>

				<div class="footer">
					<div class="pull-right">
						&copy; 2008-<?php echo date("Y"); ?>
					</div>
					<div>
						<strong>Copyright</strong> Remote Staff Inc.
					</div>
				</div>

			</div>
		</div>
		

	</body>

</html>

