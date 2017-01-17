<?php
/*
 |--------------------------------------------------------------------------
 | Text
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.text');
$field_name = 'title';
$field_default_value = 'ACME Inc';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help)
    ->prepend('<i class="fa fa-twitter"></i>')
    ->append('<i class="fa fa-eye"></i>');

/*
 |--------------------------------------------------------------------------
 | Checkbox
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.checkbox');
$field_name = 'checkbox';
$field_default_value = 1; // checked 1/0
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::checkbox()
    ->name($field_name)
	->label($field_label)
    ->check((boolean)$field_value)
    ->dataClass('switcher-success')
    ->help($field_help)
	->novalidate();

/*
 |--------------------------------------------------------------------------
 | Multiple checkboxes
 |--------------------------------------------------------------------------
 */
/*
$field_label = trans('widget::global.checkboxes');
$field_name = 'checkboxes';
$field_options = array(
    'Option 1' => array('name' => $field_name . '1', 'value' => 1),
    'Option 2' => array('name' => $field_name . '2', 'value' => 2),
    'Option 3' => array('name' => $field_name . '2', 'value' => 3)
);
$field_default_value = array(1, 2);
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::checkboxes()
    ->name($field_name . '[]')
	->label($field_label)
	->checkboxes($field_options)
    ->check($field_value)
    ->help($field_help);
*/

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
<div class="form-group">
	<div>
		<input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $field_value }}">
		<label class="control-label">{{ $field_label }}</label>
	</div>

	<div class="btn-group" role="group" style="margin-bottom:10px">
		<button type="button" class="btn btn-primary img-browse" data-id="{{ $field_name }}"><i class="fa fa-picture-o"></i> {{ trans('global.select_image') }}</button>
		<button type="button" class="btn btn-danger img-remove" data-id="{{ $field_name }}" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
	</div>

	<div id="{{ $field_name }}-image">
<?php
if($field_value != '')
{
    echo '<img src="' . url($field_value) . '" class="thumbnail widget-thumb">';
}
?>
	</div>
</div>
<?php

/*
 |--------------------------------------------------------------------------
 | File
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.file');
$field_name = 'file';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

?>
<div class="form-group">
	<label class="control-label" for="{{ $field_name }}">{{ $field_label }}</label>
    <div class="input-group">
        <input type="text" name="{{ $field_name }}" id="{{ $field_name }}" class="form-control" value="{{ $field_value }}">
        <span class="input-group-btn"><button type="button" class="btn file-browse" id="{{ $field_name }}-browse" data-id="{{ $field_name }}"><i class="fa fa-folder-open-o"></i></button></span>
        <span class="input-group-btn"><button type="button" class="btn file-remove" id="{{ $field_name }}-remove" data-id="{{ $field_name }}"><i class="fa fa-remove"></i></button></span>
    </div>
</div>
<?php


/*
 |--------------------------------------------------------------------------
 | Select
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.select');
$field_name = 'select';
$field_options = array(1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3');
$field_default_value = 2;
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::select()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
	->placeholder(' ')
	->options($field_options)
    ->help($field_help)
	->class('select2'); // select2 / select2-required

/*
 |--------------------------------------------------------------------------
 | Select multiple
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.select_multi');
$field_name = 'select';
$field_options = array(1 => 'Option 1', 2 => 'Option 2', 3 => 'Option 3');
$field_default_value = array(1, 2);
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo '<div class="select2-primary">';
echo Former::select()
    ->name($field_name . '[]')
    ->multiple(true)
    ->select($field_value)
	->label($field_label)
	->placeholder(' ')
	->options($field_options)
    ->help($field_help)
	->class('select2-multiple');
echo '</div>';

/*
 |--------------------------------------------------------------------------
 | Days
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.days');
$field_name = 'days';
$field_options = array(1 => 'Mo', 2 => 'Tu', 3 => 'We', 4 => 'Th', 5 => 'Fr', 6 => 'Sa', 7 => 'Su');
$field_default_value = array(1, 2, 3, 4, 5, 6, 7);
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo '<div class="select2-primary">';
echo Former::select()
    ->name($field_name . '[]')
    ->multiple(true)
    ->select($field_value)
	->label($field_label)
	->placeholder(' ')
	->options($field_options)
    ->help($field_help)
	->class('select2-multiple');
echo '</div>';

/*
 |--------------------------------------------------------------------------
 | Radio
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.radio');
$field_name = 'radio';
$field_options = array(
    'Option 1' => array('name' => $field_name, 'value' => 1),
    'Option 2' => array('name' => $field_name, 'value' => 2),
    'Option 3' => array('name' => $field_name, 'value' => 3)
);
$field_default_value = 2;
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::radios()
    ->name($field_name)
    ->check($field_value)
	->label($field_label)
	->radios($field_options)
    ->help($field_help);

/*
 |--------------------------------------------------------------------------
 | Date
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.date');
$field_name = 'date';
$field_default_value = date('Y-m-d');
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

?>
<div class="form-group">
	<label class="control-label" for="{{ $field_name }}">{{ $field_label }}</label>
    <div class="input-group date" id="{{ $field_name }}-datepicker">
        <input type="text" name="{{ $field_name }}" id="{{ $field_name }}" class="form-control date-picker" placeholder="{{ $field_label }}" value="{{ $field_value }}"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
    </div>
</div>
<?php

/*
 |--------------------------------------------------------------------------
 | Date range
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.date_range');
$field_name = 'date_range';
$field_default_value_start = date('Y-m-d');
$field_default_value_end = '';
$field_value_start = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value_start);
$field_value_end = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value_end);
$field_help = '';

?>
<div class="form-group">
	<label class="control-label" for="{{ $field_name }}">{{ $field_label }}</label>
    <div class="input-group input-daterange" id="{{ $field_name }}-datepicker">
        <input type="text" name="{{ $field_name }}_start" id="{{ $field_value_start }}_start" class="form-control date-picker" placeholder="{{ trans('widget::global.start') }}" value="{{ $field_value_start }}" style="width:100%">
		<span class="input-group-addon">{{ trans('widget::global.to') }}</span>
        <input type="text" name="{{ $field_name }}_end" id="{{ $field_value_end }}_end" class="form-control date-picker" placeholder="{{ trans('widget::global.end') }}" value="{{ $field_value_end }}" style="width:100%">
    </div>
</div>
<?php

/*
 |--------------------------------------------------------------------------
 | Time
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.time');
$field_name = 'time';
$field_default_value = date('H:i');
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

?>
<div class="form-group">
	<label class="control-label" for="{{ $field_name }}">{{ $field_label }}</label>
    <div class="input-group date" id="{{ $field_name }}-timepicker">
        <input type="text" name="{{ $field_name }}" id="{{ $field_name }}" class="form-control time-picker" placeholder="{{ $field_label }}" value="{{ $field_value }}"><span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
    </div>
</div>
<?php


/*
 |--------------------------------------------------------------------------
 | Time range
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.time_range');
$field_name = 'time_range';
$field_default_value_start = date('H:i');
$field_default_value_end = '';
$field_value_start = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value_start);
$field_value_end = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value_end);
$field_help = '';

?>
<div class="form-group">
	<label class="control-label" for="{{ $field_name }}">{{ $field_label }}</label>
    <div class="input-group" id="{{ $field_name }}-timepicker">
        <input type="text" name="{{ $field_name }}_start" id="{{ $field_value_start }}_start" class="form-control time-picker" placeholder="{{ trans('widget::global.start') }}" value="{{ $field_value_start }}" style="width:100%">
		<span class="input-group-addon">{{ trans('widget::global.to') }}</span>
        <input type="text" name="{{ $field_name }}_end" id="{{ $field_value_end }}_end" class="form-control time-picker" placeholder="{{ trans('widget::global.end') }}" value="{{ $field_value_end }}" style="width:100%">
    </div>
</div>
<?php

/*
 |--------------------------------------------------------------------------
 | Textarea
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.textarea');
$field_name = 'textarea';
$field_default_value = '';
$field_rows = 6;
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::textarea()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help)
	->rows($field_rows);

/*
 |--------------------------------------------------------------------------
 | WYSIWIG
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.wysiwyg');
$field_name = 'wysiwyg';
$field_default_value = '';
$field_rows = 6;
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

?>
<script>
if (! $('html').hasClass('ie8')) {
	$('#{{ $field_name }}').summernote({
		height: 200,
		tabsize: 2,
		codemirror: {
			theme: 'monokai'
		},
		toolbar: [
			['fontname', ['fontname']],
			['fontsize', ['fontsize']],
			//['style', ['style']],
			['style', ['bold', 'italic', 'underline', 'clear']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			//['height', ['height']], // line height
			['insert', ['picture', 'link']],
			['table', ['table']],
			['codeview', ['codeview']]
		]
	});
}
</script>
<?php

echo Former::textarea()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help);

?>