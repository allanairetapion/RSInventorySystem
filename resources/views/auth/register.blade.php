<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>RSITS | Register</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="/css/animate.css" rel="stylesheet">
<link href="/css/style.css" rel="stylesheet">

</head>

<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
        <div class="row border-bottom white-bg">
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-header">
                <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                    <i class="fa fa-reorder"></i>
                </button>
                <a href="#" class="navbar-brand">RSITS</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
              
                <ul class="nav navbar-top-links navbar-right">
                    <li>
                        <a href="/tickets/login"> <i class="fa fa-sign-in"></i> Sign In
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        </div>
        <div class="wrapper wrapper-content">
            <div class="container">
           <div class="row">

						<div class="ibox  float-e-margins col-md-offset-3 col-md-6  animated fadeInDown">
							<div class="ibox-title ">
								<h3 class=" font-bold">Create an Account</h3>
							</div>
							<div class="ibox-content">
								<form class="clientForm" method="Post" action="/tickets/signUp">
									{!! csrf_field() !!}
									<div class="row">
										<div class="form-group col-md-6 firstname">
											<label>First Name:</label> <input type="text"
												class="form-control" placeholder="First Name"
												name="first_name" required="" value={{old("fname")}}> <label
												class="text-danger firstname "></label>
										</div>
										<div class="form-group col-md-6 lastname">
											<label>Last Name:</label> <input type="text"
												class="form-control" placeholder="Last Name"
												name="last_name" required="" value={{old("lname")}} > <label
												class="text-danger lastname "></label>
										</div>
										<div class="form-group col-md-12 email">
											<label>Email: <span class="text-danger email "></span>
											</label> <input type="email" class="form-control"
												placeholder="Email" name="email" required="" value={{old("email")}}>
										</div>
										<div class="form-group col-md-12 department">
											<label>Department: <span class="text-danger department "></span>
											</label> <input type="text" class="form-control department"
												placeholder="Department" name="dept" required=""
												value={{old("dept")}}>
										</div>
										<div class="form-group col-md-12 password">
											<label>Password: <span class="text-danger password "></span>
											</label> <input type="password" class="form-control"
												placeholder="Password" name="password" required="">
										</div>
										<div class="form-group col-md-12">
											<label>Confirm Password:</label> <input type="password"
												class="form-control" placeholder="Re-Type Password"
												name="password_confirmation" required="">
										</div>

										<div class="form-group col-md-6 captcha">

											<label class="">Enter CAPTCHA code:</label> <input
												type="text" class="form-control" name="captcha"> <label
												class="text-danger captcha"> *Captcha code did not match</label>
										</div>

										<div class="col-md-6 text-center captcha_img">
											<br> <img class="captcha_img " src="{{captcha_src()}}">
											&nbsp;

											<button type="button"
												class="btn  btn-default refreshCaptcha "
												data-toggle="tooltip" data-placement="top"
												title="Refresh CAPTCHA">
												<i class="fa fa-refresh"></i>
											</button>
										</div>

									</div>
									<hr />
									<div class="text-center">
										<button type="button"
											class="ladda-button btn btn-primary registerClient"
											data-style="zoom-in">Register</button>

									</div>
								</form>
							</div>
							
						</div>

					</div>
             

               

              

            </div>

        </div>
        <div class="footer">
            <div class="pull-right">
                 Ticket Sign Up Page
            </div>
            <div>
                <strong>Copyright</strong> Remote Staff Inc &copy; 2008-<?php echo date("Y");?>
            </div>
        </div>

        </div>
        </div>



    	<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
		<script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
		<!-- Custom and plugin javascript -->
		<script src="/js/inspinia.js"></script>
		<script src="/js/plugins/pace/pace.min.js"></script>

		<!-- Ladda -->
		<script src="/js/plugins/ladda/spin.min.js"></script>
		<script src="/js/plugins/ladda/ladda.min.js"></script>
		<script src="/js/plugins/ladda/ladda.jquery.min.js"></script>

		<script type="text/javascript" src="/js/jquery-ui-1.10.4.min.js"></script>
		<script src="/js/ticketsClients.js"></script>
	


   <script>
	$(document).ready(function() {
		$('div.form-group').removeClass('has-error');
		$('span.text-danger').hide();
		$('label.text-danger').hide();

		$('div.loginColumns').hide();
	});
	
	$(function() {
		$("input.department").keyup(function() {
			$("input.department").autocomplete({
				source : "{{URL('/search')}}",
				minLength : 1
			});
			$("#auto").autocomplete("widget").height(200);
		});
	}); 
</script>

</body>

</html>




