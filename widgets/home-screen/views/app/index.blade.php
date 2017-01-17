<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}">

		<section class="home-container">
<?php
if($image != '')
{
    $box = \Mobile\Controller\WidgetController::getData($page, 'box_image', 1);
    if($box == 1)
    {
        echo '<div class="card" style="margin-top:5px">';
        echo '<div class="item item-image">';

	    echo '<img src="' . url($image) . '" class="full-image">';

        echo '</div>';
        echo '</div>';
    }
	else
	{
        echo '<div class="row header">';
        echo '<div class="col text-center">';

	    echo '<img src="' . url($image) . '">';

        echo '</div>';
        echo '</div>';
	}
}

$i = 0;
$col_cnt = 1;

$hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';

foreach($app->appPages as $app_page)
{
	if($app_page->hidden == 0 && $app_page->id != $page->id)
	{
		$icon = $app_page->icon;
		if($icon == '')
		{
			$widget = \Mobile\Controller\WidgetController::loadWidgetConfig($app_page->widget);
			$icon = $widget['default_icon'];
		}

		if (($i % $columns  == 0 || $i == 0) && $columns > 1)
		{
			echo '<div class="row icon-row ' . $shadow . '">';
		}

		if ($columns == 1)
		{
			echo '<div class="button-row ' . $icon_size . '">';
			echo '<a href="#' . $hashPrefix . '/nav/' . $app_page->slug . '" class="button button-block button-' . $color . '"><i class="icon ' . $icon . ' icon-' . $icon_size . '"></i> <span class="button-text">' . $app_page->name . '</span></a>';
			echo '</div>';
		}
		else
		{
			echo '<div class="col text-center bg-' . $bg_color . '">';
			echo '<a href="#' . $hashPrefix . '/nav/' . $app_page->slug . '" class="' . $color . '"><i class="icon ' . $icon . ' icon-' . $icon_size . '"></i></a><br />';
			echo '<a href="#' . $hashPrefix . '/nav/' . $app_page->slug . '" class="button button-clear button-' . $color . '">' . $app_page->name . '</a>';
			echo '</div>';
		}

		if ($col_cnt == $columns && $columns > 1)
		{
			echo '</div>';
			$col_cnt = 1;
		}
		else
		{
			$col_cnt++;
		}

		$i++;
	}
}

if ($col_cnt != 1)
{
	for ($i=0; $i<($columns - $col_cnt) + 1; $i++)
	{
		echo '<div class="col text-center">&nbsp;</div>';
	}
	echo '</div>';
}
?>
      </section>

	</ion-content>
</ion-view>