@extends('tickets.ticketadminlayout')
@section('body')
<div class="  ibox animated fadeInDown">
	
		<div class="ibox">
			<div class="ibox-title text-center">
				<h2 class="font-bold ">Agents</h2>
			</div>
			<div class="ibox-content" style=" height:450px;">

				<form class="topic form-horizontal agentPassword" method="post">
					{!! csrf_field() !!}
					<input type="hidden" class="form-control email" name="email" placeholder="Email address" required="" value="{{ old('email') }}">
					<table class="table table-striped table-bordered table-hover agentPassword" id="editable" >
						<thead>
							<tr>
								<th>Id</th>
								<th>Email</th>
								<th>User Type</th>
								<th>Password</th>

							</tr>
						</thead>
						<tbody>
							@foreach ($agents as $agent)
							<tr class="gradeX">
								<td>{{$agent->id}}</td>
								<td>{{$agent->email}}</td>
								<td>{{$agent->user_type}}</td>
								<td><button type="button" class="btn btn-sm btn-primary agentPassword" value="{{$agent->email}}">Send Password Reset Link</button></td>

							</tr>
							@endforeach

						</tbody>

					</table>

				</form>

			</div>
		</div>
	
</div>

@endsection
