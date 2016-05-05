@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Sign Up')
@section('body')
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
                	<input type="password" class="form-control" placeholder="Confirm Password" name="Cpassword" required="">
                </div>
                
              
                
				
                <button type="submit" class="btn btn-primary block full-width m-b">Register</button>

                <p class="text-muted text-center"><small>Already have an account?</small></p>
                
                <a class="btn btn-sm btn-white btn-block" href="/tickets/login">Login</a>
            </form>
            
        </div class="well">
		<div class="row">
            <div class="col-md-7">
                Copyright Remote Staff Inc
            </div>
            <div class="col-md-5 text-right">
               <small>© 2014-2015</small>
            </div>
        </div>
    </div>
    @endsection
