@extends('tickets.ticketadminlayout')
@section('body')
<br><br>
<div class="  ibox animated fadeInDown">	
	<div class="col-md-offset-3 col-md-6">			
		<div class="ibox">
			<div class="ibox-title">
				Create New
			</div>
			<div class="ibox-content">
				<form class="m-t form-horizontal agentForm" role="form" method="Post" action="/admin/register">
					{!! csrf_field() !!}
					<div class="row">
						
							<div class="form-group {{ $errors->has('fname') ? ' has-error' : '' }}">																	
								<label class="col-md-3 control-label">First Name:&nbsp;</label>	
								<div class="col-md-8">						
									<input type="text" class="form-control" placeholder="First Name" name="fname" value="{{old('fname')}}" required="">
									<br>
								</div>									
							</div>
							<div class="form-group {{ $errors->has('lname') ? ' has-error' : '' }}">	
								<label class="col-md-3 control-label">Last Name:&nbsp;</label>	
								<div class="col-md-8">						
									<input type="text" class="form-control" placeholder="Last Name" name="lname" value="{{old('lname')}}" required="">
									<br>
								</div>
							</div>																
							<div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">	
								<label class="col-md-3 control-label">Email:&nbsp;</label>
								<div class="col-md-8">						
									<input type="email" class="form-control email" placeholder="Email Address" name="email" value="{{old('email')}}" required="">
									<br>
								</div>
							</div>
							<div class="form-group">
								<label class="col-md-3 control-label">User type: &nbsp; </label>
								<div class="col-md-8">										
									<select class="form-control">
										<option value="admin"> Admin</option>
										<option value="agent"> Agent</option>
									</select>
									<br>
								</div>
							</div>							
						</div>
					</form>	 
			 	
					
			
				<center><button type="button" class="btn btn-info btn-lg add-account">Create</button></center>
				</div>
			</div>		
		</div>
	</div>
</div>


 

@endsection
