<div class="col-xs-2">
	<a href="javascript:void(0);" class="thumbnail" id="page-icon">

	</a>
</div>
<div class="col-xs-6">
<?php
echo Former::text()
	->class('form-control input-sm')
    ->name('app_page_name')
    ->forceValue($page->name)
	->label(trans('global.page_name'));
?>
</div>
<div class="col-xs-4">

	<div class="pull-right" style="margin-top:23px;margin-right:7px">
		<div class="btn-group" role="group">
		  <button type="button" id="btn-toggle-vis" class="btn btn-default" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('global.toggle_page_visibility') }}"><i class="fa fa-eye<?php if($page->hidden == 0) echo '-slash'; ?>"></i></button>
		  <button type="button" class="btn btn-default" data-modal-qr="{{ url('/app/modal/mobile/qr?id=btnQr' . $page->id) }}" id="btnQr{{ $page->id }}" data-text="<?php if (\Config::get('system.seo', true)) { echo urlencode($app->domain() . '#!/nav/' . $page->slug); } else { echo urlencode($app->domain() . '#/nav/' . $page->slug); } ?>" data-toggle="tooltip" title="{{ trans('global.qr_code') }}"><i class="fa fa-qrcode"></i></button>
		  <button type="button" id="btn-delete" class="btn btn-default" data-toggle="tooltip" data-placement="top" data-original-title="{{ trans('global.delete_page') }}"><i class="fa fa-trash"></i></button>
<?php /*		  <button type="button" class="btn btn-default" data-toggle="tooltip" data-placement="top" data-original-title="Help"><i class="fa fa-question"></i></button>*/ ?>
		</div>
	</div>

</div>
<script>
$('#page-icon').iconpicker({ 
	arrowClass: 'btn-danger',
	arrowPrevIconClass: 'fa fa-chevron-left',
	arrowNextIconClass: 'fa fa-chevron-right',
	cols: 8,
	rows: 6,
	icon: '{{ $icon }}',
	iconset: $.iconset_ionicon,
	labelHeader: '{0} of {1} pages',
	labelFooter: '{0} - {1} of {2} icons',
	placement: 'bottom',
	search: true,
	searchText: '{{ trans('global.search_') }}',
	selectedClass: 'btn-primary',
	unselectedClass: ''
}).on('change', function(e) { 
	blockUI('.screen');

	var request = $.ajax({
		  url: "{{ url('/api/v1/app-edit/icon-picker') }}",
		  type: 'POST',
		  cache: true,
		  data: {icon: e.icon, sl : '{{ $sl }}'},
		  dataType: 'json'
		});

	request.done(function(json) {
		unblockUI('.screen');
		reloadPreview();
		showSaved();
	});

	request.fail(function(jqXHR, textStatus) {
		console.log('Request failed, please try again (' + textStatus, ')');
		unblockUI('.screen');
	});
});

$('#app_page_name').on('keyup',function() {
	$('[data-id={{ $page->id }}]').find('.app-page-title').text($(this).val())
});

$('#app_page_name').on('keyup', $.debounce(1000, function() {
    blockUI('.screen');
    var request = $.ajax({
      url: "{{ url('/api/v1/app-edit/page-name') }}",
      type: 'POST',
      data: {sl : '{{ $sl }}', name : $('#app_page_name').val()},
      dataType: 'json'
    });

		request.done(function(json) {
			document.getElementById('device-screen').contentDocument.location.reload(true);

			setTimeout(function() {
				document.getElementById('device-screen').contentDocument.location.href = app_root + '/mobile/' + local_domain + '#{{ $hashPrefix }}/nav/' + json.slug;
			}, 1000);
			showSaved();
			unblockUI('.screen');
		});
	
		request.fail(function(jqXHR, textStatus) {
			console.log('Request failed, please try again (' + textStatus, ')');
			unblockUI('.screen');
		});
}));

$('#btn-delete').on('click', function() {
    swal({
      title: "{{ trans('global.are_you_sure') }}",
      text: "{{ trans('global.confirm_delete_app_page') }}",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "{{ trans('global.delete_page') }}",
      cancelButtonText: "{{ trans('global.cancel') }}",
      closeOnConfirm: true,
      closeOnCancel: true
    },
    function(isConfirm)
    {
      if(isConfirm)
      {
        blockUI();
        var request = $.ajax({
          url: "{{ url('/api/v1/app-edit/page-delete') }}",
          type: 'GET',
          data: {sl : '{{ $sl }}'},
          dataType: 'json'
        });

        request.done(function(json) {
            /* Decrement count */
            var count = parseInt($('#app-page-count').text());
            $('#app-page-count').text(count-1);

            /* Remove page */
			var page_index = $('[data-sl="{{ $sl }}"]').parents('.owl-item').index();
			$('#carousel_pages').data('owlCarousel').removeItem(page_index);
			deselectPage();

			$('.tooltip').remove();

			reloadPreview(true);
            unblockUI();
			showSaved();
        });

        request.fail(function(jqXHR, textStatus) {
            alert('Request failed, please try again (' + textStatus, ')');
            unblockUI();
        });
      }
    });
});

$('#btn-toggle-vis').on('click', function() {

	blockUI('.screen');
	var request = $.ajax({
	  url: "{{ url('/api/v1/app-edit/page-toggle') }}",
	  type: 'GET',
	  data: {sl : '{{ $sl }}'},
	  dataType: 'json'
	});

	request.done(function(json) {
		var page_index = $('[data-sl="{{ $sl }}"]').parents('.owl-item').index();

		// Toggle icon
		if($('#btn-toggle-vis i').hasClass('fa-eye'))
		{
			$('#btn-toggle-vis i').removeClass('fa-eye');
			$('#btn-toggle-vis i').addClass('fa-eye-slash');
			$('[data-sl="{{ $sl }}"]').removeClass('page-hidden');
		}
		else
		{
			$('#btn-toggle-vis i').removeClass('fa-eye-slash');
			$('#btn-toggle-vis i').addClass('fa-eye');
			$('[data-sl="{{ $sl }}"]').addClass('page-hidden');
		}

		$('.tooltip').remove();

		reloadPreview(true);
		unblockUI('.screen');
		showSaved();
	});

	request.fail(function(jqXHR, textStatus) {
		alert('Request failed, please try again (' + textStatus, ')');
		unblockUI('.screen');
	});

});

// QR
$.fn.modal.defaults.spinner = $.fn.modalmanager.defaults.spinner =
	'<div class="loading-spinner" style="margin-top: -210px;">' +
	'<div class="spinner" id="spinner"> <div class="rect1" style="background-color:#fff"></div> <div class="rect2" style="background-color:#fff"></div> <div class="rect3" style="background-color:#fff"></div> <div class="rect4" style="background-color:#fff"></div> <div class="rect5" style="background-color:#fff"></div> </div>' +
	'</div>';

$.fn.modalmanager.defaults.resize = true;

$('[data-modal-qr]').on('click', function()
{
	// create the backdrop and wait for next modal to be triggered
	$('body').modalmanager('loading');

	$modal.load($(this).attr('data-modal-qr'), '', function()
	{
		$('.tooltip').remove();
		$modal.modal();
		onModalLoad();
	});
});
</script>