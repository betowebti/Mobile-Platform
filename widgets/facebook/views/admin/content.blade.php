<?php
/*
 |--------------------------------------------------------------------------
 | Facebook ID
 |--------------------------------------------------------------------------
 */
 

$facebook_id = \Mobile\Controller\WidgetController::getData($page, 'facebook_id', '');

echo Former::text()
    ->name('facebook_id')
    ->forceValue($facebook_id)
    ->help(trans('widget::global.facebook_id_help'))
	->label(trans('widget::global.facebook_id'));

$show_title = \Mobile\Controller\WidgetController::getData($page, 'show_title', '1');

echo Former::checkbox()
    ->name('show_title')
	->label(trans('widget::global.show_title'))
    ->check((boolean) $show_title)
    ->dataClass('switcher-success')
	->novalidate();

$limit = \Mobile\Controller\WidgetController::getData($page, 'limit', 10);

echo Former::number()
    ->name('limit')
    ->forceValue($limit)
	->label(trans('widget::global.max_posts'));
?>