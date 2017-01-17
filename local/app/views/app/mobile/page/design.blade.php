<?php

$bg = ($page->background_smarthpones_file_name != '') ? ' style="background-image:url(\'' . $page->background_smarthpones->url('bg') . '\')"': '';
$class = ($page->background_smarthpones_file_name != '') ? ' filled': '';
$page_bg_delete = ($page->background_smarthpones_file_name != '') ? '' : ' display:none';

?><div>
	<label>{{ trans('global.background') }}</label>
</div>

<a href="javascript:void(0);" class="btn btn-sm btn-danger" id="remove-page-bg" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('global.remove_image') }}" style="position:absolute;left:95px;margin-top:114px;width:20px;padding:2px 0;{{ $page_bg_delete }}"><i class="fa fa-remove"></i></a>

<a href="javascript:void(0)" class="img thumbnail vertical select-page-image{{ $class }}" id="bg-page" data-id="bg-page"{{ $bg }}>
	<i class="fa fa-plus-circle"></i>
	<div>
	640x1136
	</div>
</a>

<div class="list-group" style="float:left;">
	<a href="javascript:void(0);" class="list-group-item select-page-image" id="upload-page-bg" data-id="bg-page"><i class="fa fa-cloud-upload"></i> {{ trans('global.upload_image') }}</a>
</div>

<script>

bsTooltipsPopovers();

$('.select-page-image').on('click', function()
{
	// trigger the reveal modal with elfinder inside
	$.colorbox(
	{
		href: elfinderUrl + $(this).attr('data-id') + '/processPageFile',
		fastIframe: true,
		data: {cb: 'processAppFile' },
		iframe: true,
		width: '70%',
		height: '80%'
	});
});

// Callback after elfinder selection
window.processPageFile = function(filePath, requestingField)
{

    var request = $.ajax(
    {
        url: "{{ url('/api/v1/app-edit/bg-page-image') }}",
        type: 'POST',
        data:
        {
            sl: "{{ $sl }}",
            image: filePath
        },
        dataType: 'json'
    });

    request.done(function(json)
    {
		$('#remove-page-bg').show();

		$('#' + requestingField).addClass('bg-loading');

		$('<img/>').attr('src', decodeURI(filePath)).load(function() {
			$(this).remove();
			$('#' + requestingField).css('background-image', 'url("' + decodeURI(filePath) + '")');
			$('#' + requestingField).removeClass('bg-loading');
		});

		$('#' + requestingField).addClass('filled');
        reloadPreview();
        showSaved();
    });

    request.fail(function(jqXHR, textStatus)
    {
        alert('Request failed, please try again (' + textStatus + ')');
    });
}



$('body').on('click', '#remove-page-bg', function()
{
	blockUI('.screen');
    var request = $.ajax(
    {
        url: app_root + '/api/v1/app-edit/bg-page-image-remove',
        type: 'POST',
        data:
        {
            sl: "{{ $sl }}"
        },
        dataType: 'json'
    });

    request.done(function(json)
    {
		$('#bg-page').css('background-image', 'none');
		$('#bg-page').removeClass('filled');
        $('#remove-page-bg').hide();
		unblockUI('.screen');
        reloadPreview();
        showSaved();
    });

    request.fail(function(jqXHR, textStatus)
    {
        alert('Request failed, please try again (' + textStatus + ')');
		unblockUI('.screen');
    });
});
</script>