<!DOCTYPE html>
<html>

	<head>

		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<title>@yield('title')</title>

		<link href="/css/bootstrap.min.css" rel="stylesheet">
		<link href="/font-awesome/css/font-awesome.css" rel="stylesheet">
		<link href="/css/animate.css" rel="stylesheet">
		<link href="/css/style.css" rel="stylesheet">
		
		@section('css')
		
		@show
		
		@section('scripts')
		
		@show
		
		<link href="/css/plugins/ladda/ladda-themeless.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="/css/plugins/jQueryUI/jquery-ui.css" />
		
		<script src="/js/jquery-2.1.1.js"></script>
		<script src="/js/bootstrap.min.js"></script>

		<script src="/js/plugins/metisMenu/jquery.metisMenu.js"></script>
		
		<!-- Custom and plugin javascript -->
		<script src="/js/inspinia.js"></script>
		<script src="/js/plugins/pace/pace.min.js"></script>

		<!-- Ladda -->
		<script src="/js/plugins/ladda/spin.min.js"></script>
		<script src="/js/plugins/ladda/ladda.min.js"></script>
		<script src="/js/plugins/ladda/ladda.jquery.min.js"></script>

		<script type="text/javascript" src="/js/jquery-ui-1.10.4.min.js"></script>
		<script src="/js/ticketsClients.js"></script>

	</head>

	<body class="white-bg top-navigation" >
		

		@section('body')
		@show


	</body>

	
</html>
