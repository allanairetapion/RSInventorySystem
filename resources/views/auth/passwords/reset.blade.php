@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Change Password')
@section('body')
   
 <div class="passwordBox animated fadeInDown">
        <div class="ibox">

            <div class="ibox-title">
            	<center><h2 class="font-bold">Reset password</h2><center>
            </div>
                <div class="ibox-content text-center">

                    
					<P>Enter your new password and confirm your new password.</P>
                   

                    <div class="row">
						 
                        <div class="col-lg-12">
                            <form class="m-t" role="form" method="post" action="{{ url('/password/reset') }}"  >
		            	 		{!! csrf_field() !!}
		            	 		<input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            
								@if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                           
                                <input type="email" placeholder="Email Address" class="form-control" name="email" value="{{ $email or old('email') }}" required>

                                
                            
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
		                	<i class="fa fa-btn fa-sign-in"></i>&nbsp;Confirm</button>
		
		                
		            </form>
                        </div>
                    </div>
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
		$('input[type="email"]').hide();
		
	});
</script>
@endsection
