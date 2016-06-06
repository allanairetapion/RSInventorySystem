@extends('tickets.ticketadminlayout')
@section('body')
<div class="row">
	<div class="col-md-8">
		<div class="  ibox animated fadeInDown">

			<div class="ibox">
				<div class="ibox-title">
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
									<th>Department</th>
									<th>Password</th>

								</tr>
							</thead>
							<tbody>
								@foreach ($clients as $client)
								<tr class="gradeX">
									<td>{{$client->id}}</td>
									<td>{{$client->email}}</td>
									<td>{{$client->department}}</td>
									<td>
									<button type="button" class="btn btn-sm btn-primary clientPassword" value="{{$client->email}}">
										Send Password Reset Link
									</button></td>

								</tr>
								@endforeach

							</tbody>

						</table>

					</form>

				</div>

			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="ibox ">

                        <div class="ibox-content">
                            <div class="tab-content">
                                <div id="contact-1" class="tab-pane active">
                                    <div class="row m-b-lg">
                                        <div class="col-lg-4 text-center">
                                            <h2>Nicki Smith</h2>

                                            <div class="m-b-sm">
                                                <img alt="image" class="img-circle" src="/img/a2.jpg"
                                                     style="width: 62px">
                                            </div>
                                        </div>
                                        <div class="col-lg-8">
                                            <strong>
                                                About me
                                            </strong>

                                            <p>
                                                Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                                tempor incididunt ut labore et dolore magna aliqua.
                                            </p>
                                            <button type="button" class="btn btn-primary btn-sm btn-block"><i
                                                    class="fa fa-envelope"></i> Send Message
                                            </button>
                                        </div>
                                    </div>
                                   
                                    <div class="full-height-scroll">

                                        <strong>Last activity</strong>

                                        <ul class="list-group clear-list">
                                            <li class="list-group-item fist-item">
                                                <span class="pull-right"> 09:00 pm </span>
                                                Please contact me
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 10:16 am </span>
                                                Sign a contract
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 08:22 pm </span>
                                                Open new shop
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 11:06 pm </span>
                                                Call back to Sylvia
                                            </li>
                                            <li class="list-group-item">
                                                <span class="pull-right"> 12:00 am </span>
                                                Write a letter to Sandra
                                            </li>
                                        </ul>
                                        <strong>Notes</strong>
                                        <p>
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                                            tempor incididunt ut labore et dolore magna aliqua.
                                        </p>
                                        <hr/>
                                   
                                    </div>
                                </div>

	</div>
</div>
@endsection
