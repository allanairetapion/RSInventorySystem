@extends('tickets.ticketadminlayout') @section('body')
<div class="row animated fadeInRight">
	<div class="col-md-3">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h4>
					<strong>{{$profile->first_name.' '.$profile->last_name}}</strong>
				</h4>
			</div>
			<div>
				<div class="ibox-content no-padding border-left-right">
					<img alt="image" class="img-responsive"
						src="/img/agents/{{$profile->id}}.jpg"
						onerror="this.src = '/img/default-profile.jpg'">
				</div>
				<div class="ibox-content profile-content">
					<p>
						<i class="fa fa-user"></i> : {{$profile->id}}
					</p>
					<p>
						<i class="fa fa-envelope-o"></i> : {{$profile->email}}
					</p>

					<br>
					<div>
						<table class="table">
							<tbody>
								<tr>
									<td><label class="label label-success m-r-sm">{{count($closedTickets)}}</label> Closed
									</td>
								</tr>
								<tr>
									<td><label class="label label-primary m-r-sm">{{count($assignedTickets)}}</label>
										Assigned</td>
								</tr>
								<tr>
									<td><label class="label label-danger m-r-sm">{{count($unresolvedTickets)}}</label>
										Unresolved</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-9">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Activities</h5>

			</div>

			<div class="ibox-content ">
				<div>
                                <div id="slineChart" ></div>
                            </div>
				
			</div>
		</div>
	</div>
	
	
<script type="text/javascript">
$(document).ready(function(){
	$.ajax({
		type : "GET",
		url : "/agents/ticketStats"
	}).done(function(data) {
		console.log(data);
		c3.generate({
			bindto : '#slineChart',
			data : {
				x : 'x',
				columns : data,
				colors:{
                    Assigned: '#5cb85c',
                    Unresolved:'#d9534f',
                    Closed: '#337ab7'
                },
				type : 'spline',
				groups : [['data1', 'data2']]
			},
			axis : {
				x : {
					type : 'category',

				}
			}
		});
		
	
});

});

</script>
	@endsection