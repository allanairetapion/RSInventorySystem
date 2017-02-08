 @extends('layouts.inventory_basic') @section('title', 'RS | Agents')

@section('header-page')
<div class="col-lg-10">
	<h2>Agents</h2>
	<ol class="breadcrumb">
		<li><a href="/inventory/index">Home</a></li>

		<li class="active"><a href="/inventory/agents"><strong>Agents</strong></a></li>
	</ol>
</div>
@endsection @section('content')
<div class="row">
	<div class="col-md-12">
		<div class="  ibox animated fadeInDown">

			<div class="ibox">

				<div class="ibox-content">
					<div class="row">
						@if(Auth::guard('inventory')->user()->user_type == 'admin')
						<div class="col-md-offset-10 col-md-2 text-right">
							<a href="/inventory/createAgent" class="btn btn-sm btn-primary"><i
								class="fa fa-user-plus"></i> Create New User </a>
						</div>
						@endif
					</div>
					<form class="topic form-horizontal agentPassword">
						{!! csrf_field() !!} <input type="hidden" name="email"
							class="email">
					</form>
					<div class="table-responsive">
					<br>
						<table
							class="table footable table-bordered table-hover agentPassword">
							<thead>
								<tr>
									<th>Id</th>
									<th>Email</th>
									<th>Name</th>
									<th>User Type</th>
									<th>Date Registered</th>
									<th>Date Updated</th>
									@if(Auth::guard('inventory')->user()->user_type== 'admin')
									<th class="text-center">Actions</th> @endif

								</tr>
							</thead>
							<tbody>
								@foreach ($agents as $agent)
								<tr>
									
									<td><a href="agents/{{$agent->id}}">{{$agent->id}}</a></td>
									<td>{{$agent->email}}</td>
									<td>{{$agent->first_name.' '.$agent->last_name}}
									
									<td id="{{$agent->id}}">{{$agent->user_type}}</td>
									<td>{{$agent->created_at}}</td>
									<td>{{$agent->updated_at}}</td>
									@if(Auth::guard('inventory')->user()->user_type == 'admin')
									<td class="text-center">
										<div class="btn-group">
											<button data-toggle="dropdown"
												class="btn btn-primary btn-xs dropdown-toggle">
												Actions <span class="caret"></span>
											</button>
											<ul class="dropdown-menu">
												<li><a href="/inventory/agents/{{$agent->id}}">View Profile</a>
												
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

</div>
@endsection @section('scripts')
<script>
$(document).ready(function(){
	$('table').DataTable({
        dom: '<"html5buttons"B>Tgitp',
        buttons: [
            { extend: 'copy'},
            {extend: 'csv'},
            {extend: 'excel', title: 'Remote Staff Inc \n Agents'},
            {extend: 'pdf', title: 'Remote Staff Inc \n Agents'},

            {extend: 'print',
             customize: function (win){
                    $(win.document.body).addClass('white-bg');
                    $(win.document.body).css('font-size', '10px');

                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
            }
            }
        ]

    });
	
});

$('#agentPasswordResetLink').on('click', function(e) {
	
	$('input.email').val($(this).attr('value'));
	
	swal({
		title : "Are You Sure?",
		type : 'info',
		showCancelButton : true,
		closeOnConfirm : false,
		confirmButtonText : "Yes",
		showLoaderOnConfirm : true,
		disableButtonsOnConfirm : true,
	}, function() {
		$.ajax({
			type : "POST",
			url : "/admin/forgotPassword",
			data : $('form.agentPassword').serialize(),
		}).done(function() {
			swal('','Password Reset Link has been sent!', 'success');
		});

	});

});

$('a#agentChangeUserType').on('click',
		function() {
			var agentId = $(this).attr('value');
			var agentUserType = $(this).attr('name');

			if (agentUserType == 'agent') {
				agentUserType = 'admin';
			} else {
				agentUserType = 'agent';
			}
			$(this).attr('name',agentUserType);
			swal({
				title : "Are You Sure?",
				type : 'info',
				showCancelButton : true,
				closeOnConfirm : false,
				confirmButtonText : "Yes",
				showLoaderOnConfirm : true,
				disableButtonsOnConfirm : true,
			}, function() {
				$.ajax({
					headers : {
						'X-CSRF-Token' : $(
								'input[name="_token"]')
								.val()
					},
					type : 'PUT',
					url : '/inventory/changeAgentUserType',
					data : {
						id : agentId,
						userType : agentUserType
					}
				})
		.done(
				function(
						data) {
					if (data.success != true) {
						swal('',data.errors['id'],'warning');
						return false;
					} else {
						swal(
								{
									title : 'Success!',
									text : 'User type has been changed',
									type : 'success'
								},
								function() {
									$('td#'+ agentId).text(agentUserType);
								});
					}
				});

			});
			
		
		});
</script>
@endsection
