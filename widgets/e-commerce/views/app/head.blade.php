<?php
if(! isset($head_included_eCommerce) || ! $head_included_eCommerce)
{
?>
<link href="{{ url('/widgets/e-commerce/assets/css/style.css') }}" rel="stylesheet" type="text/css">
<script src="{{ url('widgets/e-commerce/assets/js/simpleCart.min.js') }}"></script>
<script src="{{ url('widgets/e-commerce/assets/js/app.js') }}"></script>
<?php
}
?>