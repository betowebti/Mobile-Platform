<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}">
<?php
if($image != '')
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
?>
		<div class="transparent">
			<h2><?php echo \Mobile\Controller\WidgetController::getData($page, 'title', trans('widget::global.default_title')); ?></h2>
			<div>
				<?php echo \Mobile\Controller\WidgetController::getData($page, 'content', trans('widget::global.default_content')); ?>
			</div>
		</div>
<?php
if($phone_number != '' && $btn_text != '')
{
?>
		<div class="transparent">
	        <a href="tel:{{ $phone_number }}" class="button button-balanced button-block">{{ $btn_text }}</a>
		</div>
<?php
}
else
{
	echo '<br>';
}

if($list != NULL)
{
?>
        <ion-list type="card">
<?php
$i = 0;
$js = '';
foreach($list->icon as $row)
{
	if($list->title[$i] != '' && $list->value[$i] != '' && $list->icon[$i] != '')
	{
?>
            <ion-item class="item item-icon-left">
                <i class="icon {{ $list->icon[$i] }}"></i>
                <h2>{{ $list->title[$i] }}</h2>
                <p>{{ $list->value[$i] }}</p>
            </ion-item>
<?php
	}
	$i++;
}
?>
        </ion-list>
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
