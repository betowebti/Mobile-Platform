<?php
if(! isset($head_included_facebook) || ! $head_included_facebook)
{
?>
<link href="{{ url('/widgets/facebook/assets/css/styles.css') }}" rel="stylesheet" type="text/css">
<script src="{{ url('widgets/facebook/assets/js/jquery.tmpl.min.js') }}"></script>
<script src="{{ url('widgets/facebook/assets/js/fbwall.min.js') }}"></script>
<?php
}
?>