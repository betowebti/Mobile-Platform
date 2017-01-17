<ion-side-menus enable-menu-with-back-views="true">
  <ion-side-menu-content ng-controller="NavCtrl">
    <ion-nav-bar class="bar-dark">
      <ion-nav-back-button class="button-icon icon ion-arrow-left-c">
      </ion-nav-back-button>

      <ion-nav-buttons side="left">
        <button class="button button-icon ion-navicon" menu-toggle="left">
        </button>
      </ion-nav-buttons>
    </ion-nav-bar>
    <ion-nav-view name="mainContent"></ion-nav-view>
  </ion-side-menu-content>

  <ion-side-menu side="left">
    <ion-header-bar class="bar-dark">
      <h1 class="title">{{ $app->name }}</h1>
    </ion-header-bar>
    <ion-content>
      <ion-list>
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
        <ion-item nav-clear menu-close href="#{{ $hashPrefix }}/nav/<?php echo $page->slug ?>" class="item-icon-left">
          <i class="icon {{ $icon }}"></i> <?php echo $page->name ?>
        </ion-item>
<?php
	}
}
?>
      </ion-list>
    </ion-content>
  </ion-side-menu>
</ion-side-menus>