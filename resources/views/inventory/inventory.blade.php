<!DOCTYPE html>
<html>
	<head>
	
		<!-- Metadata -->
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>@yield('title')</title>

		@section('css')
		<!-- CSS Files -->
		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">

		<!-- Toastr style -->
		<link href="/css/plugins/toastr/toastr.min.css" rel="stylesheet">

		<!-- Gritter -->
		<link href="/js/plugins/gritter/jquery.gritter.css" rel="stylesheet">
		@show
	</head>

	<body>
		<div id="wrapper">

			<nav class="navbar-default navbar-static-side" role="navigation">
				<div class="sidebar-collapse">
					<ul class="nav metismenu" id="side-menu">
						@section('sidebar')

						<li class="nav-header">
							<div class="dropdown profile-element">

								<span> <img alt="profile_picture" class="img-circle" height="50px" width="50px" src="data:image/jpeg;base64,<?php echo base64_encode(Auth::guard('inventory') -> user() -> image); ?>"> </span>
								<a data-toggle="dropdown" class="dropdown-toggle" href="#"> <span class="clear"> <span class="block m-t-xs"> {{{ Auth::guard('inventory')->user()->first_name }}}
										{{{ Auth::guard('inventory')->user()->last_name }}} <strong class="font-bold"> </strong> </span> <span class="text-muted text-xs block"> {{{ Auth::guard('inventory')->user()->user_type }}} <b class="caret"></b></span> </span> </a>

								<ul class="dropdown-menu animated fadeInRight m-t-xs">
									<li>
										<a href="profile.html">Profile</a>
									</li>
									<li>
										<a href="contacts.html">Contacts</a>
									</li>
									<li>
										<a href="mailbox.html">Mailbox</a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="/inventory/logout">Logout</a>
									</li>
								</ul>
							</div>
							<div class="logo-element">
								RS
							</div>
						</li>
						<li>
							<a href="/inventory/index"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>

						</li>

						<li>
							<a href="/inventory/borrow"><i class="fa fa-list-alt"></i> <span class="nav-label">Borrow Form</span></a>
						</li>

						<li>
							<a href="/inventory/return"><i class="fa fa-list-alt"></i> <span class="nav-label">Return Form</span> </a>
						</li>

						<li>
							<a href="/inventory/detailed"><i class="fa fa-list-alt"></i> <span class="nav-label">Detailed Inventory Form</span></a>
						</li>

						<li>
							<a href="/inventory/issues"><i class="fa fa-picture-o"></i> <span class="nav-label">Items Have Issues </span></a>
						</li>

						<li>
							<a href="/inventory/broken"><i class="fa fa-magic"></i> <span class="nav-label">Broken Items Form </span></a>
						</li>

						<li>
							<a href="/inventory/summaryMonYrs"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Summary for Month and Year </span></a>
						</li>

						<li>
							<a href="/inventory/summaryAll"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Summary of All Items </span></a>
						</li>

						<li>
							<a href="/inventory/summaryAll"><i class="fa fa-shopping-cart"></i> <span class="nav-label">Schedule for Maintenance</span></a>
						</li>

						<li>
							<a href="package.html"><i class="fa fa-database"></i> <span class="nav-label">Ticketing</span></a>
						</li>
						
						<li>
							<a href="/inventory/addItems"><i class="fa fa-database"></i> <span class="nav-label">Add Items</span></a>
						</li>
					<li>
							<a href="/inventory/manageAccounts"><i class="fa fa-cog"></i> <span class="nav-label">Manage Accounts</span></a>
						</li>
					</ul>
					@show
					</ul>

				</div>
			</nav>

			<div id="page-wrapper" class="gray-bg dashbard-1">
				<div class="row border-bottom">
					<nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
						<div class="navbar-header">
							<a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>

							<!--
							<form class="navbar-form-custom" action="search_results.html">

							<input type="text" placeholder="{{{ Auth::guard('inventory')->user()->user_type }}} " class="form-control" readonly="readonly">

							</form>-->

						</div>
						<ul class="nav navbar-top-links navbar-right">
							<li>
								<span class="m-r-sm text-muted welcome-message">Remote Staff Inventory And Monitoring System</span>
							</li>
							<li class="dropdown">
								<!--
								<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#"> <i class="fa fa-envelope"></i> <span class="label label-warning">16</span> </a>
								-->
								<ul class="dropdown-menu dropdown-messages">
									<li>
										<div class="dropdown-messages-box">
											<a href="profile.html" class="pull-left"> <img alt="image" class="img-circle" src="img/a7.jpg"> </a>
											<div class="media-body">
												<small class="pull-right">46h ago</small>
												<strong>Mike Loreipsum</strong> started following <strong>Monica Smith</strong>.
												<br>
												<small class="text-muted">3 days ago at 7:58 pm - 10.06.2014</small>
											</div>
										</div>
									</li>
									<li class="divider"></li>
									<li>
										<div class="dropdown-messages-box">
											<a href="profile.html" class="pull-left"> <img alt="image" class="img-circle" src="img/a4.jpg"> </a>
											<div class="media-body ">
												<small class="pull-right text-navy">5h ago</small>
												<strong>Chris Johnatan Overtunk</strong> started following <strong>Monica Smith</strong>.
												<br>
												<small class="text-muted">Yesterday 1:21 pm - 11.06.2014</small>
											</div>
										</div>
									</li>
									<li class="divider"></li>
									<li>
										<div class="dropdown-messages-box">
											<a href="profile.html" class="pull-left"> <img alt="image" class="img-circle" src="img/profile.jpg"> </a>
											<div class="media-body ">
												<small class="pull-right">23h ago</small>
												<strong>Monica Smith</strong> love <strong>Kim Smith</strong>.
												<br>
												<small class="text-muted">2 days ago at 2:30 am - 11.06.2014</small>
											</div>
										</div>
									</li>
									<li class="divider"></li>
									<li>
										<div class="text-center link-block">
											<a href="mailbox.html"> <i class="fa fa-envelope"></i> <strong>Read All Messages</strong> </a>
										</div>
									</li>
								</ul>
							</li>
							<li class="dropdown">
								<!--
								<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#"> <i class="fa fa-bell"></i> <span class="label label-primary">8</span> </a>
								-->
								<ul class="dropdown-menu dropdown-alerts">
									<li>
										<a href="mailbox.html">
										<div>
											<i class="fa fa-envelope fa-fw"></i> You have 16 messages <span class="pull-right text-muted small">4 minutes ago</span>
										</div> </a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="profile.html">
										<div>
											<i class="fa fa-twitter fa-fw"></i> 3 New Followers <span class="pull-right text-muted small">12 minutes ago</span>
										</div> </a>
									</li>
									<li class="divider"></li>
									<li>
										<a href="grid_options.html">
										<div>
											<i class="fa fa-upload fa-fw"></i> Server Rebooted <span class="pull-right text-muted small">4 minutes ago</span>
										</div> </a>
									</li>
									<li class="divider"></li>
									<li>
										<div class="text-center link-block">
											<a href="notifications.html"> <strong>See All Alerts</strong> <i class="fa fa-angle-right"></i> </a>
										</div>
									</li>
									
								</ul>
							</li>

							<li>
								<a href="/inventory/logout"> <i class="fa fa-sign-out"></i> Log out </a>
							</li>
							<li>
								<!--
								<a class="right-sidebar-toggle"> <i class="fa fa-tasks"></i> </a>
								-->
							</li>
						</ul>

					</nav>
				</div>

				<div class="row  border-bottom white-bg dashboard-header">
					@yield('header-page')
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="wrapper wrapper-content">
							@yield('content')

						</div>
						<div class="footer">
							<div class="pull-right">
								RS Operations
							</div>
							<div>
								<strong>Copyright</strong> Remote Staff Inc. &copy; 2016
							</div>
						</div>
					</div>
				</div>

			</div>
			<div class="small-chat-box fadeInRight animated">

				<div class="heading" draggable="true">
					<small class="chat-date pull-right"> 02.19.2015 </small>
					Small chat
				</div>

				<div class="content">

					<div class="left">
						<div class="author-name">
							Monica Jackson <small class="chat-date"> 10:02 am </small>
						</div>
						<div class="chat-message active">
							Lorem Ipsum is simply dummy text input.
						</div>

					</div>
					<div class="right">
						<div class="author-name">
							Mick Smith
							<small class="chat-date"> 11:24 am </small>
						</div>
						<div class="chat-message">
							Lorem Ipsum is simpl.
						</div>
					</div>
					<div class="left">
						<div class="author-name">
							Alice Novak
							<small class="chat-date"> 08:45 pm </small>
						</div>
						<div class="chat-message active">
							Check this stock char.
						</div>
					</div>
					<div class="right">
						<div class="author-name">
							Anna Lamson
							<small class="chat-date"> 11:24 am </small>
						</div>
						<div class="chat-message">
							The standard chunk of Lorem Ipsum
						</div>
					</div>
					<div class="left">
						<div class="author-name">
							Mick Lane
							<small class="chat-date"> 08:45 pm </small>
						</div>
						<div class="chat-message active">
							I belive that. Lorem Ipsum is simply dummy text.
						</div>
					</div>

				</div>
				<div class="form-chat">
					<div class="input-group input-group-sm">
						<input type="text" class="form-control">
						<span class="input-group-btn">
							<button
							class="btn btn-primary" type="button">
								Send
							</button> </span>
					</div>
				</div>

			</div>
			<div id="small-chat">

				<span class="badge badge-warning pull-right">5</span>
				<a class="open-small-chat"> <i class="fa fa-comments"></i> </a>
			</div>
			<div id="right-sidebar">
				<div class="sidebar-container">

					<ul class="nav nav-tabs navs-3">

						<li class="active">
							<a data-toggle="tab" href="#tab-1"> Notes </a>
						</li>
						<li>
							<a data-toggle="tab" href="#tab-2"> Projects </a>
						</li>
						<li class="">
							<a data-toggle="tab" href="#tab-3"> <i class="fa fa-gear"></i> </a>
						</li>
					</ul>

					<div class="tab-content">

						<div id="tab-1" class="tab-pane active">

							<div class="sidebar-title">
								<h3><i class="fa fa-comments-o"></i> Latest Notes</h3>
								<small><i class="fa fa-tim"></i> You have 10 new message.</small>
							</div>

							<div>

								<div class="sidebar-message">
									<a href="#">
									<div class="pull-left text-center">
										<img alt="image" class="img-circle message-avatar" src="img/a1.jpg">

										<div class="m-t-xs">
											<i class="fa fa-star text-warning"></i>
											<i class="fa fa-star text-warning"></i>
										</div>
									</div>
									<div class="media-body">

										There are many variations of passages of Lorem Ipsum available.
										<br>
										<small class="text-muted">Today 4:21 pm</small>
									</div> </a>
								</div>
								<div class="sidebar-message">
									<a href="#">
									<div class="pull-left text-center">
										<img alt="image" class="img-circle message-avatar" src="img/a2.jpg">
									</div>
									<div class="media-body">
										The point of using Lorem Ipsum is that it has a more-or-less normal.
										<br>
										<small class="text-muted">Yesterday 2:45 pm</small>
									</div> </a>
								</div>
								<div class="sidebar-message">
									<a href="#">
									<div class="pull-left text-center">
										<img alt="image" class="img-circle message-avatar" src="img/a3.jpg">

										<div class="m-t-xs">
											<i class="fa fa-star text-warning"></i>
											<i class="fa fa-star text-warning"></i>
											<i class="fa fa-star text-warning"></i>
										</div>
									</div>
									<div class="media-body">
										Mevolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
										<br>
										<small class="text-muted">Yesterday 1:10 pm</small>
									</div> </a>
								</div>
								<div class="sidebar-message">
									<a href="#">
									<div class="pull-left text-center">
										<img alt="image" class="img-circle message-avatar" src="img/a4.jpg">
									</div>
									<div class="media-body">
										Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the
										<br>
										<small class="text-muted">Monday 8:37 pm</small>
									</div> </a>
								</div>
								<div class="sidebar-message">
									<a href="#">
									<div class="pull-left text-center">
										<img alt="image" class="img-circle message-avatar" src="img/a8.jpg">
									</div>
									<div class="media-body">

										All the Lorem Ipsum generators on the Internet tend to repeat.
										<br>
										<small class="text-muted">Today 4:21 pm</small>
									</div> </a>
								</div>
								<div class="sidebar-message">
									<a href="#">
									<div class="pull-left text-center">
										<img alt="image" class="img-circle message-avatar" src="img/a7.jpg">
									</div>
									<div class="media-body">
										Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32.
										<br>
										<small class="text-muted">Yesterday 2:45 pm</small>
									</div> </a>
								</div>
								<div class="sidebar-message">
									<a href="#">
									<div class="pull-left text-center">
										<img alt="image" class="img-circle message-avatar" src="img/a3.jpg">

										<div class="m-t-xs">
											<i class="fa fa-star text-warning"></i>
											<i class="fa fa-star text-warning"></i>
											<i class="fa fa-star text-warning"></i>
										</div>
									</div>
									<div class="media-body">
										The standard chunk of Lorem Ipsum used since the 1500s is reproduced below.
										<br>
										<small class="text-muted">Yesterday 1:10 pm</small>
									</div> </a>
								</div>
								<div class="sidebar-message">
									<a href="#">
									<div class="pull-left text-center">
										<img alt="image" class="img-circle message-avatar" src="img/a4.jpg">
									</div>
									<div class="media-body">
										Uncover many web sites still in their infancy. Various versions have.
										<br>
										<small class="text-muted">Monday 8:37 pm</small>
									</div> </a>
								</div>
							</div>

						</div>

						<div id="tab-2" class="tab-pane">

							<div class="sidebar-title">
								<h3><i class="fa fa-cube"></i> Latest projects</h3>
								<small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
							</div>

							<ul class="sidebar-list">
								<li>
									<a href="#">
									<div class="small pull-right m-t-xs">
										9 hours ago
									</div> <h4>Business valuation</h4> It is a long established fact that a reader will be distracted.
									<div class="small">
										Completion with: 22%
									</div>
									<div class="progress progress-mini">
										<div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
									</div>
									<div class="small text-muted m-t-xs">
										Project end: 4:00 pm - 12.06.2014
									</div> </a>
								</li>
								<li>
									<a href="#">
									<div class="small pull-right m-t-xs">
										9 hours ago
									</div> <h4>Contract with Company </h4> Many desktop publishing packages and web page editors.
									<div class="small">
										Completion with: 48%
									</div>
									<div class="progress progress-mini">
										<div style="width: 48%;" class="progress-bar"></div>
									</div> </a>
								</li>
								<li>
									<a href="#">
									<div class="small pull-right m-t-xs">
										9 hours ago
									</div> <h4>Meeting</h4> By the readable content of a page when looking at its layout.
									<div class="small">
										Completion with: 14%
									</div>
									<div class="progress progress-mini">
										<div style="width: 14%;" class="progress-bar progress-bar-info"></div>
									</div> </a>
								</li>
								<li>
									<a href="#"> <span class="label label-primary pull-right">NEW</span> <h4>The generated</h4> <!--<div class="small pull-right m-t-xs">9 hours ago</div>--> There are many variations of passages of Lorem Ipsum available.
									<div class="small">
										Completion with: 22%
									</div>
									<div class="small text-muted m-t-xs">
										Project end: 4:00 pm - 12.06.2014
									</div> </a>
								</li>
								<li>
									<a href="#">
									<div class="small pull-right m-t-xs">
										9 hours ago
									</div> <h4>Business valuation</h4> It is a long established fact that a reader will be distracted.
									<div class="small">
										Completion with: 22%
									</div>
									<div class="progress progress-mini">
										<div style="width: 22%;" class="progress-bar progress-bar-warning"></div>
									</div>
									<div class="small text-muted m-t-xs">
										Project end: 4:00 pm - 12.06.2014
									</div> </a>
								</li>
								<li>
									<a href="#">
									<div class="small pull-right m-t-xs">
										9 hours ago
									</div> <h4>Contract with Company </h4> Many desktop publishing packages and web page editors.
									<div class="small">
										Completion with: 48%
									</div>
									<div class="progress progress-mini">
										<div style="width: 48%;" class="progress-bar"></div>
									</div> </a>
								</li>
								<li>
									<a href="#">
									<div class="small pull-right m-t-xs">
										9 hours ago
									</div> <h4>Meeting</h4> By the readable content of a page when looking at its layout.
									<div class="small">
										Completion with: 14%
									</div>
									<div class="progress progress-mini">
										<div style="width: 14%;" class="progress-bar progress-bar-info"></div>
									</div> </a>
								</li>
								<li>
									<a href="#"> <span class="label label-primary pull-right">NEW</span> <h4>The generated</h4> <!--<div class="small pull-right m-t-xs">9 hours ago</div>--> There are many variations of passages of Lorem Ipsum available.
									<div class="small">
										Completion with: 22%
									</div>
									<div class="small text-muted m-t-xs">
										Project end: 4:00 pm - 12.06.2014
									</div> </a>
								</li>

							</ul>

						</div>

						<div id="tab-3" class="tab-pane">

							<div class="sidebar-title">
								<h3><i class="fa fa-gears"></i> Settings</h3>
								<small><i class="fa fa-tim"></i> You have 14 projects. 10 not completed.</small>
							</div>

							<div class="setings-item">
								<span> Show notifications </span>
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example">
										<label class="onoffswitch-label" for="example"> <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span> </label>
									</div>
								</div>
							</div>
							<div class="setings-item">
								<span> Disable Chat </span>
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" name="collapsemenu" checked class="onoffswitch-checkbox" id="example2">
										<label class="onoffswitch-label" for="example2"> <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span> </label>
									</div>
								</div>
							</div>
							<div class="setings-item">
								<span> Enable history </span>
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example3">
										<label class="onoffswitch-label" for="example3"> <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span> </label>
									</div>
								</div>
							</div>
							<div class="setings-item">
								<span> Show charts </span>
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example4">
										<label class="onoffswitch-label" for="example4"> <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span> </label>
									</div>
								</div>
							</div>
							<div class="setings-item">
								<span> Offline users </span>
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example5">
										<label class="onoffswitch-label" for="example5"> <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span> </label>
									</div>
								</div>
							</div>
							<div class="setings-item">
								<span> Global search </span>
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" checked name="collapsemenu" class="onoffswitch-checkbox" id="example6">
										<label class="onoffswitch-label" for="example6"> <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span> </label>
									</div>
								</div>
							</div>
							<div class="setings-item">
								<span> Update everyday </span>
								<div class="switch">
									<div class="onoffswitch">
										<input type="checkbox" name="collapsemenu" class="onoffswitch-checkbox" id="example7">
										<label class="onoffswitch-label" for="example7"> <span class="onoffswitch-inner"></span> <span class="onoffswitch-switch"></span> </label>
									</div>
								</div>
							</div>

							<div class="sidebar-content">
								<h4>Settings</h4>
								<div class="small">
									I belive that. Lorem Ipsum is simply dummy text of the printing and typesetting industry.
									And typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
									Over the years, sometimes by accident, sometimes on purpose (injected humour and the like).
								</div>
							</div>

						</div>
					</div>

				</div>

			</div>
		</div>

		@yield('scripts')

		<script>
$(document).ready(function() {

var sparklineCharts = function() {

$("#sparkline5").sparkline([1, 4], {
type: 'pie',
height: '140',
sliceColors: ['#1ab394', '#F5F5F5']
});

$("#sparkline6").sparkline([5, 3], {
type: 'pie',
height: '140',
sliceColors: ['#1ab394', '#F5F5F5']
});

$("#sparkline7").sparkline([2, 2], {
type: 'pie',
height: '140',
sliceColors: ['#ed5565', '#F5F5F5']
});

$("#sparkline8").sparkline([2, 3], {
type: 'pie',
height: '140',
sliceColors: ['#ed5565', '#F5F5F5']
});
};

};

var sparkResize;

$(window).resize(function(e) {
clearTimeout(sparkResize);
sparkResize = setTimeout(sparklineCharts, 500);
});

sparklineCharts();

});
		</script>

		<!-- Mainly scripts -->

		<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
		<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

		<!-- Flot -->
		<script src="/js/plugins/flot/jquery.flot.js"></script>
		<script src="/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
		<script src="/js/plugins/flot/jquery.flot.spline.js"></script>
		<script src="/js/plugins/flot/jquery.flot.resize.js"></script>
		<script src="/js/plugins/flot/jquery.flot.pie.js"></script>

		<!-- Peity -->
		<script src="/js/plugins/peity/jquery.peity.min.js"></script>
		<script src="/js/demo/peity-demo.js"></script>

		<!-- Custom and plugin javascript -->
		<script src="/js/inspinia.js"></script>
		<script src="/js/plugins/pace/pace.min.js"></script>

		<!-- jQuery UI -->
		<script src="/js/plugins/jquery-ui/jquery-ui.min.js"></script>

		<!-- GITTER -->
		<script src="/js/plugins/gritter/jquery.gritter.min.js"></script>

		<!-- Sparkline -->
		<script src="/js/plugins/sparkline/jquery.sparkline.min.js"></script>

		<!-- Sparkline demo data  -->
		<script src="/js/demo/sparkline-demo.js"></script>

		<!-- ChartJS-->
		<script src="/js/plugins/chartJs/Chart.min.js"></script>

		<!-- Toastr -->
		<script src="/js/plugins/toastr/toastr.min.js"></script>

		<!-- Mainly scripts -->
		<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/bootstrap.min.js"></script>

		<!-- Custom and plugin javascript -->
		<script src="/js/inspinia.js"></script>
		<script src="/js/plugins/pace/pace.min.js"></script>
		<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

		<!-- Chosen -->
		<script src="/js/plugins/chosen/chosen.jquery.js"></script>

		<!-- JSKnob -->
		<script src="/js/plugins/jsKnob/jquery.knob.js"></script>

		<!-- Input Mask-->
		<script src="/js/plugins/jasny/jasny-bootstrap.min.js"></script>

		<!-- Data picker -->
		<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>

		<!-- NouSlider -->
		<script src="/js/plugins/nouslider/jquery.nouislider.min.js"></script>

		<!-- Switchery -->
		<script src="/js/plugins/switchery/switchery.js"></script>

		<!-- IonRangeSlider -->
		<script src="/js/plugins/ionRangeSlider/ion.rangeSlider.min.js"></script>

		<!-- iCheck -->
		<script src="/js/plugins/iCheck/icheck.min.js"></script>

		<!-- MENU -->
		<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>

		<!-- Color picker -->
		<script src="/js/plugins/colorpicker/bootstrap-colorpicker.min.js"></script>

		<!-- Clock picker -->
		<script src="/js/plugins/clockpicker/clockpicker.js"></script>

		<!-- Image cropper -->
		<script src="/js/plugins/cropper/cropper.min.js"></script>

		<!-- Date range use moment.js same as full calendar plugin -->
		<script src="/js/plugins/fullcalendar/moment.min.js"></script>

		<!-- Date range picker -->
		<script src="/js/plugins/daterangepicker/daterangepicker.js"></script>

		<!-- Select2 -->
		<script src="/js/plugins/select2/select2.full.min.js"></script>

		<!-- TouchSpin -->
		<script src="/js/plugins/touchspin/jquery.bootstrap-touchspin.min.js"></script>

		<script>
			$('#data_1 .input-group.date').datepicker({
				todayBtn : "linked",
				keyboardNavigation : false,
				forceParse : false,
				calendarWeeks : true,
				autoclose : true
			});

		</script>

		@show

	</body>

</html>