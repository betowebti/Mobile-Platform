<!doctype html>
<html>
<head>
	<title></title>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">

	<meta http-equiv="cleartype" content="on">
	<link rel="stylesheet" href="{{ url('/assets/css/mobile.css?v=' . Config::get('system.mobile_version')) }}" />
	<script src="{{ url('/assets/js/mobile.js?v=' . Config::get('system.mobile_version')) }}"></script>
	<link rel="stylesheet" href="{{ url('themes/_boilerplate/assets/css/style.css') }}" id="themestyle" />
	<link rel="stylesheet" href="{{ url('themes/_boilerplate/assets/css/custom.css') }}" id="themecustomstyle" />
    <style type="text/css">
    body {
        background:url({{ url('static/app-backgrounds/blue-bokeh.png') }}) no-repeat;
        background-size: cover !important;
    }
    </style>
</head>
<body>

    <div class="bar bar-header bar-dark">
      <h1 class="title" id="app-title">{{ $app_title }}</h1>
    </div>

<br>
<br>
<br>

	<ion-view>
		<ion-content padding="true" class="has-header">
	
			<div class="transparent">{{ $app_message }}</div>
	
		</ion-content>
	</ion-view>

</body>
</html>