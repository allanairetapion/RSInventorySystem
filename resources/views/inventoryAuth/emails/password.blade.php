<html>

<body>
     
   	<img src="{{ $message->embed(public_path() . '/img/remote-staff-logo.jpg') }}" alt="" />
  
    <div align="left">	
		<h1>Reset your password</h1>
    	<br>
    	<p>Hi {{$user->first_name}} {{$user->last_name}},
		<br><br>
		We've received a request to change your Remote Staff Inventory System Management account password.
		<br><br>
    	Click the link below to reset your password. This link will expire in 1hour.
    	<br>
    	<a href="{{ $link = url('/inventory/changePassword', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
		<br><br><br>
		Regards,
		<br>
		Remote Staff Operations</p>
		</div>
</body>
</html>	