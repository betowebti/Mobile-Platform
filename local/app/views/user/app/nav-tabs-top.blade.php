<ion-tabs class="tabs-icon-top has-tabs-top tabs-top">
<?php
foreach($app->appPages as $page)
{
	if($page->hidden == 0)
	{
		$icon = $page->icon;
		if($icon == '')
		{
			$widget = \Mobile\Controller\WidgetController::loadWidgetConfig($page->widget);
			$icon = $widget['default_icon'];
		}

?>
  <ion-tab title="<?php echo $page->name ?>" icon="{{ $icon }}" href="#/nav/<?php echo $page->slug ?>"> </ion-tab>
<?php
	}
}
?>
</ion-tabs>
<ion-nav-view name="mainContent"></ion-nav-view>