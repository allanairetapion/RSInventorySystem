@extends('tickets.ticketadminlayout')
@section('body')
<div class="animated fadeInRight">
<div class="mail-box-header">
	<div class="pull-right tooltip-demo">
		<a href="mailbox.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to draft folder"><i class="fa fa-pencil"></i> Draft</a>
		<a href="mailbox.html" class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> Discard</a>
	</div>
	<h2 class="font-bold"> Reply</h2>
</div>
<div class="mail-box">

	<div class="mail-body">

		<form class="form-horizontal" method="get">
			<div class="form-group">
				<label class="col-sm-1 control-label">To:</label>

				<div class="col-sm-11">
					<input type="text" class="form-control" value="{{Session::get('email')}}">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-1 control-label">Subject:</label>

				<div class="col-sm-11">
					<input type="text" class="form-control" value="">
				</div>
			</div>
		</form>

	</div>
	<div class="mail-text h-200">

		<div class="ticketsummernote">
			

		</div>
		<div class="clearfix"></div>
	</div>
	<div class="mail-body text-right tooltip-demo">
		<a href="mailbox.html" class="btn btn-sm btn-primary" data-toggle="tooltip" data-placement="top" title="Send"><i class="fa fa-reply"></i> Send</a>
		<a href="mailbox.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Discard email"><i class="fa fa-times"></i> Discard</a>
		<a href="mailbox.html" class="btn btn-white btn-sm" data-toggle="tooltip" data-placement="top" title="Move to draft folder"><i class="fa fa-pencil"></i> Draft</a>
	</div>
	<div class="clearfix"></div>

</div>

</div>
@endsection