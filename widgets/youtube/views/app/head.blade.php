<?php
if(! isset($head_included_youtube) || ! $head_included_youtube)
{
?>
<link href="{{ url('/widgets/youtube/assets/css/youmax.css') }}" rel="stylesheet" type="text/css">
<script src="{{ url('widgets/youtube/assets/js/youmax.min.js') }}"></script>
<?php
}
?>