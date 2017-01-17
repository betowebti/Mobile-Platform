<?php
if(! isset($head_included_photos) || ! $head_included_photos)
{
?>
<link href="{{ url('/widgets/photos/assets/css/style.css') }}" rel="stylesheet" type="text/css">
<script src="{{ url('widgets/photos/assets/js/ion-gallery.min.js') }}"></script>
<script src="{{ url('widgets/photos/assets/js/app.js') }}"></script>
<?php
}
?>