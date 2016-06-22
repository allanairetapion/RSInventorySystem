@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Sign Up')
@section('body')

   <div class=" middle-box animated fadeInDown">
   	<center><img src="/img/remote_logo2.jpg"></center>
        <div class="ibox">

            <div class="gray-bg ibox-title">
            	<center><h3 class="text-success bold-text">Account Successfuly Activated!</h3></center>
            </div>
                <div class="gray-bg ibox-content text-center">                    
        	 		<h4>Welcome to Remote Staff's Ticketing System</h4>
        	 		<br>
        	 		<a href="/tickets/login" class="btn btn-primary btn-block"> Click here to Sign in </a>     	 
        	 		        
                </div>
            
        </div>
        <hr/>
    </div>
    <div class="footer">
            	  <p class="pull-right">&copy;2008-<?php echo date("Y"); ?></p>
                <p><strong>Copyright</strong> Remote Staff Inc.</p> 	            
    </div>
@endsection    
