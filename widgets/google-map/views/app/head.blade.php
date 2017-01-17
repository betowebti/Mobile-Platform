<?php
if(! isset($head_included_googleMap) || ! $head_included_googleMap)
{
?>
<link href="{{ url('/widgets/google-map/assets/css/style.css') }}" rel="stylesheet" type="text/css">
<script src="//maps.googleapis.com/maps/api/js?sensor=true"></script>
<script src="{{ url('widgets/google-map/assets/js/app.js') }}"></script>
<?php
}
?>