<?php
if(! isset($head_included_map) || ! $head_included_map)
{
	$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
?>
<script src="{{ $protocol }}cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.js"></script>
<link rel="stylesheet" href="{{ $protocol }}cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/leaflet.css">
<link href="{{ url('/widgets/map/assets/css/style.css') }}" rel="stylesheet" type="text/css">
<link href="{{ url('/widgets/map/assets/vendor/leaflet-routing-machine/leaflet-routing-machine.css') }}" rel="stylesheet" type="text/css">
<script src="{{ url('widgets/map/assets/vendor/angular-leaflet/angular-leaflet-directive.min.js') }}"></script>
<script src="{{ url('widgets/map/assets/vendor/leaflet-routing-machine/leaflet-routing-machine.min.js') }}"></script>
<script src="{{ url('widgets/map/assets/js/app.js') }}"></script>
<?php
}
?>