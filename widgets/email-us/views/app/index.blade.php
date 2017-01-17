<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}">
<?php
$email_address = \Mobile\Controller\WidgetController::getData($page, 'email_address', '');
$subject = \Mobile\Controller\WidgetController::getData($page, 'subject', '');

if($email_address != '')
{
?>
		<a href="mailto:{{ $email_address }}?subject={{ $subject }}" class="button button-large button-balanced button-full icon-left ion-email">{{ $email_address }}</a>
<?php
if(! \Auth::check())
{
?>
<script>
window.open('mailto:<?php echo $email_address . '?subject=' . $subject ?>', '_system');
</script>
<?php
	}
}
?>
	</ion-content>
</ion-view>