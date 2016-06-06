@extends('tickets.ticketadminlayout')
@section('body')
<div class="  ibox animated fadeInDown">
	<div class="col-md-offset-2 col-md-8">
		<div class="ibox">
			<div class="ibox-title text-center">
				<h2 class="font-bold ">Restrictions</h2>
			</div>
			<div class="ibox-content">
				
				

					<form class="topic form-horizontal restriction" method="post">
							{!! csrf_field() !!}
							
							<table class="table table-striped table-bordered topics">
								<thead>
									<tr>										
										<th class="text-center">Description</th>										
										<th class="text-center">Allowed</th>
										
									</tr>
								</thead>
								<tbody>
									@foreach($restrictions as $restriction)
									<tr>
										<td class="text-center">{{$restriction->description}} </td>																		
										<td>											
											<select name="{{$restriction->id}}" class="form-control">
												@if (($restriction->admin && $restriction->agent) == 1)
												<option value="both">Admin and Agent </option>
												<option value="admin">Admin only</option>																								
												@else
													@if($restriction->admin == 1)
													<option value="admin">Admin only</option>																																							
													<option value="both">Admin and Agent </option>																								
													@else													
													<option value="admin">Admin only</option>																																							
													<option value="both">Admin and Agent </option>																																	
													@endif
												@endif
											</select>
										</td>							
									</tr>
									@endforeach									
								</tbody>
							</table>												
								<center><input type="button" class="btn btn-primary saveRestriction" value="Save and Apply"></center>				
				</form> 
			
				
			
		</div>
	</div>
</div>
@endsection
