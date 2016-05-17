@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Forgot Password')
@section('body')


 <div class="passwordBox animated fadeInDown">
        <div class="row">

            <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">Forgot password</h2>

                    <p>
                        Enter your email address and we will send a verification code to reset your password.
                    </p>
                    
                    @if (session('status'))
                        <div class="alert alert-success">
                           <center> {{ session('status') }} </center>
                        </div>
                    @endif

                    <div class="row">
						
                        <div class="col-lg-12">
                            <form class="m-t" role="form" action="{{ url('/admin/forgotPassword') }}" method="post">
                            	{!! csrf_field() !!}
                                <div class="form-group">
                                    <input type="email" class="form-control" name="email" placeholder="Email address" required="" value="{{ old('email') }}">
                                 
                                  @if($errors->has('email'))
   										<br>
      									<center><label class="text-danger"><strong>Email</strong> doesn't exist. Are you sure you have an account?</label></center>
  									
						          @endif
                                </div>
                                 

                                <button type="submit" class="btn btn-primary block full-width m-b">Send Verification Code</button>

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
