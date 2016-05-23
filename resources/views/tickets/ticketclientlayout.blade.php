<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Client</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">
		<link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
		<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
		<script src="/js/plugins/chartJs/Chart.min.js"></script>

	</head>

	<body class="top-navigation">

		<div id="wrapper">
			<div id="page-wrapper" class="gray-bg">
				<div class="row border-bottom white-bg">
					<nav class="navbar navbar-static-top" role="navigation">
						<div class="navbar-header">
							<button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed"

							type="button">
								<i class="fa fa-reorder"></i>
							</button>
							<a href="/tickets/landingPage" class="navbar-brand">RS Tickets</a>
						</div>
						<div class="navbar-collapse collapse" id="navbar">
							<ul class="nav navbar-nav">
								<li class="active">
									<a> Hello, {{ Auth::guard('user')->user()->clientProfile ? Auth::guard('user')->user()->clientProfile->first_name.' '.Auth::guard('user')->user()->clientProfile->last_name : '' }}! </a>
								</li>
								<li>
									<a href="/tickets/createTicket"> Create Ticket </a>
								</li>
								<li>
									<a href="#"> Ticket Status <span class="label label-warning">7</span> </a>
								</li>

							</ul>
							<ul class="nav navbar-top-links navbar-right">
								<li>
									<a href="#" class="adminEmail">{{ Auth::guard('user')->user()->email   }}</a>
								</li>
								<li>
									<a href="/tickets/logout"> <span class="glyphicon glyphicon-log-out"></span>&nbsp;Log out </a>
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

	</body>

</html>

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

	/// Create Ticket
	$('button.create-ticket').click(function(e) {
		e.preventDefault
		$.ajax({
			type : "POST",
			url : "/tickets/createTicket",
			data : $('.createTicket').serialize(),
		}).done(function(data) {
			var msg = "";
			if (data.response != "") {
				$.each(data.errors, function(k, v) {
					msg = v + "\n" + msg;
				})
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
		})
	}); 
</script>

</body>

</html>
