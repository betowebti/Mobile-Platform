<?php
if(! isset($head_included_loyaltyCards) || ! $head_included_loyaltyCards)
{
?>
<link href="{{ url('/widgets/loyalty-cards/assets/css/style.css') }}" rel="stylesheet" type="text/css">
<script src="{{ url('widgets/loyalty-cards/assets/js/app.js') }}"></script>
<?php
}
?>