@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Sign Up')
@section('body')

   <div class="passwordBox animated fadeInDown">
        <div class="ibox">

            <div class="ibox-title">
            	<center><h3 class="text-success bold-text">Account Successfuly Activated!</h3></center>
            </div>
                <div class="ibox-content">
                    <form method="post">
                    	{!! csrf_field() !!}
                    	<input type="hidden" name="token" value="{{ $token }}">
                    	<input type="email" placeholder="Email Address" class="form-control" name="email" value="{{ $email or old('email') }}" required>


                    <form>
        	 		<center>Your Account is now Activated. </center>
        	 		<br>
        	 		<a href="/tickets/login" class="btn btn-primary btn-block"> Click here to Sign in </a>     	 
        	 		        
                </div>
            
        </div>
        <hr/>
    </div>
    <div class="footer">
            	  <p class="pull-right">&copy;2014-2015</p>
                <p><strong>Copyright</strong> Remote Staff Inc.</p> 	            
    </div>
<script type="text/javascript">
	
	$(document).ready(function(){
		$('input').hide();
		$.ajax({
  		type:"POST",
  		url:"/tickets/activate",
  		data: $('form').serialize(),
  	})
	});
</script>
@endsection    
