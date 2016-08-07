@extends('tickets.ticketadminlayout')
@section('body')
<div class="row">
	<div class="col-md-12">
		<div class="  ibox animated fadeInDown">

			<div class="ibox">
				<div class="ibox-title">
					<div class="pull-right">
					<a href="/admin/createClient" class="btn btn-sm btn-primary"><i class="fa fa-user-plus"></i> Create client account </a>
					</div>
					<h2 class="font-bold ">Clients</h2>
				</div>
				<div class="ibox-content">

					<form class="topic form-horizontal clientPassword" method="post">
						{!! csrf_field() !!}
						<input type="hidden" class="form-control email" name="email" placeholder="Email address" required="" value="{{ old('email') }}">
						<table class="table table-striped table-bordered table-hover clientTable" id="editable" >
							<thead>
								<tr>
									<th>Id</th>
									<th>Email</th>
									<th>Name</th>
									<th>Department</th>
									<th>Status</th>
									<th class="text-center">Actions</th>

								</tr>
							</thead>
							<tbody>
								@foreach ($clients as $client)
								<tr class="gradeX">
									<td>{{$client->id}}</td>
									<td>{{$client->email}}</td>
									<td>{{$client->first_name.' '.$client->last_name}}</td>
									<td>{{$client->department}}</td>
									<td id="{{$client->id}}">{{$client->status}}</td>
									<td class="text-center">
									<button type="button" class="btn btn-sm btn-primary clientPasswordResetLink" value="{{$client->email}}">
										Send Reset Link
									</button>
									<button type="button" class="btn btn-sm btn-warning clientChangePassword" value="{{$client->id}}">
										Change Password
									</button>
									<button type="button" class="btn btn-sm btn-danger changeClientStatus" name="{{$client->status}}" value="{{$client->id}}">
										Change Status
									</button>
									<button type="button" class="btn btn-sm btn-danger btn-outline clientDelete" name="clientDelete" value="{{$client->id}}" >Delete
									</button>
									</td>

								</tr>
								@endforeach

							</tbody>

						</table>

					</form>

				</div>

			</div>
		</div>
	</div>
	
	
</div>
@endsection
