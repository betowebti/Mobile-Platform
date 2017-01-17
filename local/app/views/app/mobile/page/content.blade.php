<?php
// Load widget views, translation and config namespaces
$widget_dir = public_path() . '/widgets/' . $page->widget;

\View::addLocation($widget_dir . '/views');
\View::addNamespace('widget', $widget_dir . '/views');
\Lang::addNamespace('widget', $widget_dir . '/lang');
\Config::addNamespace('widget', $widget_dir . '/config');

require public_path() . '/widgets/' . $page->widget . '/controllers/AdminController.php';

echo Former::vertical_open()
	->class('form ajax ajax-validate')
	->action(url('api/v1/app-edit/save-widget'))
	->method('POST');

echo Former::hidden()
		->name('sl')
		->forceValue($sl);

echo Former::hidden()
		->name('widget')
		->forceValue($page->widget);

echo \App::make('\Widget\Controller\AdminController')->getContent($app, $page);

echo '<hr>';

echo Former::actions(
    Former::submit(trans('global.save_changes'))->class('btn-lg btn-primary btn')->style('padding:20px 25px;font-size: 18px;')->id('btn-submit')/*,
    Former::reset(trans('global.reset'))->class('btn-lg btn-default btn')*/
	);

echo Former::close();

/*
 |--------------------------------------------------------------------------
 | General JavaScript
 |--------------------------------------------------------------------------
 */

?>
<script>
$('[data-class]').switcher(
{
	theme: 'square',
	on_state_content: '<span class="fa fa-check"></span>',
	off_state_content: '<span class="fa fa-times"></span>'
});

select2();

bsTooltipsPopovers();

$('.date-picker').datepicker({
	format: 'yyyy-mm-dd'
});

$('.time-picker').timepicker({
	minuteStep: 5,
	showSeconds: false,
	showMeridian: false,
	showInputs: false,
	orientation: $('body').hasClass('right-to-left') ? { x: 'right', y: 'auto'} : { x: 'auto', y: 'auto'}
});

$('#page-content').on('click', '.file-browse,.img-browse', function(event)
{
  if(event.handled !== true)
  {
	// trigger the reveal modal with elfinder inside
	$.colorbox(
	{
		href: elfinderUrl + $(this).attr('data-id') + '/processWidgetFile',
		fastIframe: true,
		iframe: true,
		width: '70%',
		height: '80%'
	});
    event.handled = true;
  }
  return false;

});

$('#page-content').on('click', '.img-remove', function(event)
{
  if(event.handled !== true)
  {
	$('#' + $(this).attr('data-id') + '-image').html('');
	$('#' + $(this).attr('data-id')).val('');
    event.handled = true;
  }
  return false;
});

$('#page-content').on('click', '.file-remove', function(event)
{
  if(event.handled !== true)
  {
	$('#' + $(this).attr('data-id')).val('');

    event.handled = true;
  }
  return false;
});

// Callback after elfinder selection
window.processWidgetFile = function(filePath, requestingField)
{
    if($('#' + requestingField).attr('type') == 'text')
    {
	    $('#' + requestingField).val(decodeURI(filePath));
    }

    if($('#' + requestingField + '-image').length)
    {
		var w = (typeof $('#' + requestingField + '-image').attr('data-w') !== 'undefined') ? $('#' + requestingField + '-image').attr('data-w') : 120;
		var h = (typeof $('#' + requestingField + '-image').attr('data-h') !== 'undefined') ? $('#' + requestingField + '-image').attr('data-h') : 120;
		var img = decodeURI(filePath);
		var thumb = '{{ url('/api/v1/thumb/nail?') }}w=' + w + '&h=' + h + '&img=' + filePath;

		$('#' + requestingField + '-image').addClass('bg-loading');

		$('<img/>').attr('src', decodeURI(thumb)).load(function() {
			$(this).remove();
			$('#' + requestingField + '-image').html('<img src="' + thumb + '" class="thumbnail" style="max-width:100%; margin:0">');
			$('#' + requestingField + '-image').removeClass('bg-loading');
		});

        $('#' + requestingField).val(img);
    }
}

$('form.ajax').ajaxForm({
    dataType: 'json',
    beforeSerialize: widgetBeforeSerialize,
    success: widgetFormResponse,
    error: widgetFormResponse
});

function widgetBeforeSerialize($jqForm, options)
{
    var form = $jqForm[0];

    // Set non-checked checkboxes to value="0"
    var cb = form.getElementsByTagName('input');

    for(var i=0;i<cb.length;i++){ 
        if(cb[i].type=='checkbox' && !cb[i].checked)
        {
           cb[i].value = 0;
           cb[i].checked = true;
        }
    }

    // Loading state
    blockUI('.screen');
    $(form).find('.sumbit,button[type="submit"],input[type="submit"]').addClass('loading');
    $(form).find('.sumbit,button[type="submit"],input[type="submit"]').attr('disabled', 'disabled');
}

function widgetFormResponse(responseText, statusText, xhr, $jqForm)
{
    var form = $jqForm[0];

    // Remove possible old markup
    $(form).find('.ajax-help-inline').remove();
    $(form).find('.form-group').removeClass('has-error has-warning has-info has-success');

    // Process JSON response
    unblockUI('.screen');

    // Reset non-checked checkboxes
    var cb = form.getElementsByTagName('input');

    for(var i=0;i<cb.length;i++){ 
        if(cb[i].type=='checkbox' && cb[i].value == 0)
        {
           cb[i].value = 1;
           cb[i].checked = false;
        }
    }

	reloadPreview();
	showSaved();

    $(form).find('.sumbit,button[type="submit"],input[type="submit"]').removeAttr('disabled');
    $(form).find('.sumbit,button[type="submit"],input[type="submit"]').removeClass('loading');
}
</script>