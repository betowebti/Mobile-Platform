<?php

$field_label = trans('widget::global.address');
$field_name = 'address';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name);

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->prepend('<i class="fa fa-map-marker"></i>')
	->append(Former::button('<i class="fa fa-search"></i> ' . trans('widget::global.find'))->class('btn btn-primary')->id('geocode_address'));

echo '<div class="row">';

echo '<div class="col-md-4">';

$field_label = trans('widget::global.longitude');
$field_name = 'longitude';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name);

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label);

echo '</div>';

echo '<div class="col-md-4">';

$field_label = trans('widget::global.latitude');
$field_name = 'latitude';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name);

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label);

echo '</div>';

echo '<div class="col-md-4">';

$field_label = trans('widget::global.zoom');
$field_name = 'zoom';

for($i = 1; $i < 15; $i++)
{
	$field_options[$i] = $i;
}

$field_default_value = 9;
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo Former::select()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
	->placeholder(' ')
	->options($field_options)
	->class('select2-required');

echo '</div>';

echo '</div>';

$field_label = trans('widget::global.open_marker_by_default');
$field_name = 'open_marker';
$field_default_value = 1; // checked 1/0
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo Former::checkbox()
    ->name($field_name)
	->label($field_label)
    ->check((boolean)$field_value)
    ->dataClass('switcher-success')
	->novalidate();

$field_label = trans('widget::global.marker_text');
$field_name = 'marker';
$field_default_value = '';
$field_rows = 6;
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

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
			['style', ['style']],
			['style', ['bold', 'italic', 'underline', 'clear']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['height', ['height']], // line height
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
	->label($field_label);


echo '<div class="row">';
echo '<div class="col-md-6">';

$icon = \Mobile\Controller\WidgetController::getData($page, 'icon', '');

$marker_icons_path = public_path() . '/widgets/map/assets/img/markers';
$marker_icons = \File::files($marker_icons_path);

$icons[''] = '';

foreach($marker_icons as $marker_icon)
{
    $icon_path = url('/widgets/map/assets/img/markers/' . basename($marker_icon));
    $icon_name = str_replace('-', ' ', str_replace('_', ' ', str_replace('.png', '', basename($marker_icon))));
    $icons[$icon_path] = $icon_name;
}
echo Former::select('icon')
	->label(trans('widget::global.icon'))
    ->forceValue($icon)
    ->options($icons);

echo '</div>';
echo '<div class="col-md-6">';
/*
 |--------------------------------------------------------------------------
 | Custom icon
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.custom_icon');
$field_name = 'custom_icon';
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

	<div id="{{ $field_name }}-image" data-w="36" data-h="36">
<?php
if($field_value != '')
{
    echo '<img src="' . url($field_value) . '" class="thumbnail widget-thumb">';
}
?>
	</div>
</div>
<?php
echo '</div>';
echo '</div>';
?>
<style type="text/css">
#s2id_icon.select2-container .select2-choice {
	height: 84px;
    line-height: 68px;
}
#s2id_icon .select-icon {
    margin-right:20px;
}
</style>
<script>
function format_marker(marker) {
    if(!marker.id) return marker.text; // optgroup
    return (marker.text != '') ? "<img class='select-icon' src='" + marker.id + "'/> " + marker.text : '';
}

$('#icon').select2({
    formatResult: format_marker,
    formatSelection: format_marker,
    placeholder: '',
    allowClear: true,
    escapeMarkup: function(m) { return m; }
});

$('#address').on('change', geocodeAddress);
$('#geocode_address').on('click', geocodeAddress);

function geocodeAddress()
{
	var address = $('#address').val();
    var geocode = '{{ url('/api/v1/widget/get/map/getGeocode?address=') }}' + address;

    $.getJSON(geocode, function(data) {

        if(data.result == 'success')
        {
            var found = true;
			console.log(data);

            $('#longitude').val(data.geo.longitude);
            $('#latitude').val(data.geo.latitude);
        }
        else
        {
            var found = false;
        }

        var $address_group = $('#address').parent('.input-group').parent('.form-group');
        $address_group.removeClass('has-success has-error');
    
        if(! found)
        {
            $address_group.addClass('has-error');
        }
        else
        {
            $address_group.addClass('has-success');
        }

    });
}
</script>