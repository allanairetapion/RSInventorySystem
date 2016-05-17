@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Sign Up')
@section('body')

   <div class="passwordBox animated fadeInDown">
        <div class="row">

            <div class="col-md-12">
                <div class="ibox-content">
                    <h3 class="text-success">Password successfully changed</h3>
        	 		You may now login to your account using your new password <br><br>
        	 		<a href="admin/logout" class="btn btn-primary btn-block">Sign In</a>               
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
