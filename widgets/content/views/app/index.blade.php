<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}">
<?php
if ($image != '')
{
    $box = \Mobile\Controller\WidgetController::getData($page, 'box_image', 1);
    if($box == 1)
    {
        echo '<div class="card" style="margin-top:5px">';
        echo '<div class="item item-image">';
    }

    echo '<img src="' . url($image) . '" class="full-image">';

    if($box == 1)
    {
        echo '</div>';
        echo '</div>';
    }
}

if ($content != '')
{
?>
		<div class="transparent">
			<div>
				<?php echo $content; ?>
			</div>
		</div>
<?php
}

if ($social_share)
{
	echo '<div class="transparent m-top text-center">
	<div id="share' . $page->id . '"></div>
</div>';

	echo \Mobile\Core\Social::shareButtons($app, $page, 'share' . $page->id);
}
?>
    </ion-content>
</ion-view>
