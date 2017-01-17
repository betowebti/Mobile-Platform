<?php
$field_name = 'url';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, url());

echo Former::hidden()
    ->name($field_name)
    ->forceValue($field_value);

$field_label = trans('widget::global.channel_url');
$field_name = 'channel_url';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, trans('widget::global.default_channel'));

echo Former::text()
    ->name($field_name)
    ->required(true)
    ->forceValue($field_value)
	->label($field_label);

$field_label = trans('widget::global.featured');
$field_name = 'playlist_url';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, trans('widget::global.default_playlist'));

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
    ->help(trans('widget::global.playlist_url'))
	->label($field_label);

echo '<div class="row">';
echo '<div class="col-md-3">';

$field_label = trans('widget::global.default_tab');
$field_name = 'tab';
$field_options = array('featured' => trans('widget::global.featured'), 'uploads' => trans('widget::global.uploads'), 'playlists' => trans('widget::global.playlists'));
$type = \Mobile\Controller\WidgetController::getData($page, $field_name, 'uploads');

echo Former::select()
    ->name($field_name)
    ->id($field_name)
    ->forceValue($type)
	->label($field_label)
	->placeholder(' ')
	->options($field_options)
	->class('select2-required');

echo '</div>';
echo '<div class="col-md-4">';

$field_label = trans('widget::global.columns');
$field_name = 'columns';
$field_options = array_combine(range(1,4), range(1,4));
$type = \Mobile\Controller\WidgetController::getData($page, $field_name, '1');

echo Former::select()
    ->name($field_name)
    ->id($field_name)
    ->forceValue($type)
	->label($field_label)
	->placeholder(' ')
	->options($field_options)
	->class('select2-required');

echo '</div>';
echo '<div class="col-md-4">';

$field_label = trans('widget::global.max_results');
$field_name = 'max_results';
$field_options = array_combine(range(1,15), range(1,15));
$type = \Mobile\Controller\WidgetController::getData($page, $field_name, '15');

echo Former::select()
    ->name($field_name)
    ->id($field_name)
    ->forceValue($type)
	->label($field_label)
	->placeholder(' ')
	->options($field_options)
	->class('select2-required');

echo '</div>';
echo '</div>';
?>