<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Print Ticket</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">

		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">

		<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/bootstrap.min.js"></script>

	</head>
	<body class="white-bg">
		<div class="row">
			<div class="col-md-12 animated fadeInRight mailView">

				<div class="mail-box-header">

					<div class="row">
						<div class="pull-right">

						</div>
						<h2> &nbsp; View Message </h2>
					</div>
					<div class="mail-tools tooltip-demo m-t-md">

						<h5><span class="pull-right"><span class="font-noraml">Ticket id:</span> {{Session::get('id')}}</span></h5><h3><span class="font-noraml">Subject: </span>{{Session::get('subject')}} </h3>
						<h5><span class="pull-right"><span class="font-noraml">Date sent:</span> {{Session::get('date_sent')}}</span><span class="font-noraml">From: </span>{{Session::get('email')}} </h5>

						@if(Session::get('status') != "Pending")
						<h5> @if(Session::get('status') == "Closed") <span class="pull-right"><span class="font-noraml">Closed by: </span> {{Session::get('closed_by')}} </span> @endif <span class="font-noraml">Assigned to: </span>{{Session::get

						('assigned_support')}} </h5>
						@endif
					</div>

					<div class="mail-box">
						<div class="mail-body">
							Summary:
							<div class="ibox-content gray-bg">
								<p>
									{!!html_entity_decode(Session::get('summary'))!!}
								</p>
							</div>

							@if(Session::get('status') == "Closed")
							<br>
							Closing Report:
							<div class="ibox-content gray-bg">
								<p>
									{!!html_entity_decode(Session::get('closing_report'))!!}
								</p>
							</div>
							@endif

						</div>

						<div class="clearfix"></div>

					</div>
				</div>
			</div>
			
			<script>
				$(document).ready(function(){
					window.print();
				});
			</script>
	</body>
</html>