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
	<div class="col-lg-12">
		<div class="ibox float-e-margins">
			<div class="ibox-title">
				<h5>Activities</h5>

			</div>

			<div class="ibox-content inspinia-timeline">

				<div class="timeline-item">
					<div class="row">
						<div class="col-xs-3 date">
							<i class="fa fa-briefcase"></i> Mar 18, 2016 2:00 am
						</div>
						<div class="col-xs-7 content no-top-border">
							<p class="m-b-xs">
								<strong>Meeting</strong>
							</p>

							

							
						</div>
					</div>
				</div>
				<div class="timeline-item">
					<div class="row">
						<div class="col-xs-3 date">
							<i class="fa fa-file-text"></i> 7:00 am <br /> <small
								class="text-navy">3 hour ago</small>
						</div>
						<div class="col-xs-7 content">
							<p class="m-b-xs">
								<strong>Send documents to Mike</strong>
							</p>
							<p>Lorem Ipsum is simply dummy text of the printing and
								typesetting industry. Lorem Ipsum has been the industry's
								standard dummy text ever since.</p>
						</div>
					</div>
				</div>
				<div class="timeline-item">
					<div class="row">
						<div class="col-xs-3 date">
							<i class="fa fa-coffee"></i> 8:00 am <br />
						</div>
						<div class="col-xs-7 content">
							<p class="m-b-xs">
								<strong>Coffee Break</strong>
							</p>
							<p>Go to shop and find some products. Lorem Ipsum is simply dummy
								text of the printing and typesetting industry. Lorem Ipsum has
								been the industry's.</p>
						</div>
					</div>
				</div>
				<div class="timeline-item">
					<div class="row">
						<div class="col-xs-3 date">
							<i class="fa fa-phone"></i> 11:00 am <br /> <small
								class="text-navy">21 hour ago</small>
						</div>
						<div class="col-xs-7 content">
							<p class="m-b-xs">
								<strong>Phone with Jeronimo</strong>
							</p>
							<p>Lorem Ipsum has been the industry's standard dummy text ever
								since.</p>
						</div>
					</div>
				</div>
				<div class="timeline-item">
					<div class="row">
						<div class="col-xs-3 date">
							<i class="fa fa-user-md"></i> 09:00 pm <br /> <small>21 hour ago</small>
						</div>
						<div class="col-xs-7 content">
							<p class="m-b-xs">
								<strong>Go to the doctor dr Smith</strong>
							</p>
							<p>Find some issue and go to doctor.</p>
						</div>
					</div>
				</div>
				<div class="timeline-item">
					<div class="row">
						<div class="col-xs-3 date">
							<i class="fa fa-user-md"></i> 11:10 pm
						</div>
						<div class="col-xs-7 content">
							<p class="m-b-xs">
								<strong>Chat with Sandra</strong>
							</p>
							<p>Lorem Ipsum has been the industry's standard dummy text ever
								since.</p>
						</div>
					</div>
				</div>
				<div class="timeline-item">
					<div class="row">
						<div class="col-xs-3 date">
							<i class="fa fa-comments"></i> 12:50 pm <br /> <small
								class="text-navy">48 hour ago</small>
						</div>
						<div class="col-xs-7 content">
							<p class="m-b-xs">
								<strong>Chat with Monica and Sandra</strong>
							</p>
							<p>Web sites still in their infancy. Various versions have
								evolved over the years, sometimes by accident, sometimes on
								purpose (injected humour and the like).</p>
						</div>
					</div>
				</div>
				<div class="timeline-item">
					<div class="row">
						<div class="col-xs-3 date">
							<i class="fa fa-phone"></i> 08:50 pm <br /> <small
								class="text-navy">68 hour ago</small>
						</div>
						<div class="col-xs-7 content">
							<p class="m-b-xs">
								<strong>Phone to James</strong>
							</p>
							<p>Various versions have evolved over the years, sometimes by
								accident, sometimes on purpose (injected humour and the like).</p>
						</div>
					</div>
				</div>
				<div class="timeline-item">
					<div class="row">
						<div class="col-xs-3 date">
							<i class="fa fa-file-text"></i> 7:00 am <br /> <small
								class="text-navy">3 hour ago</small>
						</div>
						<div class="col-xs-7 content">
							<p class="m-b-xs">
								<strong>Send documents to Mike</strong>
							</p>
							<p>Lorem Ipsum is simply dummy text of the printing and
								typesetting industry. Lorem Ipsum has been the industry's
								standard dummy text ever since.</p>
						</div>
					</div>
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