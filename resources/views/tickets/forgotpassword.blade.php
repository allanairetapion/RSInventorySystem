<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot password</title>
	<link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
	<script src="/js/jquery-2.1.1.js"></script>
	<script src="/js/bootstrap.min.js"></script>
</head>

<body class="gray-bg">

    <div class="passwordBox animated fadeInDown">
        <div class="row">

            <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">Forgot password</h2>

                    <p>
                        Enter your email address and we will send a verification code to reset your password.
                    </p>

                    <div class="row">

                        <div class="col-lg-12">
                            <form class="m-t" role="form" action="forgotPassword" method="post">
                            	{!! csrf_field() !!}
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email address" required="">
                                  @if($errors->has('email'))
   										<br>
      									<center><label class="label-danger"><strong>Email<strong> doesn't exist. Are you sure you have an account?</label></center>
  									
								  @endif
                                </div>
                                 

                                <button type="submit" class="btn btn-primary block full-width m-b">Send Verification Code</button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Copyright Remote Staff Inc
            </div>
            <div class="col-md-6 text-right">
               <small>© 2014-2015</small>
            </div>
        </div>
    </div>

	<script>
		
			$('span').hide();
		
	</script>
</body>

</html>
