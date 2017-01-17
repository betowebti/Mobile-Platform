<!doctype html>
<html lang="{{ App::getLocale() }}">
	<head>
		<title>{{ $page_title }}</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<meta name="description" content="{{ $page_description }}" />
		<link rel="shortcut icon" href="{{ $favicon }}" type="image/x-icon"/>
		<link rel="stylesheet" href="{{ url('/website/lander/assets/css/style.min.css') }}" />
		<!--[if lt IE 9]>
		<script src="{{ url('assets/js/ie.min.js') }}"></script>
		<![endif]-->
	</head>
	<body class="{{ $scheme }} {{ $phone }}" data-spy="scroll" data-target="#main-navbar" data-offset="60">
<?php if ($google_analytics != '') { ?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', '{{ $google_analytics }}', 'auto');
  ga('send', 'pageview');

</script>
<?php } ?>

@yield('content')

	<script src="{{ url('/website/lander/assets/js/app.min.js') }}"></script>
<?php if ($demo_mode == 1) { ?>
	<script src="{{ url('/website/lander/assets/js/demo.js') }}"></script>
<?php } ?>
	</body>
</html>