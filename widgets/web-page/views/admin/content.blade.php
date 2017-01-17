<?php
/*
 |--------------------------------------------------------------------------
 | Website URL
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.website_url');
$field_name = 'url';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
	->placeholder('http://')
    ->prepend('<i class="fa fa-link"></i>');

/*
 |--------------------------------------------------------------------------
 | New window
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.open_new_window');
$field_name = 'new_window';
$field_default_value = 0; // checked 1/0
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
 | Button text
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.button_text');
$field_name = 'button_text';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo '<div id="button_text" style="display:none">';
echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help(trans('widget::global.button_text_help'));
echo '</div>';

?>
<script>
checkNewWindow();
$('#new_window').on('change', checkNewWindow);

function checkNewWindow()
{
	var open_new_window = $('#new_window')[0].checked;
	if(open_new_window)
	{
		$('#button_text').show();
	}
	else
	{
		$('#button_text').hide();
	}
}
</script>