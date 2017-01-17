<?php
/*
 |--------------------------------------------------------------------------
 | Text
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.website');
$field_name = 'rss_feed';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = trans('widget::global.website_help');

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help)
    ->placeholder('http://')
    ->prepend('<i class="fa fa-rss"></i>');


$valid = \Mobile\Controller\WidgetController::getData($page, 'valid', 0);

echo Former::hidden()
    ->name('valid')
    ->id('valid')
    ->forceValue($valid);

?>
<script>
$('#rss_feed').on('change', function() {
	blockUI();

	$.ajax({
		type: 'POST',
		url: '{{ url('api/v1/widget/admin-post/rss/parseUrl') }}',
		data: {
			'sl': '{{ $sl }}', 
			'url': $(this).val()
		},
		dataType: 'json',
		success: function(data)
		{
			$('#valid').val(data.valid);
			if(data.valid == 1)
			{
				$('#rss_feed').val(data.url);
				$('#rss_feed').parents('.form-group').addClass('has-success');
				$('#rss_feed').parents('.input-group').next('.help-block').text(data.msg);
			}
			else
			{
				$('#rss_feed').parents('.form-group').addClass('has-error');
				$('#rss_feed').parents('.input-group').next('.help-block').text(data.msg);
			}
		}		
	}).always(function() {
		unblockUI();
	});
});
</script>