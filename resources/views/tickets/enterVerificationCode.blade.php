@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Verify Code')
@section('body')
 <div class="passwordBox animated fadeInDown">
        <div class="row">

            <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">Verify Code</h2>

                    <p>
                        Enter the verification code sent to your email address to continue.
                    </p>

                    <div class="row">
						 @if($errors->has('email'))
   										<br>
      									<center><label class="text-danger"><strong>Email</strong> doesn't exist. Are you sure you have an account?</label></center>
  									
						@endif
                        <div class="col-lg-12">
                            <form class="m-t" role="form" action="forgotPassword" method="post">
                            	{!! csrf_field() !!}
                                <div class="form-group">
                                    <input type="text" class="form-control" name="vcode" placeholder="Code here" required="">
                                 
                                </div>
                                 

                                <button type="submit" class="btn btn-primary block full-width m-b">Submit</button>

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