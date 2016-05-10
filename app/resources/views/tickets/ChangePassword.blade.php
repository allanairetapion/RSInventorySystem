@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Change Password')
@section('body')
   
 <div class="passwordBox animated fadeInDown">
        <div class="row">

            <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">Change password</h2>

                   

                    <div class="row">
						 @if($errors->has('email'))
   										<br>
      									<center><label class="text-danger"><strong>Email</strong> doesn't exist. Are you sure you have an account?</label></center>
  									
						@endif
                        <div class="col-lg-12">
                            <form class="m-t" role="form" method="post" action="login"  >
		            	 {!! csrf_field() !!}
		            	  @if ($errors->has('password'))
      									<center><label class="label-danger"><strong>Email or Password<strong> didn't match. </label></center>
  									<br>
                             @endif
                             
		                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
		                    <input type="password" class="form-control" placeholder="New Password" name="password" required="">
		                </div>
		                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
		                    <input type="password" class="form-control" placeholder="Confirm New Password" name="confirmPassword" required="">
		                </div>
		                <button type="submit" action="" class="btn btn-primary block full-width m-b">
		                	<i class="fa fa-btn fa-sign-in"></i>Confirm</button>
		
		                
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
@endsection
