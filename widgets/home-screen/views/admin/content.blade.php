<?php 
/*
 |--------------------------------------------------------------------------
 | Image
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.image');
$field_name = 'image';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

?>
<div class="row">
	<div class="col-md-6">
		<div class="form-group">
			<div>
				<input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $field_value }}">
				<label class="control-label">{{ $field_label }}</label>
			</div>

			<div class="btn-group" role="group" style="margin-bottom:10px">
				<button type="button" class="btn btn-info img-browse" data-id="{{ $field_name }}"><i class="fa fa-picture-o"></i> {{ trans('global.select_image') }}</button>
				<button type="button" class="btn btn-danger img-remove" data-id="{{ $field_name }}" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
			</div>

			<div id="{{ $field_name }}-image" data-w="240" data-h="120">
<?php
if($field_value != '')
{
    echo '<img src="' . url('/api/v1/thumb/nail?w=240&h=120&img=' . $field_value) . '" class="thumbnail widget-thumb">';
}
?>
			</div>
		</div>
	</div>
<?php
echo '<div class="col-md-6">';

$field_label = trans('widget::global.box_image');
$field_name = 'box_image';
$field_default_value = 1; // checked 1/0
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo Former::checkbox()
    ->name($field_name)
	->label($field_label)
    ->check((boolean)$field_value)
    ->dataClass('switcher-success')
	->novalidate();

echo '</div>';

echo '</div>';

echo '<div class="row">';
echo '<div class="col-md-4">';

$field_value = \Mobile\Controller\WidgetController::getData($page, 'columns', 2);
$columns = array_combine(range(1,4), range(1,4));

echo Former::select('columns')
	->class('select2-required form-control')
    ->name('columns')
    ->forceValue($field_value)
	->options($columns)
	->label(trans('widget::global.columns'));

echo '</div>';
echo '<div class="col-md-4">';

$field_value = \Mobile\Controller\WidgetController::getData($page, 'icon_size', 'l');
$sizes = array(
	"xs" => trans('widget::global.xs'),
	"s" => trans('widget::global.s'),
	"m" => trans('widget::global.m'),
	"l" => trans('widget::global.l'),
	"xl" => trans('widget::global.xl')
);

echo Former::select('icon_size')
	->class('select2-required form-control')
    ->name('icon_size')
    ->forceValue($field_value)
	->options($sizes)
	->label(trans('widget::global.icon_size'));

echo '</div>';
echo '<div class="col-md-4">';

$field_value = \Mobile\Controller\WidgetController::getData($page, 'color', 'light');
$colors = array(
	"light" => trans('widget::global.primary'),
	"dark" => trans('widget::global.secondary'),
	"stable" => trans('widget::global.stable'),
	"positive" => trans('widget::global.positive'),
	"calm" => trans('widget::global.calm'),
	"balanced" => trans('widget::global.balanced'),
	"energized" => trans('widget::global.energized'),
	"assertive" => trans('widget::global.assertive'),
	"royal" => trans('widget::global.royal')
);

echo Former::select('color')
	->class('select2-required form-control')
    ->name('color')
    ->forceValue($field_value)
	->options($colors)
	->label(trans('widget::global.color'));

echo '</div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-md-4">';

$field_value = \Mobile\Controller\WidgetController::getData($page, 'bg_color', 'dark');
$colors = array(
	"light" => trans('widget::global.light'),
	"dark" => trans('widget::global.dark'),
	"none" => trans('widget::global.none')
);

echo Former::select('bg_color')
	->class('select2-required form-control')
    ->name('bg_color')
    ->forceValue($field_value)
	->options($colors)
	->label(trans('widget::global.background_color'));

echo '</div>';
echo '<div class="col-md-4">';

$field_value = \Mobile\Controller\WidgetController::getData($page, 'shadow', 'none');
$colors = array(
	"light" => trans('widget::global.light'),
	"dark" => trans('widget::global.dark'),
	"none" => trans('widget::global.none')
);

echo Former::select('shadow')
	->class('select2-required form-control')
    ->name('shadow')
    ->forceValue($field_value)
	->options($colors)
	->label(trans('widget::global.shadow'));

echo '</div>';
echo '</div>';

?>