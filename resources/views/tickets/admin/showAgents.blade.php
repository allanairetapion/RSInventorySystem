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

				<div class="ibox-content">

					<form class="topic form-horizontal agentPassword">
						{!! csrf_field() !!} <input type="hidden" name="email"
							class="email">
					</form>
					<table class="table  table-bordered table-hover agentPassword">
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
							<tr>
								@if(Auth::guard('admin')->user()->user_type == 'admin')
								<td><a href="/admin/agents/{{$agent->id}}">{{$agent->id}}</a></td>
								@else
								<td>{{$agent->id}}</td>
								@endif
								<td>{{$agent->email}}</td>
								<td>{{$agent->first_name.' '.$agent->last_name}}
								
								<td id="{{$agent->id}}">{{$agent->user_type}}</td>
								<td>{{$agent->created_at}}</td>
								<td>{{$agent->updated_at}}</td>
								@if(Auth::guard('admin')->user()->user_type == 'admin')
								<td class="text-center">
									<div class="btn-group">
										<button data-toggle="dropdown"
											class="btn btn-primary btn-xs dropdown-toggle">
											Actions <span class="caret"></span>
										</button>
										<ul class="dropdown-menu">
											<li><a href="/admin/agents/{{$agent->id}}">View Profile</a>
											
											<li><a href="#" id="agentPasswordResetLink"
												value="{{$agent->email}}">Send Reset Link</a></li>
											<li><a href="#" id="agentChangeUserType"
												name="{{$agent->user_type}}" value="{{$agent->id}}">Change
													User Type</a></li>

										</ul>
									</div>
								</td> @endif
							</tr>
							@endforeach

						</tbody>

					</table>



				</div>
			</div>

		</div>
	</div>

</div>

@endsection
