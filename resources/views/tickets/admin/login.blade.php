@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Log In')
@section('body')
 <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>              
				<div class="well">
					
		            <form class="m-t" role="form" method="post" action="login"  >
		            	 {!! csrf_field() !!}
		            	  @if ($errors->has('password'))
      									<center><label class="text-danger"><strong>Email or Password</strong> didn't match. </label></center>
  									<br>
                             @endif
                             @if ($errors->has('email'))
      									<center><label class="text-danger"><strong>Email</strong> doesn't exist. Are you sure you have an account?</label></center>
  									<br>
                             @endif
                             
		                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
		                    <input type="email" class="form-control" placeholder="Email" value="{{ old('email') }}"name="email" required="">
		                     
		                </div>
		                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
		                    <input type="password" class="form-control" placeholder="Password" name="password" required="">
		     
		                </div>
		                <button type="submit" action="" class="btn btn-primary block full-width m-b">
		                	<i class="fa fa-btn fa-sign-in"></i>Login</button>
		
		                <a href="/admin/forgotPassword"><small>Forgot password?</small></a>
		                <p class="text-muted text-center"><small>Do not have an account?</small></p>
		                <a class="btn btn-sm btn-white btn-block" href="/admin/register">Create an account</a>
		            </form>
				</div>  
        	</div>
        </div>
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
