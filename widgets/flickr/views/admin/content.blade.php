<?php
/*
 |--------------------------------------------------------------------------
 | Flickr type
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.feed_type');
$field_name = 'type';
$field_options = array('id' => 'User ID', 'ids' => 'List of user IDs', 'tags' => 'Tag(s)');
$field_default_value = 'tags';
$type = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::select()
    ->name($field_name)
    ->id($field_name)
    ->forceValue($type)
	->label($field_label)
	->placeholder(' ')
	->options($field_options)
    ->help($field_help)
	->class('select2-required'); // select2 / select2-required

/*
 |--------------------------------------------------------------------------
 | Flickr feed
 |--------------------------------------------------------------------------
 */

if($type == 'id')
{
	$field_label = trans('widget::global.user_id');
}
elseif($type == 'ids')
{
	$field_label = trans('widget::global.user_ids');
}
elseif($type == 'tags')
{
	$field_label = trans('widget::global.tags');
}
$field_name = 'tag';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo '<div id="tag_input">';
echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help);
echo '</div>';

?>
<script>
$('#type').on('change', function() {
	switch($(this).val())
	{
		case 'id':
			$('#tag_input .control-label').text("{{ trans('widget::global.user_id') }}");
			break;
		case 'ids':
			$('#tag_input .control-label').text("{{ trans('widget::global.user_ids') }}");
			break;
		case 'tags':
			$('#tag_input .control-label').text("{{ trans('widget::global.tags') }}");
			break;
	}
});
</script>