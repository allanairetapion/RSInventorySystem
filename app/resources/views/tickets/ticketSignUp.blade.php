<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tickets Sign Up</title>

	<link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="js/plugins/iCheck/icheck.min.js"></script>
</head>

<body class="white-bg">
	<br><br>

        <center><img src="/img/remote-staff-logo.jpg" class="img-responsive"></center>
		<br>
	

    <div class="middle-box text-center loginscreen   animated fadeInDown">
    	<div class="well">         
        	<form class="m-t" role="form" method="Post" action="signUp">
        		 {!! csrf_field() !!}
            	<div class="form-group">
                    <input type="text" class="form-control" placeholder="First Name" name="fname"required="">
                    <input type="text" class="form-control" placeholder="Last Name" name="lname"required="">
                </div>
                
                <div class="form-group">
                    <input type="email" class="form-control" placeholder="Email" name="email" required="">
                </div>
                
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Department" name="dept" required="">
                </div>
                
                
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password" required="" pattern=".{6,}" title="6 characters minimum">
                </div>
                
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Confirm Password" name="Cpassword" required="">
                </div>
                
				
                <button type="submit" class="btn btn-primary block full-width m-b">Register</button>

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                
                <a class="btn btn-sm btn-white btn-block" href="/tickets/login">Login</a>
            </form>
            
        </div class="well">

    </div>
    
   
</body>

</html>
