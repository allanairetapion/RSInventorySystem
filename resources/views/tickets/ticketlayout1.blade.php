
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>@yield('title')</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="/css/plugins/iCheck/custom.css" rel="stylesheet">
		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">

		<script src="/js/bootstrap.min.js"></script>
		<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/jquery-ui-1.10.4.min.js"></script>
		<script type="text/javascript" src="/js/jquery-ui-1.10.4.min.js"></script>
		<link rel="stylesheet" type="text/css" href="/css/plugins/jQueryUI/jquery-ui.css" />

	</head>

	<body class="white-bg" >

		@section('body')
		@show

	</body>

	<script>
		 $(document).ready(function() {

			$(function() {
				$("input.department").keyup(function() {
					$("input.department").autocomplete({
						source : "{{URL('/search')}}",
						minLength : 1
					});
					$("#auto").autocomplete("widget").height(200);
				});
			});
		});

	
	</script>
</html>
