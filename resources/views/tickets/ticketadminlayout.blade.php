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
<link rel="stylesheet" type="text/css"
	href="/css/plugins/jQueryUI/jquery-ui.css" />
<!-- FooTable -->
<link href="/css/plugins/footable/footable.core.css" rel="stylesheet">
<!-- c3 Charts -->
<link href="/css/plugins/c3/c3.min.css" rel="stylesheet">
<link href="/css/plugins/blueimp/css/blueimp-gallery.min.css"
	rel="stylesheet">

<!-- Dropzone -->
<link href="/css/plugins/dropzone/basic.css" rel="stylesheet">
<link href="/css/plugins/dropzone/dropzone.css" rel="stylesheet">

<link href="/css/plugins/toastr/toastr.min.css" rel="stylesheet">

<link href="/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">

<link href="/css/plugins/cropper/cropper.min.css" rel="stylesheet">

@section('scripts')
<!-- Mainly scripts -->
<script src="/js/jquery-2.1.1.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<!-- Custom and plugin javascript -->
<script src="/js/inspinia.js"></script>
<script src="/js/plugins/pace/pace.min.js"></script>
<!-- FooTable -->
<!-- blueimp gallery -->
<script src="/js/plugins/blueimp/jquery.blueimp-gallery.min.js"></script>
<script src="/js/plugins/footable/footable.all.min.js"></script>
<!-- Toastr script -->
<script src="/js/plugins/toastr/toastr.min.js"></script>
<script type="text/javascript" src="/js/jquery-ui-1.10.4.min.js"></script>
<!---Data Table -->
<script src="/js/plugins/jeditable/jquery.jeditable.js"></script>
<script src="/js/plugins/dataTables/datatables.min.js"></script>
<!-- SUMMERNOTE -->
<script src="/js/plugins/summernote/summernote.min.js"></script>
<!-- Image cropper -->
<script src="/js/plugins/cropper/cropper.min.js"></script>
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
<!--Ichecks-->
<script src="/js/plugins/iCheck/icheck.min.js"></script>

<!-- Data picker -->
<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>


<script src="/js/ticketsAdmin.js"></script>

@show @section('style') @show
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
						<button aria-controls="navbar" aria-expanded="false"
							data-target="#navbar" data-toggle="collapse"
							class="navbar-toggle collapsed" type="button">
							<i class="fa fa-reorder"></i>
						</button>
						<a href="/admin/index" class="navbar-brand">Remote Staff</a>
					</div>
					<div class="navbar-collapse collapse" id="navbar">
						<ul class="nav navbar-nav">
							<li class="dropdown"><a aria-expanded="false" role="button"
								href="#" class="dropdown-toggle" data-toggle="dropdown"> Tickets<span
									class="caret"></span></a>
								<ul role="menu" class="dropdown-menu">
									<li><a href="/admin/tickets">All Tickets</a></li>


									<li><a href="/admin/ticketReport">Ticket Report</a></li>
									<li class="divider" role="separator"></li>
									<li><a href="/admin/createTicket">Create Ticket</a></li>
								</ul></li>

							<li class="dropdown"><a href="/admin/agents"> Agents </a></li>


							<li class="dropdown"><a href="/admin/clients"> Clients </a></li>
							@if(Auth::guard('admin')->user()->user_type == 'admin')
							<li class="dropdown"><a aria-expanded="false" role="button"
								href="#" class="dropdown-toggle" data-toggle="dropdown">
									Settings<span class="caret"></span>
							</a>
								<ul role="menu" class="dropdown-menu">
									<li><a href="/admin/department">Department</a></li>
									<li><a href="/admin/restrictions">Restrictions</a></li>

									<li><a href="/admin/topics">Topics</a></li>

								</ul></li> @endif

						</ul>
						<ul class="nav navbar-top-links navbar-right">

							<li class="active"><a>{{
									Auth::guard('admin')->user()->adminProfile ?
									Auth::guard('admin')->user()->adminProfile->first_name.'
									'.Auth::guard('admin')->user()->adminProfile->last_name : ''
									}}!</a></li>


							<li class="dropdown"><a aria-expanded="false" role="button"
								href="#" class="dropdown-toggle" data-toggle="dropdown"> <i
									class="fa fa-cog"></i> <span class="caret"></span></a>
								<ul role="menu" class="dropdown-menu">
									<li><a href="/admin/editAccount"><i class="fa fa-edit"></i>
											Edit Account</a></li>
									<li><a href="/admin/logout"> <i class="fa fa-sign-out"></i></span>
											Log out
									</a></li>

								</ul></li>
							</li>


						</ul>
					</div>
				</nav>
			</div>
			<div class="wrapper wrapper-content">@section('body') @show</div>

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

