@extends('tickets.ticketclientlayout')
@section('body')
<div class="row">
	<div class="col-md-6">
		<div class="ibox animated bounceInLeft float-e-margins">
			<div class="ibox-title">
				<h5>Messages</h5>
			</div>
			<div class="ibox-content ibox-heading">
				<h3><i class="fa fa-envelope-o"></i> New messages</h3>
				<small><i class="fa fa-tim"></i> You have 22 new messages</small>
			</div>
			<div class="ibox-content">
				<div class="feed-activity-list">

					<div class="feed-element">
						<div class="panel-group" id="accordion">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
        Collapsible Group 1</a>
      </h4>
    </div>
    <div id="collapse1" class="panel-collapse collapse in">
      <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
      minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
      commodo consequat.</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse2">
        Collapsible Group 2</a>
      </h4>
    </div>
    <div id="collapse2" class="panel-collapse collapse">
      <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
      minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
      commodo consequat.</div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapse3">
        Collapsible Group 3</a>
      </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse">
      <div class="panel-body">Lorem ipsum dolor sit amet, consectetur adipisicing elit,
      sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad
      minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea
      commodo consequat.</div>
    </div>
  </div>
</div>
					</div>
									
					

				</div>
			</div>
		</div>
	</div>

<div class="col-md-3 animated bounceInLeft">
	<div class="widget style2 yellow-bg">
			<div class="row">
				<div class="col-xs-4">
					<i class="glyphicon glyphicon-time  fa-5x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span> Pending Tickets </span>
					<h2 class="font-bold">0</h2>
					<small>Total</small>
				</div>
			</div>
		</div>
</div>

<div class="col-md-3 animated bounceInLeft">
	<a href="/tickets/createTicket">
	<div class="widget style2 navy-bg">
			<div class="row">
				<div class="col-xs-4">
					<i class="fa fa-ticket fa-5x"></i>
				</div>
				<div class="col-xs-8 text-right">
					<span>To submit a ticket</span>
					<h2 class="font-bold">Click here</h2>					
				</div>
			</div>
		</div>
	</a>
</div>



</div>
@endsection