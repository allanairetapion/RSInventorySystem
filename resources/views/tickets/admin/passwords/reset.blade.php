@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Change Password')
@section('body')
   
 <div class="passwordBox animated fadeInDown">
        <div class="row">

            <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">Reset password</h2>
					<P>Enter your email, new password and confirm your new password.</P>
                   

                    <div class="row">
						 
                        <div class="col-lg-12">
                            <form class="m-t" role="form" method="post" action="{{ url('/admin/changePassword') }}"  >
		            	 		{!! csrf_field() !!}
		            	 		<input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            

                           
                                <input type="email" placeholder="Email Address" class="form-control" name="email" value="{{ $email or old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            

                           
                                <input type="password" placeholder="Password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            
                            
                                <input type="password" placeholder="Confirm New Password" class="form-control" name="password_confirmation" required>

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            
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
