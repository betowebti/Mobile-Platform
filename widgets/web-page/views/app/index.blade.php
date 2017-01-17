<ion-view title="{{ $page->name }}">
    <ion-content padding="<?php echo ($new_window == 0) ? 'false' : 'true'; ?>" class="{{ $app->content_classes }}" overflow-scroll="true">
<?php
if($url != '')
{
	if($new_window == 0)
	{
?>
		<iframe src="{{ $url }}" frameborder="0" style="width:100%; min-height:100%"></iframe>
<?php
	}
	else
	{
?>
		<a href="{{ $url }}" class="button button-large button-balanced button-full icon-left ion-link" target="_blank">{{ $button_text }}</a>
<?php
		if(! \Auth::check())
		{
?>
<script>
window.open('<?php echo $url ?>', '_system');
</script>
<?php
		}
	}
}
?>
	</ion-content>
</ion-view>