<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Admin</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">
		<link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
		<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
		<script src="/js/plugins/chartJs/Chart.min.js"></script>

		<style>
			.modal {
				text-align: center;
			}

			@media screen and (min-width: 768px) {
				.modal:before {
					display: inline-block;
					vertical-align: middle;
					content: " ";
					height: 100%;
				}
			}

			.modal-dialog {
				display: inline-block;
				text-align: left;
				vertical-align: middle;
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
							<a href="/admin" class="navbar-brand">RS Tickets</a>
						</div>
						<div class="navbar-collapse collapse" id="navbar">
							<ul class="nav navbar-nav">
								<li class="active">
									<a> Hello, {{ Auth::guard('admin')->user()->adminProfile ? Auth::guard('admin')->user()->adminProfile->first_name.' '.Auth::guard('admin')->user()->adminProfile->last_name : '' }}!</a>
								</li>
								<li class="dropdown">
									<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Tickets<span class="caret"></span></a>
									<ul role="menu" class="dropdown-menu">
										<li>
											<a href="/admin/createTicket">Create Ticket</a>
										</li>
										<li>
											<a href="#">Add new topic</a>
										</li>
										<li>
											<a href="#">Ticket Status</a>
										</li>
									</ul>
								</li>

								<li class="dropdown">
									<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Agents <span class="caret"></span></a>
									<ul role="menu" class="dropdown-menu">
										<li>
											<a href="">My Agents</a>
										</li>
										<li>
											<a href="/admin/createAgent">Create New Agent/Admin</a>
										</li>
									</ul>
								</li>
								<li class="dropdown">
									<a href="#"> Clients </a>
								</li>
								<li class="dropdown">
									<a href="#"> Restrictions </a>
								</li>

							</ul>
							<ul class="nav navbar-top-links navbar-right">
								<li>
									<a href="#" class="adminEmail">{{ Auth::guard('admin')->user()->email}}</a>
								</li>
								<li>
									<a href="/admin/logout"> <span class="glyphicon glyphicon-log-out"></span>&nbsp;Log out </a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
				<div class="wrapper wrapper-content">
					<div class="container">
						<div class="row">
							@section('body')
							@show
						</div>
					</div>
				</div>

				<div class="footer">
					<div class="pull-right">
						&copy; 2014-2015
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

		<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>

		<!-- Flot -->
		<script src="/js/plugins/flot/jquery.flot.js"></script>
		<script src="/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
		<script src="/js/plugins/flot/jquery.flot.resize.js"></script>

		<!-- ChartJS-->
		<script src="/js/plugins/chartJs/Chart.min.js"></script>

		<!-- Peity -->
		<script src="/js/plugins/peity/jquery.peity.min.js"></script>
		<!-- Peity demo -->
		<script src="/js/demo/peity-demo.js"></script>

		<script>
			$(document).ready(function() {

				var doughnutData = [{
					value : 300,
					color : "#a3e1d4",
					highlight : "#1ab394",
					label : "App"
				}, {
					value : 50,
					color : "#dedede",
					highlight : "#1ab394",
					label : "Software"
				}, {
					value : 100,
					color : "#A4CEE8",
					highlight : "#1ab394",
					label : "Laptop"
				}];
				var doughnutOptions = {
					segmentShowStroke : true,
					segmentStrokeColor : "#fff",
					segmentStrokeWidth : 2,
					percentageInnerCutout : 45, // This is 0 for Pie charts
					animationSteps : 100,
					animationEasing : "easeOutBounce",
					animateRotate : true,
					animateScale : false

				};

				var ctx = document.getElementById("doughnutChart").getContext("2d");
				var DoughnutChart = new Chart(ctx).Doughnut(doughnutData, doughnutOptions);
			});
		</script>

		<script type="text/javascript">
			$('button.add-account').click(function(e) {
				e.preventDefault();
				$.ajax({
					type : "POST",
					url : "/checkEmail",
					data : $('.agentForm').serialize(),

				}).done(function(data) {
					if (data.response != "") {
						var msg = "";
						if (data.response != "") {
							$.each(data.errors, function(k, v) {
								msg = v + "\n" + msg;
							});
						}
						if (data.errors['email']) {
							$('div.email').addClass('has-error');
						}
						if (data.errors['firstname']) {
							$('div.fname').addClass('has-error');
						}
						if (data.errors['lastname']) {
							$('div.lname').addClass('has-error');
						}
						if (data.errors['user_type']) {
							$('div.usertype').addClass('has-error');
						}
						swal({
							type : 'warning',
							title : 'Oops...',
							text : msg,
						});
					} else {
						//Validation Success Tell user to input his/her password to continue/confirm adding

						validateSuccess();
					}

				});
			});

			function validateSuccess() {
				swal({
					title : "Create New Agent",
					text : "To continue, Please enter your password:",
					type : "input",
					inputType : "password",
					showCancelButton : true,
					closeOnConfirm : false
				}, function(inputValue) {
					if (inputValue != "") {
						$.ajax({
							type : 'get',
							url : '/checkPassword' + "/" + inputValue,
						}).done(function(data) {
							if (data == "true") {
								swal('Success', 'New user has been added', 'success');
								addNew();
							} else {
								swal.showInputError("Wrong Password");
								return false;
							}
						});
					} else {
						swal.showInputError("You need to type in your password in order to do this!");
						return false;
					}
				});
			};
			function addNew() {
				$.ajax({
					type : "POST",
					url : "/admin/register",
					data : $('.agentForm').serialize(),
				}).done(function() {
					sendActivation();
				});

			};
			function sendActivation() {
				$.ajax({
					type : "POST",
					url : "/admin/sendActivate",
					data : $('.agentForm').serialize(),
				})
			};

			/// Create Ticket
			$('button.create-ticket').click(function(e) {
				e.preventDefault
				$.ajax({
					type : "POST",
					url : "/admin/createTicket",
					data : $('.createTicket').serialize(),
				}).done(function(data) {
					var msg = "";
					if (data.response != "") {
						$.each(data.errors, function(k, v) {
							msg = v + "\n" + msg;
						});

						if (data.errors['Topic']) {
							$('div.topic').addClass('has-error');
						}
						if (data.errors['Subject']) {
							$('div.subject').addClass('has-error');
						}
						if (data.errors['Summary']) {
							$('div.summary').addClass('has-error');
						}

						swal("Oops...", msg, "warning");
					} else {
						swal("Succes!", "Your ticket has been created.", "success");
					}
				});
			});
		</script>

	</body>

</html>
