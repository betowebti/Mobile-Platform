<?php
if(! isset($head_included_video) || ! $head_included_video)
{
?>
<link href="{{ url('/widgets/video/assets/css/style.css') }}" rel="stylesheet" type="text/css">
<script src="{{ url('widgets/video/assets/js/app.js') }}"></script>
<?php
}
?>