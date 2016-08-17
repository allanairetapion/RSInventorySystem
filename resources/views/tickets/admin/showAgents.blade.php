@extends('tickets.ticketadminlayout') @section('body')

<div class="row">
	<div class="col-md-12">
		<div class="  ibox animated fadeInDown">

			<div class="ibox">
				<div class="ibox-title">
					@if(Auth::guard('admin')->user()->user_type == 'admin')
					<div class="pull-right">
						<a href="/admin/createAgent" class="btn btn-sm btn-primary"><i
							class="fa fa-user-plus"></i> Create New User </a>
					</div>
					@endif
					<h2 class="font-bold ">Agents</h2>
				</div>

				<div class="ibox-content" style="height: 450px;">

					<form class="topic form-horizontal agentPassword" method="post">
						{!! csrf_field() !!} <input type="hidden"
							class="form-control email" name="email"
							placeholder="Email address" required=""
							value="{{ old('email') }}">

						<table
							class="table table-striped table-bordered table-hover agentPassword"
							id="editable">
							<thead>
								<tr>
									<th>Id</th>
									<th>Email</th>
									<th>Name</th>
									<th>User Type</th>
									<th>Date Registered</th>
									<th>Date Updated</th>
									@if(Auth::guard('admin')->user()->user_type== 'admin')
									<th class="text-center">Actions</th> @endif

								</tr>
							</thead>
							<tbody>
								@foreach ($agents as $agent)
								<tr class="gradeX">
									<td>{{$agent->id}}</td>
									<td>{{$agent->email}}</td>
									<td>{{$agent->first_name.' '.$agent->last_name}}
									
									<td id="{{$agent->id}}">{{$agent->user_type}}</td>
									<td>{{$agent->date_registered}}</td>
									<td>{{$agent->updated_at}}</td>
									@if(Auth::guard('admin')->user()->user_type == 'admin')
									<td class="text-center">
										<div class="btn-group">
											<button type="button"
												class="btn btn-sm btn-primary btn-xs agentPasswordResetLink"
												value="{{$agent->email}}">Send Reset Link</button>

											<button type="button"
												class="btn btn-sm btn-danger btn-xs agentChangeUserType"
												name="{{$agent->user_type}}" value="{{$agent->id}}">Change
												user type</button>
										</div>
									</td> 
									@endif
								</tr>
								@endforeach

							</tbody>

						</table>
					</form>

					</form>

				</div>
			</div>

		</div>
	</div>

</div>

@endsection
