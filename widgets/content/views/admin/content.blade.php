<?php
/*
 |--------------------------------------------------------------------------
 | Image
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.header_image');
$field_name = 'image';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

?>
<div class="row">
	<div class="col-md-8">
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

/*
 |--------------------------------------------------------------------------
 | Checkbox
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.box_image');
$field_name = 'box_image';
$field_default_value = 1; // checked 1/0
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo '<div class="col-md-4">';

echo Former::checkbox()
    ->name($field_name)
	->label($field_label)
    ->check((boolean)$field_value)
    ->dataClass('switcher-success')
	->novalidate();

echo '</div>';
echo '</div>';

/*
 |--------------------------------------------------------------------------
 | WYSIWIG
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.content');
$field_name = 'content';
$field_default_value = trans('widget::global.default_content');
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

?>
<script>
if (! $('html').hasClass('ie8')) {
	$('#{{ $field_name }}').summernote({
		height: 300,
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
	->label($field_label)
    ->help($field_help);

/*
 |--------------------------------------------------------------------------
 | Social share
 |--------------------------------------------------------------------------
 */

echo '<div class="row"><div class="col-md-8">';

$social_share_text = \Mobile\Controller\WidgetController::getData($page, 'social_share_text');
$social_share = \Mobile\Controller\WidgetController::getData($page, 'social_share', 1);

echo Former::text()
    ->name('social_share_text')
    ->forceValue($social_share_text)
	->label(trans('global.social_share_text'))
    ->placeholder($app->name . ' - ' . $page->name)
    ->prepend('<i class="fa fa-share-alt"></i>');

echo '</div><div class="col-md-4">';

echo Former::checkbox()
    ->name('social_share')
	->label(trans('global.social_share_help'))
    ->check((boolean) $social_share)
    ->dataClass('switcher-success')
	->novalidate();

echo '</div></div>';

?>