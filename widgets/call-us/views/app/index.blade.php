<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}">
<?php
$phone_number = \Mobile\Controller\WidgetController::getData($page, 'phone_number', '');

if($phone_number != '')
{
?>
		<a href="tel:{{ $phone_number }}" class="button button-large button-balanced button-full icon-left ion-ios-telephone">{{ $phone_number }}</a>
<?php
if(! \Auth::check())
{
?>
<script>
window.open('tel:<?php echo $phone_number ?>', '_self');
</script>
<?php
	}
}
?>
	</ion-content>
</ion-view>