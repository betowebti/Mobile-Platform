<?php
/*
 |--------------------------------------------------------------------------
 | Email address
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.email_address');
$field_name = 'email_address';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->prepend('<i class="fa fa-envelope-o"></i>');

/*
 |--------------------------------------------------------------------------
 | Subject
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.subject');
$field_name = 'subject';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = trans('widget::global.email_address_help');

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
    ->help($field_help)
	->label($field_label);

?>