<?php
/*
 |--------------------------------------------------------------------------
 | Phone number
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.phone_number');
$field_name = 'phone_number';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = trans('widget::global.phone_number_help');

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help)
    ->prepend('<i class="fa fa-phone"></i>');

?>