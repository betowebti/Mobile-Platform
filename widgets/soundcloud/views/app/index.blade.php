<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}">
<?php
if(count($soundclouds) > 0)
{
	foreach($soundclouds as $soundcloud)
	{
		// Show soundcloud
		if($soundcloud->code)
		{
			echo '<div class="card list">';
			echo $soundcloud->code;
			echo '</div>';
		}
	}
}
?>
	</ion-content>
</ion-view>