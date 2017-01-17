<?php
if(! isset($head_included_flickr) || ! $head_included_flickr)
{
?>
<link href="{{ url('/widgets/flickr/assets/css/style.css') }}" rel="stylesheet" type="text/css">
<script src="{{ url('widgets/flickr/assets/js/app.js') }}"></script>
<?php
}
?>