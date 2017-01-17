<?php

$field_label = trans('widget::global.button_label');
$vcard_button = \Mobile\Controller\WidgetController::getData($page, 'vcard_button', trans('widget::global.download_vcard'));

echo Former::text()
    ->name('vcard_button')
    ->forceValue($vcard_button)
    ->required()
	->label($field_label);

$vcard = \Mobile\Controller\WidgetController::getData($page, 'vcard', '');
if($vcard != '') $vcard = json_decode($vcard, true);

echo '<br><legend>' . trans('widget::global.personal') . '</legend>';

$field_label = trans('widget::global.photo');
$field_name = 'photo';
$field_default_value = '';
$field_value = (isset($vcard[$field_name])) ? $vcard[$field_name] : '';

?>
<div class="form-group">
	<div>
		<input type="hidden" name="vcard[{{ $field_name }}]" id="{{ $field_name }}" value="{{ $field_value }}">
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

echo '<div class="row">';
echo '<div class="col-md-2">';

$field_label = trans('widget::global.prefix');
$field_name = 'prefix';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

echo '</div>';
echo '<div class="col-md-4">';

$field_label = trans('widget::global.first_name');
$field_name = 'first_name';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
    ->required()
	->label($field_label);

echo '</div>';
echo '<div class="col-md-4">';

$field_label = trans('widget::global.last_name');
$field_name = 'last_name';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
    ->required()
	->label($field_label);

echo '</div>';
echo '<div class="col-md-2">';

$field_label = trans('widget::global.suffix');
$field_name = 'suffix';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

echo '</div>';
echo '</div>';

echo '<div class="row">';
echo '<div class="col-md-6">';

$field_label = trans('widget::global.company');
$field_name = 'company';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

echo '</div>';
echo '<div class="col-md-6">';

$field_label = trans('widget::global.job_title');
$field_name = 'job_title';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

echo '</div>';
echo '</div>';

echo '<br><legend>' . trans('widget::global.contact') . '</legend>';

echo '<div class="row">';
echo '<div class="col-md-6">';

$field_label = trans('widget::global.email');
$field_name = 'email';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label)
    ->prepend('<i class="fa fa-envelope-o"></i>');

$field_label = trans('widget::global.phone_home');
$field_name = 'phone_home';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label)
    ->prepend('<i class="fa fa-phone"></i>');

$field_label = trans('widget::global.personal_website');
$field_name = 'personal_website';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label)
    ->prepend('<i class="fa fa-link"></i>');

echo '</div>';
echo '<div class="col-md-6">';

$field_label = trans('widget::global.fax');
$field_name = 'fax';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label)
    ->prepend('<i class="fa fa-fax"></i>');

$field_label = trans('widget::global.phone_work');
$field_name = 'phone_work';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label)
    ->prepend('<i class="fa fa-phone"></i>');


$field_label = trans('widget::global.work_website');
$field_name = 'work_website';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label)
    ->prepend('<i class="fa fa-link"></i>');

echo '</div>';
echo '</div>';


echo '<div class="row">';
echo '<div class="col-md-6">';

echo '<br><legend>' . trans('widget::global.business_address') . '</legend>';

$field_label = trans('widget::global.street');
$field_name = 'business_street';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

$field_label = trans('widget::global.city');
$field_name = 'business_city';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

$field_label = trans('widget::global.state');
$field_name = 'business_state';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

$field_label = trans('widget::global.zip');
$field_name = 'business_zip';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

$field_label = trans('widget::global.country');
$field_name = 'business_country';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

echo '</div>';
echo '<div class="col-md-6">';

echo '<br><legend>' . trans('widget::global.home_address') . '</legend>';

$field_label = trans('widget::global.street');
$field_name = 'home_street';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

$field_label = trans('widget::global.city');
$field_name = 'home_city';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

$field_label = trans('widget::global.state');
$field_name = 'home_state';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

$field_label = trans('widget::global.zip');
$field_name = 'home_zip';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

$field_label = trans('widget::global.country');
$field_name = 'home_country';

echo Former::text()
    ->name('vcard[' . $field_name . ']')
    ->forceValue((isset($vcard[$field_name])) ? $vcard[$field_name] : '')
	->label($field_label);

echo '</div>';
echo '</div>';

?>