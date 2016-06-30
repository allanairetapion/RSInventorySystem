@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Sign Up')
@section('body')

   <div class=" middle-box passwordBox animated fadeInDown">
   
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
        <div class="row">
		<div class="col-md-6">
			<strong>Copyright</strong> Remote Staff Inc
		</div>
		<div class="col-md-6 text-right">
			<small>&copy;2008-<?php echo date("Y"); ?></small>
		</div>
	</div>
    </div>
    
@endsection    
