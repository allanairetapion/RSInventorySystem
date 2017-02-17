<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>Client</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">
		<link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
		
		<link href="/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
		<link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
		<link href="/css/plugins/summernote/summernote.css" rel="stylesheet">
		<link href="/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">
		<link href="/css/plugins/c3/c3.min.css" rel="stylesheet">
		<!-- Dropzone -->
		<link href="/css/plugins/dropzone/basic.css" rel="stylesheet">
    	<link href="/css/plugins/dropzone/dropzone.css" rel="stylesheet">
		
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
							<a href="/tickets" class="navbar-brand">Remote Staff</a>
						</div>
						<div class="navbar-collapse collapse" id="navbar">
							<ul class="nav navbar-nav">
								<li class="active">
									<a> Hello, {{ Auth::guard('user')->user()->clientProfile ? Auth::guard('user')->user()->clientProfile->first_name.' '.Auth::guard('user')->user()->clientProfile->last_name : '' }}! </a>
								</li>
								

							</ul>
							<ul class="nav navbar-top-links navbar-right">
								<li>
									<a href="#" class="adminEmail">{{ Auth::guard('user')->user()->email   }}</a>
								</li>
								<li class="dropdown">
									<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-cog"></i> <span class="caret"></span></a>
									<ul role="menu" class="dropdown-menu">
										<li>
											<a href="/tickets/editAccount"><i class="fa fa-edit"></i> Edit Account</a>
										</li>
										<li>
											<a href="/tickets/logout"> <i class="fa fa-sign-out"></i></span>&nbsp;Log out </a>
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

	
<!-- Mainly scripts -->
		<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
		<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<!-- Custom and plugin javascript -->
		<script src="/js/inspinia.js"></script>
		<script src="/js/plugins/pace/pace.min.js"></script>
		<!-- Sweet Alert -->
		<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
		<!-- DROPZONE -->
    	<script src="/js/plugins/dropzone/dropzone.js"></script>
		<!-- Ladda -->
		<script src="/js/plugins/ladda/spin.min.js"></script>
		<script src="/js/plugins/ladda/ladda.min.js"></script>
		<script src="/js/plugins/ladda/ladda.jquery.min.js"></script>
		<!-- d3 and c3 charts -->
		<script src="/js/plugins/d3/d3.min.js"></script>
		<script src="/js/plugins/c3/c3.min.js"></script>
		<script src="/js/plugins/summernote/summernote.min.js"></script>
		<script src="/js/ticketsClients.js"></script>
		@yield('scripts')
</body>

</html>
