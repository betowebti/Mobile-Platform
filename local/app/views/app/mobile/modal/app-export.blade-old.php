<div class="modal-dialog" style="width:800px">
<?php
echo Former::open()
	->class('form-horizontal -validate')
	->action(url('api/v1/app-export/app-export'))
	->id('frmExport')
	->method('POST');

echo Former::hidden()
    ->value($sl)
    ->name('sl');
?>
	<div class="modal-content">
		<div class="modal-header">
			<button class="close" type="button" data-dismiss="modal">×</button>
			<?php echo trans('global.download_app') ?>
        </div>
		<div class="modal-body">

			<div class="jumbotron" style="padding:20px;margin-bottom:20px">
				<img src="{{ $app->icon(80); }}" class="pull-left" style="margin:0 20px 20px 0">
				<p class="lead no-margin">{{ trans('export.hero') }} <a href="javascript:void(0);" onclick="$('p').next().show();$(this).hide()">{{ trans('export.more') }}</a></p>
				<p class="lead no-margin" style="display:none"><br>{{ trans('export.explanation', ['url' => url('/')]) }} </p>
			</div>
<?php
echo Former::text()
    ->name('filename')
    ->forceValue($filename)
    ->autocorrect('off')
	->label(trans('export.file_name'));

echo Former::checkbox()
	->name('cordova')
	->label(trans('export.add_cordova'))
	->dataClass('switcher-success')
	->help(trans('export.add_cordova_info'))
	->check(false);

?>

			<div class="alert alert-warning"><i class="fa fa-exclamation-triangle"></i> {{ trans('export.warning_compatible_widgets') }}</div>
			<div class="alert alert-warning" style="margin-bottom:0"><i class="fa fa-exclamation-triangle"></i> {{ trans('export.warning_webserver') }}</div>

		</div>
		<div class="modal-footer">
			<button class="btn btn-primary" type="submit"><?php echo trans('global.download_app') ?></button>
			<button class="btn" data-dismiss="modal" type="button"><?php echo trans('global.close') ?></button>
		</div>
<?php
echo Former::close();
?>
	</div>
</div>
<script>

$('#frmExport').on('submit', function() {
    blockUI();
    setTimeout('getstatus()', 1000);
});

function getstatus(){
	$.ajax({
		url: "{{ url('api/v1/app-export/app-export-status') }}",
		type: "POST",
		dataType: 'json',
		success: function(data) {
			if (typeof data.status === 'undefined' || data.status == 'pending')
			{
				setTimeout('getstatus()', 1000);
			}
			else
			{
				unblockUI();
				$modal.modal('hide');
			}
		}
	});
}

$('[data-class]').switcher(
{
	theme: 'square',
	on_state_content: '<span class="fa fa-check"></span>',
	off_state_content: '<span class="fa fa-times"></span>'
});
</script>