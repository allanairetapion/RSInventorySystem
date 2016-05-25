@extends('layouts.master')

@section('title', 'Remote Staff Inventory System')

@section('sidebar')


<html>
<body>	
<li class="nav-header">
	<div class="dropdown profile-element">

		
		<span> <img alt="image" class="img-circle" height="50px" width="50px" src="data:image/jpeg;base64,<?php echo base64_encode( Auth::guard('inventory')->user()->image); ?>"> </span>
	<a data-toggle="dropdown" class="dropdown-toggle" href="#">
		 <span class="clear"> <span class="block m-t-xs">{{{ Auth::guard('inventory')->user()->first_name }}} {{{ Auth::guard('inventory')->user()->last_name }}}
		 	<strong class="font-bold"> </strong> </span> <span class="text-muted text-xs block">{{{ Auth::guard('inventory')->user()->user_type }}}
		 		<b class="caret"></b></span> </span> </a> 
		<ul class="dropdown-menu animated fadeInRight m-t-xs">
			<li>
				<a href="#">Change Profile Picture</a>
			</li>
			<li>
				<a href="accounts_management">Manage Accounts</a>
			</li>
			<li>
			<li>
				<a href="change_pass">Change Password</a>
			</li>

			<li class="divider"></li>
			<li>
				<a href="/inventory/login">Logout</a>
			</li>
		</ul>
	</div>
	<div class="logo-element">
		RS
	</div>
</li>
<li class="active">
	<a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboards</span> <span class="fa arrow"></span></a>
	<ul class="nav nav-second-level">
		<li class="active">
			<a href="InventorySys/index">Dashboard v.1</a>
		</li>
		<li>
			<a href="dashboard_2.html">Dashboard v.2</a>
		</li>
		<li>
			<a href="dashboard_3.html">Dashboard v.3</a>
		</li>
		<li>
			<a href="dashboard_4_1.html">Dashboard v.4</a>
		</li>
		<li>
			<a href="dashboard_5.html">Dashboard v.5 <span class="label label-primary pull-right">NEW</span></a>
		</li>
	</ul>
</li>
<li>
	<a href="#"><i class="fa fa-sitemap"></i> <span class="nav-label">Menu Levels </span><span class="fa arrow"></span></a>
	<ul class="nav nav-second-level collapse">
		<li>
			<a href="#">Third Level <span class="fa arrow"></span></a>
			<ul class="nav nav-third-level">
				<li>
					<a href="#">Third Level Item</a>
				</li>
				<li>
					<a href="#">Third Level Item</a>
				</li>
				<li>
					<a href="#">Third Level Item</a>
				</li>

			</ul>
		</li>
		<li>
			<a href="#">Second Level Item</a>
		</li>
		<li>
			<a href="#">Second Level Item</a>
		</li>
		<li>
			<a href="#">Second Level Item</a>
		</li>
	</ul>
</li>

@endsection

@section('header-page')

<div class="">
	<h2>Welcome {{{ Auth::guard('inventory')->user()->first_name }}}!</h2>

</div>


/* Graphs */
<div class="row">
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h5>Percentage distribution</h5>
                                <h2>42/20</h2>
                                <div class="text-center">
                                    <div id="sparkline5"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h5>Percentage division</h5>
                                <h2>100/54</h2>
                                <div class="text-center">
                                    <div id="sparkline6"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h5>Percentage distribution</h5>
                                <h2>685/211</h2>
                                <div class="text-center">
                                    <div id="sparkline7"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="ibox">
                            <div class="ibox-content">
                                <h5>Percentage division</h5>
                                <h2>240/32</h2>
                                <div class="text-center">
                                    <div id="sparkline8"></div>
                                </div>
                            </div>
                        </div>
                    </div>


    <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Sparkline -->
    <script src="/js/plugins/sparkline/jquery.sparkline.min.js"></script>

    <!-- Peity -->
    <script src="/js/plugins/peity/jquery.peity.min.js"></script>
    <script src="/js/demo/peity-demo.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/js/inspinia.js"></script>
    <script src="/js/plugins/pace/pace.min.js"></script>

    <script>
        $(document).ready(function() {

            var sparklineCharts = function(){
          
                 $("#sparkline5").sparkline([1, 4], {
                     type: 'pie',
                     height: '140',
                     sliceColors: ['#1ab394', '#F5F5F5']
                 });

                 $("#sparkline6").sparkline([5, 3], {
                     type: 'pie',
                     height: '140',
                     sliceColors: ['#1ab394', '#F5F5F5']
                 });

                 $("#sparkline7").sparkline([2, 2], {
                     type: 'pie',
                     height: '140',
                     sliceColors: ['#ed5565', '#F5F5F5']
                 });

                 $("#sparkline8").sparkline([2, 3], {
                     type: 'pie',
                     height: '140',
                     sliceColors: ['#ed5565', '#F5F5F5']
                 });
            };

            var sparkResize;

            $(window).resize(function(e) {
                clearTimeout(sparkResize);
                sparkResize = setTimeout(sparklineCharts, 500);
            });

            sparklineCharts();


        });
    </script>
   </body>
   </html>




@endsection
@section('content')
Remote Staff Inventory Management System

@endsection
