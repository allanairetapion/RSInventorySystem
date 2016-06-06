
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
		<link href="/css/plugins/dataTables/datatables.min.css" rel="stylesheet">
		<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
		<script src="/js/plugins/chartJs/Chart.min.js"></script>
		<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
		<link href="/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
		<link href="/css/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet">
		<link href="/css/plugins/summernote/summernote.css" rel="stylesheet">
		<link href="/css/plugins/summernote/summernote-bs3.css" rel="stylesheet">
		<link href="/css/plugins/bootstrap-markdown/bootstrap-markdown.min.css" rel="stylesheet">
		<link href="/css/plugins/toastr/toastr.min.css" rel="stylesheet">

		<style>
			@media screen and (min-width: 768px) {
				.modal-lg {
					width: 900px;
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
											<a href="/admin/createTicket">Submit a Ticket</a>
										</li>
										@if (Auth::guard('admin')->user()->user_type == "agent")
										@if($restrictions[2]->agent ==1)
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
								@if(Auth::guard('admin')->user()->user_type == 'agent')

								@if(($restrictions[3]->agent || $restrictions[4]->agent) == 1)
								<li class="dropdown">
									<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Agents <span class="caret"></span></a>
									<ul role="menu" class="dropdown-menu">
										@if ($restrictions[4]->agent)
										<li>
											<a href="/admin/agents">My Agents</a>
										</li>
										@endif
										@if($restrictions[3]->agent == 1)
										<li>
											<a href="/admin/createAgent">Create New User</a>
										</li>
										@endif
									</ul>
								</li>
								@endif

								@else
								<li class="dropdown">
									<a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Agents <span class="caret"></span></a>
									<ul role="menu" class="dropdown-menu">
										<li>
											<a href="/admin/agents">My Agents</a>
										</li>
										<li>
											<a href="/admin/createAgent">Create New User</a>
										</li>
									</ul>
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

								<li>
									<img alt="image" class="img-rounded" src="/img/1.png"
									style="width: 30px">
								</li>
								<li class="active">
									<a>{{ Auth::guard('admin')->user()->adminProfile ? Auth::guard('admin')->user()->adminProfile->first_name.' '.Auth::guard('admin')->user()->adminProfile->last_name : '' }}!</a>
								</li>
								<li>
									<a class="dropdown-toggle count-info" data-toggle="dropdown" href="#"> <i class="fa fa-envelope"></i> <span class="label label-warning">16</span> </a>
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
										</li>

									</ul>
								</li>
								<li>
									<a href="/admin/logout"> <i class="fa fa-sign-out"></i>Log out </a>
								</li>
							</ul>
						</div>
					</nav>
				</div>
				<div class="wrapper wrapper-content">
					<div class="container">

						@section('body')
						@show

					</div>
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
		@section('scripts')
		<!-- Mainly scripts -->
		<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
		<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<!---Data Table -->
		<script src="/js/plugins/jeditable/jquery.jeditable.js"></script>
		<script src="/js/plugins/dataTables/datatables.min.js"></script>
		<!-- Data picker -->
		<script src="/js/plugins/datapicker/bootstrap-datepicker.js"></script>
		<!-- Date range use moment.js same as full calendar plugin -->
		<script src="/js/plugins/fullcalendar/moment.min.js"></script>
		<!-- SUMMERNOTE -->
		<script src="/js/plugins/summernote/summernote.min.js"></script>

		<!-- Date range picker -->
		<script src="/js/plugins/daterangepicker/daterangepicker.js"></script>

		<!-- Custom and plugin javascript -->
		<script src="/js/inspinia.js"></script>
		<script src="/js/plugins/pace/pace.min.js"></script>

		<script src="/js/plugins/sweetalert/sweetalert.min.js"></script>

		<!-- Flot -->
		<script src="/js/plugins/flot/jquery.flot.js"></script>
		<script src="/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
		<script src="/js/plugins/flot/jquery.flot.resize.js"></script>
		<script src="/js/plugins/flot/jquery.flot.pie.js"></script>

		<!-- ChartJS-->
		<script src="/js/plugins/chartJs/Chart.min.js"></script>

		<!-- Peity -->
		<script src="/js/plugins/peity/jquery.peity.min.js"></script>
		<!-- Peity demo -->
		<script src="/js/demo/peity-demo.js"></script>
		<!--Ichecks-->
		<script src="/js/plugins/iCheck/icheck.min.js"></script>
		<!-- Toastr script -->
		<script src="/js/plugins/toastr/toastr.min.js"></script>

		<script>
			$(document).ready(function() {
				
				$('div.panel.ticketReply').hide();
				$('span.addTopic').hide();
				$('.i-checks').iCheck({
					checkboxClass : 'icheckbox_square-green',
					radioClass : 'iradio_square-green',
				});
				$('[data-toggle="tooltip"]').tooltip();
				$('table.agentPassword').dataTable();
				$('table.clientTable').dataTable();		
				
				
function getRandomColor() {
    var letters = '0123456789ABCDEF'.split('');
    var color = '#';
    for (var i = 0; i < 6; i++ ) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}
				$('table.noSupport').dataTable();

				var barData = {
					labels : ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sept","Oct","Nov","Dec"],
					datasets : [{
						label : "My First dataset",
						fillColor : getRandomColor(),
										
						data : [65, 59, 80, 81, 56, 55, 40]
					}, {
						label : "My Second dataset",
						fillColor : getRandomColor(),
										
						data : [28, 48, 40, 19, 86, 27, 90]
					}]
				};

				var barOptions = {
					scaleBeginAtZero : true,
					scaleShowGridLines : true,
					scaleGridLineColor : "rgba(0,0,0,.05)",
					scaleGridLineWidth : 1,
					barShowStroke : true,
					barStrokeWidth : 2,
					barValueSpacing : 5,
					barDatasetSpacing : 1,
					responsive : true
				}

				var ctx = document.getElementById("barChart").getContext("2d");
				var myNewChart = new Chart(ctx).Bar(barData, barOptions);

			});
		</script>
		<script>
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
						console.log($('.agentForm').serialize());
						validateSuccess();
					}

				});
			});

			function validateSuccess() {
				swal({
					title : "Are you sure you want to create new agent?",
					text : "If you are sure, Please enter your password.",
					type : "input",
					inputType : "password",
					showCancelButton : true,
					closeOnConfirm : false,
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
				$('input[type="hidden"].topic').val($('div.ticketsummernote').code());
				console.log($('div.ticketsummernote').code());
				$('div.topic').removeClass('has-error');
				$('div.subject').removeClass('has-error');
				$('div.summary').removeClass('has-error');
				e.preventDefault
				$.ajax({
					type : "POST",
					url : "/admin/createTicket",
					data : $('.createTicket').serialize(),
				}).done(function(data) {
					console.log($('.createTicket').serialize());
					var msg = "";
					if (data.response != "") {
						$.each(data.errors, function(k, v) {
							msg = v + "\n" + msg;
						});

						if (data.errors['topic']) {
							$('div.topic').addClass('has-error');
						}
						if (data.errors['assigned_support']) {
							$('div.assigned_support').addClass('has-error');
						}
						if (data.errors['subject']) {
							$('div.subject').addClass('has-error');
						}
						if (data.errors['summary']) {
							$('div.summary').addClass('has-error');
						}

						swal("Oops...", msg, "warning");
					} else {
						swal("Succes!", "Your ticket has been created.", "success");
						$('div.assigned_support').removeClass('has-error');
						$('div.topic').removeClass('has-error');
						$('div.subject').removeClass('has-error');
						$('div.summary').removeClass('has-error');
					}
				});
			});

			//Add Topic
			$('button.addTopic').click(function(e) {
				e.preventDefault

				$.ajax({
					type : "POST",
					url : "/admin/addTopic",
					data : $('form.addTopic').serialize(),
				}).done(function(data) {

					var msg = "";
					if (data.success == false) {
						$.each(data.errors, function(k, v) {
							msg = v + "\n" + msg;
						});
						swal("Oops...", msg, "warning");
						$('div.addTopic').addClass('has-error');
						$('div.addTopic').addClass('has-feedback');
						$('span.addTopic').show();
					} else {

						$('table.topics').html(data);
						$('span.addTopic').hide();
						$('div.addTopic').removeClass('has-error');
						$('div.addTopic').removeClass('has-feedback');
						swal("Added", "New Topic has been added.", "success");
					}
				});

			});

			//Delete Topic
			$('button.deleteTopic').click(function(e) {
				$('span.addTopic').hide();
				$('div.addTopic').removeClass('has-error');
				$('div.addTopic').removeClass('has-feedback');
				console.log($('form.topic').serializeArray());
				e.preventDefault
				swal({
					title : "Are you sure?",
					text : "Selected topic will be deleted. ",
					type : "warning",
					showCancelButton : true,
					confirmButtonColor : "#DD6B55",
					confirmButtonText : "Yes",
					closeOnConfirm : false
				}, function() {

					$.ajax({
						type : "POST",
						url : "/admin/deleteTopic",
						data : $('form.topic').serializeArray(),
					}).done(function(data) {
						swal("Deleted!", "Topic/s has been deleted.", "success");
						$('table.topics').html(data);
					});
				});
			});

			//Update Selection
			$('button.updateTopic').click(function(e) {
				console.log($('form.topic').serializeArray());
				$('span.addTopic').hide();
				$('div.addTopic').removeClass('has-error');
				$('div.addTopic').removeClass('has-feedback');
				e.preventDefault
				swal({
					title : "Updated!",
					text : "Topic selection has been updated.",
					type : "success",
					timer : 5000,
				});
				$.ajax({
					type : "POST",
					url : "/admin/updateSelection",
					data : $('form.topic').serializeArray(),
				})
			});

			$("input.topicCB").change(function() {
				$("input.topic:checkbox").prop('checked', $(this).prop("checked"));
			});
			//Save Restriction
			$('input.saveRestriction').on('click', function(e) {
				e.preventDefault
				swal({
					title : "Updated!",
					text : "Restrictions has been updated.",
					type : "success",
					timer : 5000,
				});
				console.log($('form.restriction').serializeArray());

				$.ajax({
					type : "POST",
					url : "/admin/updateRestriction",
					data : $('form.restriction').serialize(),
				})
			});

			$('button.clientPassword').on('click', function(e) {
				$('input.email').val($(this).val())
				$.ajax({
					headers : {
						'X-CSRF-Token' : $('input[name="_token"]').val()
					},
					type : "POST",
					url : "/tickets/forgotPassword",
					data : $('form.clientPassword').serialize(),
				})
			});
			$('button.agentPassword').on('click', function(e) {
				$('input.email').val($(this).val())
				$.ajax({
					headers : {
						'X-CSRF-Token' : $('input[name="_token"]').val()
					},
					type : "POST",
					url : "/admin/forgotPassword",
					data : $('form.agentPassword').serialize(),
				})
			});

			$('button.ticketReply').on('click', function() {
				$('div.panel.ticketReply').slideToggle(1000);
			});

			$('div.modal').on('hidden.bs.modal', function() {
				$('div.panel.ticketReply').hide();
			})

			$('button.deleteTicket').on('click', function(e) {
				e.preventDefault
				console.log($('form.ticketReply').serializeArray());
				swal({
					title : "Are you sure?",
					text : "You can't undo this action",
					type : 'warning',
					showCancelButton : true,
					confirmButtonText : "Yes",
					closeOnConfirm : false
				}, function() {
					$.ajax({
						type : "POST",
						url : "/admin/deleteTicket",
						data : $('form.ticketReply').serializeArray(),
					}).done(function(data) {
						swal({
							title : "Deleted!",
							text : "Ticket has been deleted.",
							type : "success",
						}, function() {
							$('div.modal').modal("hide");
						});
						$('table.ticketsNew').html(data);
						$('.i-checks').iCheck({
							checkboxClass : 'icheckbox_square-green',
							radioClass : 'iradio_square-green',
						});
					});

				});
			});

		</script>
		<script type="text/javascript">
						$('select.selectticketStatus').click(function() {
			var user_type = "<?php echo Auth::guard('admin')->user()->user_type ?>
				";
				var me;

				if (user_type == "admin")
				me =
			<?php echo $restrictions[0]->admin
			?>
				;
				else
				me =
			<?php echo $restrictions[0]->agent
			?>
				;

				if (me == 0) {
					swal({
						title : 'Oops',
						text : "You're not allowed to do that",
						type : 'error',
						timer : 3000,
					});
				}
				});
		</script>
		@show
	

			</body>

			</html>

