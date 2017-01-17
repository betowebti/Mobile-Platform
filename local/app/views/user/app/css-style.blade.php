<!-- skipmin --><style type="text/css">
body,
ion-nav-view,
ion-nav-view .pane {
<?php
$app_bg = url('/themes/' . $app->theme . '/assets/img/background-phone.png');
$app_bg = ($app->background_smarthpones_file_name != '') ? $app->background_smarthpones->url('bg') : $app_bg;

if(strpos($app_bg, '/1x1.gif') === false)
{
?>
	background:url(<?php echo $app_bg ?>) no-repeat center center !important;
	-webkit-background-size: cover !important;
	-moz-background-size: cover !important;
	-o-background-size: cover !important;
	background-size: cover !important;
}
<?php
}

foreach($app->appPages as $page)
{
	$bodyClass = camel_case('c-' . $page->slug);
	$bg = ($page->background_smarthpones_file_name != NULL) ? 'url("' . $page->background_smarthpones->url('bg') . '")' : 'transparent';

    if($bg != 'transparent')
    {
?>
body.<?php echo $bodyClass ?> ion-content { background:<?php echo $bg ?> no-repeat top center !important;
	-webkit-background-size: cover !important;
	-moz-background-size: cover !important;
	-o-background-size: cover !important;
	background-size: cover !important;
}
<?php
    }
}
?>
.card {
	margin-top:5px;
}
.m-top {
	margin-top:10px;
}
.text-center {
    text-align:center;
}
.transparent {
	width:100%;
	display:list-item;
	margin-bottom:10px;
}
.list {
	width:100%;
	display:list-item;
}
.transparent ol,
.transparent ul {
	list-style: inside;
}
.transparent ol li,
.transparent ul li{
	margin-left:5px;
}
.transparent ol li {
	list-style: decimal;
	margin-left:25px;
}
.icon-button:before {
	margin-top:4px !important;
}
.no-padding {
	padding:0;
}
/* Fix body text color popup */
.popup-container .popup .popup-head .popup-title,
.popup-container .popup .popup-head .popup-sub-title,
.popup-container .popup .popup-body span {
	color:#000000 !important;
}

</style>{{--skipmin--}}