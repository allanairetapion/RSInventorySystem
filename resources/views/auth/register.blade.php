@extends('tickets.ticketlayout1')
@section('title', 'Remote Staff - Sign Up')
@section('body')

<div class="  loginscreen   animated fadeInDown">
  
   	<div class="col-md-offset-4 col-md-4">
   				
    		<div class="well">   
		
			<form class="m-t" role="form" method="Post" action="/tickets/signUp">
        		<input type="hidden" name="_token" value="{{ csrf_token() }}">
        	<center><h3>Create an account</h3>
        		<label class="text-danger"><strong>*</strong></label>
        			<label class="text-muted">Indicates required field.</label></center>	
					
        		
            		<div class="form-group">
            			
            			<div>
            				@if($errors->has('fname'))
								<center><label class="text-danger"><strong>First name must be atleast 2 characters and only contain alphabets.</strong></label></center>	
						 	@endif	
						 	
						 	@if($errors->has('lname'))
								<center><label class="text-danger"><strong>Last name must be atleast 2 characters and only contain alphabets.</strong></label></center>	
						 	@endif
						 	
						 	<label class="text-muted">First Name</label>	
							<label class="text-danger"><strong>*</strong></label>
            					<div class="col-md-12{{ $errors->has('fname') ? ' has-error' : '' }}">
									<input type="text" class="form-control" placeholder="First Name" name="fname"required="" value={{old("fname")}}>
                					<br>
                				</div>
                			
                			<br>
                			<label class="text-muted">Last Name</label>	
							<label class="text-danger"><strong>*</strong></label>
            				
                				<div class="col-md-12{{ $errors->has('lname') ? ' has-error' : '' }}">
									<input type="text" class="form-control" placeholder="Last Name" name="lname"required="" value={{old("lname")}} >
               					 	<br>
               					</div>
               		   </div>
               				<div>
               					@if($errors->has('email'))
									<center><label class="text-danger"><strong>This</strong> email exists</label></center>	
						 		@endif
						 		
						 		<br>
                			<label class="text-muted">Email</label>	
							<label class="text-danger"><strong>*</strong></label>
            				
               					<div class="col-md-12{{ $errors->has('email') ? ' has-error' : '' }}">
									<input type="email" class="form-control" placeholder="Email" name="email" required="" value={{old("email")}}>
                				 <br>
								</div>
							
							<br>
                			<label class="text-muted">Department</label>	
							<label class="text-danger"><strong>*</strong></label>
            				
									 <div class="col-md-12">	 
						 				 <input type="text" class="form-control" placeholder="Department" name="dept" required="">
                				 		<br>
                					</div>
               			   </div>
               					 <div>
                	 	 			@if($errors->has('password'))
										<center><label class="text-danger"><strong>The password must be between 6 and 100 characters or Password didn't match.</label></center>	
								    @endif 
									@if($errors->has('password_confirmation'))
										<center><label class="text-danger"><strong> The password confirmation does not match.</label></center>	
								    @endif
								    
							<br>
                			<label class="text-muted">Password</label>	
							<label class="text-danger"><strong>*</strong></label>
            				
                						<div class="col-md-12 {{ $errors->has('password') ? ' has-error' : '' }}">
											<input type="password" class="form-control" placeholder="Password" name="password" required="" value={{old("password")}}>
                							<br>
                						</div>
                						
                			<br>
                			<label class="text-muted">Re-Type Password</label>	
							<label class="text-danger"><strong>*</strong></label>
            				
                							<div class="col-md-12">
												<input type="password" class="form-control" placeholder="Re-Type Password" name="password_confirmation" required="" value={{old("password_confirmation")}} >
                								<br>
                							</div>
                					
                					@if($errors->has('captcha'))
										<center><label class="text-danger"><strong> Captcha not match</label></center>	
								    @endif
								    
							<br>
                			<label class="text-muted">Type the code below</label>	
							<label class="text-danger"><strong>*</strong></label>
            				
                							<div class="col-md-6 col-md-offset-3">
													<p>{!! captcha_img() !!} </p>
      											<p><input type="text" name="captcha"></p>';
											
                							</div>
              					 </div> 
					</div>
               		
                	
                				
                					<button type="submit"  class="btn btn-primary block full-width m-b">Register</button>
								
									<p class="text-muted text-center"><small>Already have an account?</small></p>
               
                				
               							<a class="btn btn-sm btn-white btn-block" href="/tickets/login">Login</a>
            						
           						
           			</form>		
                 </div>
                
                  <div class="row">
            <div class="col-md-4">
                Copyright Remote Staff Inc
            </div>
            <div class="col-md-offset-3 col-md-5 text-right">
               <small>© 2014-2015</small>
            </div>
        </div>
            
           
      
</div>


		
    @endsection
