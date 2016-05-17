<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin</title>

    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/iCheck/custom.css" rel="stylesheet">
    <link href="/css/animate.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <link href="/css/plugins/sweetalert/sweetalert.css" rel="stylesheet">
    <script src="/js/plugins/sweetalert/sweetalert.min.js"></script>
    <script src="/js/plugins/chartJs/Chart.min.js"></script>

	<style>
		.modal {
  text-align: center;
}

@media screen and (min-width: 768px) { 
  .modal:before {
    display: inline-block;
    vertical-align: middle;
    content: " ";
    height: 100%;
  }
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
	</style>
</head>

<body class="top-navigation">

    <div id="wrapper">
        <div id="page-wrapper" class="gray-bg">
        	<div class="row border-bottom white-bg">
        <nav class="navbar navbar-static-top" role="navigation">
            <div class="navbar-header">
                <button aria-controls="navbar" aria-expanded="false" data-target="#navbar" data-toggle="collapse" class="navbar-toggle collapsed" 

type="button">
                    <i class="fa fa-reorder"></i>
                </button>
                <a href="#" class="navbar-brand">RS Tickets</a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a> Hello, {{ Auth::guard('admin')->user()->adminProfile ? Auth::guard('admin')->user()->adminProfile->first_name.' '.Auth::guard('admin')->user()->adminProfile->last_name : '' }}!</a>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Tickets<span 

class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="#">Create Ticket</a></li>
                            <li><a href="#">Add new topic</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Ticket Status <span 

class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="#">Open</a></li>
                            <li><a href="#">Pending</a></li>
                            <li><a href="#">Closed</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Ticket Status</a></li>
                            <li><a href="#">Search Ticket</a></li>
                            <li class="divider"></li>
                            <li><a href="#">My Tickets</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a aria-expanded="false" role="button" href="#" class="dropdown-toggle" data-toggle="dropdown"> Agents <span 

class="caret"></span></a>
                        <ul role="menu" class="dropdown-menu">
                            <li><a href="">My Agents</a></li>
                            <li><a href="admin/createAgent">Create New Agent/Admin</a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#"> Clients </a>
                    </li>
                    <li class="dropdown">
                        <a href="#"> Restrictions </a>
                    </li>

                </ul>
                <ul class="nav navbar-top-links navbar-right">
                	<li>
                		<a href="#" class="adminEmail">{{ Auth::guard('admin')->user()->email   }}</a>
                	</li>
                    <li>
                        <a href="/admin/logout">
                            <span class="glyphicon glyphicon-log-out"></span>&nbsp;Log out
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        </div>
        @section('body')
        @show
        
        <div class="footer">
            <div class="pull-right">
                10GB of <strong>250GB</strong> Free.
            </div>
            <div>
                <strong>Copyright</strong> Example Company &copy; 2014-2015
            </div>
        </div>

        </div>
        </div>



   <!-- Mainly scripts -->
    <script src="/js/jquery-2.1.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
    <script src="/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

    <!-- Custom and plugin javascript -->
    <script src="/js/inspinia.js"></script>
    <script src="/js/plugins/pace/pace.min.js"></script>
    
     <script src="/js/plugins/sweetalert/sweetalert.min.js"></script>

    <!-- Flot -->
    <script src="/js/plugins/flot/jquery.flot.js"></script>
    <script src="/js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="/js/plugins/flot/jquery.flot.resize.js"></script>

    <!-- ChartJS-->
    <script src="/js/plugins/chartJs/Chart.min.js"></script>

    <!-- Peity -->
    <script src="/js/plugins/peity/jquery.peity.min.js"></script>
    <!-- Peity demo -->
    <script src="/js/demo/peity-demo.js"></script>

 
    
    <script>
        $(document).ready(function() {
            var doughnutData = [
                {
                    value: 300,
                    color: "#a3e1d4",
                    highlight: "#1ab394",
                    label: "App"
                },
                {
                    value: 50,
                    color: "#dedede",
                    highlight: "#1ab394",
                    label: "Software"
                },
                {
                    value: 100,
                    color: "#A4CEE8",
                    highlight: "#1ab394",
                    label: "Laptop"
                }
            ];
 			var doughnutOptions = {
                segmentShowStroke: true,
                segmentStrokeColor: "#fff",
                segmentStrokeWidth: 2,
                percentageInnerCutout: 45, // This is 0 for Pie charts
                animationSteps: 100,
                animationEasing: "easeOutBounce",
                animateRotate: true,
                animateScale: false

            };

            var ctx = document.getElementById("doughnutChart").getContext("2d");
            var DoughnutChart = new Chart(ctx).Doughnut(doughnutData, doughnutOptions);
            
            });
    </script>
  
    <script type="text/javascript">
       
  $('button.add-account').click(function(e) {
  	 e.preventDefault(); 
  	 var email = $('.adminEmail').text();
  	 var password ="";
  	 var checkEmail = $('.email').val();
  	 
     var check = $.post("/checkEmail", checkEmail);
     check.done(function(data){
     	if(data.fail){
     		
     	}
     });     
    swal({
      title: "Create New Agent", 
      text: "To continue, Please enter your password:" , 
      type: "input",
      inputType: "password",
      showCancelButton: true,
      closeOnConfirm: false
    }, function(inputValue) {    		
    	if(inputValue != ""){
	      $.ajax({
	                type: 'get',
	                url: '/checkPassword'+"/"+inputValue,
	            })
	            .done(function(data){
	            	if(data =="true"){
	            		swal('Add','add','success');
	            	}
	            	else{
	            		swal.showInputError("Wrong Password");
	            		return false;
	            	}
	            });
	     } 
     else {
     	swal.showInputError("You need to type in your password in order to do this!");
     	return false;
     }
    });
  });
  

  </script>
   
</body>

</html>
