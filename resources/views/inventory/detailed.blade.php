@extends('inventory.inventory') @section('title', 'RS | Inventory
Details') @section('header-page')
<div class="col-lg-10">
	<h2>Inventory Details</h2>
	<ol class="breadcrumb">
		<li><a href="index.html">Home</a></li>

		<li class="active"><strong>Inventory Details</strong></li>
	</ol>
</div>
@endsection @section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="ibox ">

			<div class="ibox-content">

				<div class="row">
					<div class="col-lg-3">
						<select id="stockType"class="form-control">
							<option selected value="">All</option>
							<option value="In-stock">In-stock</option>
							<option value="Borrowed">Borrowed</option>
							<option value="With Issue">With Issue</option>
							<option value="Broken">Broken</option>
						</select>
					</div>
					<div class="col-lg-2">
						<button class="btn btn-primary btn-block" data-toggle="modal"
							data-target="#itemLevel">Level</button>
					</div>
					<div class="col-lg-offset-2 col-lg-5">
						<div class="input-group m-b">
							<input type="text" class="form-control" id="filter"
								placeholder="Search...">
							<div class="input-group-btn">
								<button class="btn btn-white" id="itemAdvancedSearch"
									type="button">
									Search Options <span class="caret"></span>
								</button>
							</div>
						</div>
					</div>
					<div class="col-lg-12">
						<div class="tabs-container">
							<ul class="nav nav-tabs">
								<li class="active"><a id="itemType" data-toggle="tab" href="#tab-1"> All</a></li>
								<li class=""><a id="itemType"  data-toggle="tab" href="#tab-2">Laptop</a></li>
								<li class=""><a id="itemType"  data-toggle="tab" href="#tab-3">Mouse</a></li>
								<li class=""><a id="itemType"  data-toggle="tab" href="#tab-4">Headset</a></li>
								<li class=""><a id="itemType"  data-toggle="tab" href="#tab-5">Projector</a></li>

							</ul>
							<div class="tab-content">
								<div id="tab-1" class="tab-pane active">
									<div class="panel-body">
										<div class="table-responsive" id="stockAll">
											<table id="detailed" class="footable table table-hover" data-striping="false">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody>
													@foreach($items as $item)
													<tr>
														<td><a href="#" id="itemInfo">{{$item->itemNo}}</a></td>
														<td>{{$item->unique_id}}</td>
														<td>{{$item->stationNo}}</td>
														<td>{{$item->brand}}</td>
														<td>{{$item->model}}</td>
														<td>{{$item->itemStatus}}</td>
														<td>{{$item->morningClient}}</td>
														<td>{{$item->nightClient}}</td>
														<td>{{$item->created_at}}</td>
													</tr>
													@endforeach
												</tbody>
												<tfoot>
                                <tr>
                                    <td colspan="9">
                                        <ul class="pagination pull-right"></ul>
                                    </td>
                                </tr>
                                </tfoot>
											</table>
											
										</div>
									</div>
								</div>
								<div id="tab-2" class="tab-pane">
									<div class="panel-body">
										<div class="table-responsive">
											<table class="footable table table-hover" data-striping="false">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody id="stockLaptop">
													<tr>
														<td colspan="9"><div class="spiner-example">
																<div class="sk-spinner sk-spinner-three-bounce">
																	<div class="sk-bounce1"></div>
																	<div class="sk-bounce2"></div>
																	<div class="sk-bounce3"></div>
																</div>
															</div></td>
													</tr>
												</tbody>

											</table>
										</div>
									</div>
								</div>
								<div id="tab-3" class="tab-pane">
									<div class="panel-body">
										<div class="table-responsive" >
											<table class="footable table  table-hover" data-striping="false">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody id="stockMouse">
<tr>
														<td colspan="9"><div class="spiner-example">
																<div class="sk-spinner sk-spinner-three-bounce">
																	<div class="sk-bounce1"></div>
																	<div class="sk-bounce2"></div>
																	<div class="sk-bounce3"></div>
																</div>
															</div></td>
													</tr>
												</tbody>

											</table>
										</div>
									</div>
								</div>
								<div id="tab-4" class="tab-pane">
									<div class="panel-body">
										<div class="table-responsive" >
											<table class="footable table table-hover" data-striping="false">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody id="stockHeadset">
													<tr>
														<td colspan="9"><div class="spiner-example">
																<div class="sk-spinner sk-spinner-three-bounce">
																	<div class="sk-bounce1"></div>
																	<div class="sk-bounce2"></div>
																	<div class="sk-bounce3"></div>
																</div>
															</div></td>
													</tr>
												</tbody>

											</table>
										</div>
									</div>
								</div>
								<div id="tab-5" class="tab-pane">
									<div class="panel-body">
										<div class="table-responsive" >
											<table class="footable table table-hover" data-striping="false">
												<thead>
													<tr>
														<th>Item No</th>
														<th>Unique Id</th>
														<th>Station No</th>
														<th>Brand</th>
														<th>Model</th>
														<th>Status</th>
														<th>Morning Shift</th>
														<th>Night Shift</th>
														<th>Date Arrived</th>
													</tr>
												</thead>
												<tbody id="stockProjector">
<tr>
														<td colspan="9"><div class="spiner-example">
																<div class="sk-spinner sk-spinner-three-bounce">
																	<div class="sk-bounce1"></div>
																	<div class="sk-bounce2"></div>
																	<div class="sk-bounce3"></div>
																</div>
															</div></td>
													</tr>
												</tbody>

											</table>
										</div>
									</div>
								</div>
							</div>


						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div id="itemInfo" class="modal inmodal fade" role="dialog">
	<div class="modal-dialog modal-sm">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"></h4>
			</div>

			<div class="modal-body">
				<p>
					<strong>Status:</strong>&nbsp;<span id="itemInfoStatus"> </span>
				</p>
				<p>
					Brand: &nbsp; <span id="itemInfoBrand"></span>
				</p>
				<p>
					Model: &nbsp; <span id="itemInfoBrand"></span>
				</p>
				<p>
					Serial No.: &nbsp; <span id="itemInfoBrand"></span>
				</p>
				<p>
					Date Arrived: &nbsp; <span id="itemInfoBrand"></span>
				</p>
			</div>

			<div class="modal-footer">

				<button class="btn btn-white btn-w-m" data-dismiss="modal">Done</button>

			</div>
		</div>

	</div>
</div>

<div id="itemLevel" class="modal inmodal fade" role="dialog">
	<div class="modal-dialog modal-lg">

		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Summary</h4>
			</div>

			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<div id="stocked"></div>
					</div>
				</div>
			</div>

			<div class="modal-footer">

				<button class="btn btn-primary btn-w-m" data-dismiss="modal">Done</button>

			</div>
		</div>

	</div>
</div>

<script>
$(document).ready(function(){
	$('.footable').footable();

	
});

$('#itemLevel').on('shown.bs.modal', function() {
	$.ajax({
		type : "GET",
		url : "/inventory/detailed/itemLevel"
	}).done(function(data) {
		console.log(data);
		c3.generate({
			bindto : '#stocked',
			data : {
				x : 'x',
				columns : data,

				type : 'bar',
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




$('.nav-tabs a').click(function(){
	var iType = $(this).text();
	if($('tbody#stock'+ iType +' tr td:eq(0)').attr('colspan') == 9 ){
		$('tbody#stock'+ iType).html(
				'<tr><td colspan="9"><div class="spiner-example">'+
						'<div class="sk-spinner sk-spinner-three-bounce">'+
							'<div class="sk-bounce1"></div>'+
							'<div class="sk-bounce2"></div>'+
							'<div class="sk-bounce3"></div>'+
						'</div>'+
					'</div></td></tr>');
				$.ajax({
					type : "GET",
					url : "/inventory/detailed/stockItems",
					data : {
						itemType : iType,
					},
				}).done(function(data){
					var html;
					if(data == ""){
						html = "<tr><td class='text-center' colspan='9'>No Result Found</td><tr>"
					}else{
						$.each(data,function(index, v) {
							html += "<tr><td>" + v.itemNo +"</td><td>" + v.unique_id+ "</td>" +
									"<td>" + v.stationNo +"</td><td>" + v.brand+ "</td>"+
									"<td>" + v.model + "</td><td>"+ v.itemStatus+"</td>"+
									"<td>" + v.morningClient + "</td><td>"+ v.nightClient + "</td>" +
									"<td>" + v.created_at+ "</td></tr>";

						});
					}
					

					$('tbody#stock'+ iType).html(html);
					 $('.footable').trigger('footable_filter', {filter: $('select#stockType').val()});
					});
		}
	
});
$('select#stockType').change(function(){
	
	      $('.footable').trigger('footable_filter', {filter: $(this).val()});
	  
	
});

$('a#itemInfo').click(function(){
	$('div#itemInfo').modal('toggle');
	$('h4.modal-title').text($(this).text());
	$('span#itemInfoStatus').text($(this).parents('tr:first').find('td:eq(4)').text());
});
</script>
@endsection
