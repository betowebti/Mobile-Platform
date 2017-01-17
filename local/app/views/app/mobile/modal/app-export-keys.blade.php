<div class="modal-dialog" style="width:400px">
	<div class="modal-content">
		<div class="modal-header">
			<button class="close" type="button" data-dismiss="modal">×</button>
			{{ $title }}
        </div>
		<div class="modal-body" style="padding-bottom:0">
			<iframe src="about:blank" name="iSubmitKey" frameborder="0" style="display:none;width:0px;height:0px"></iframe>
<?php
Former::setOption('default_form_type', 'vertical');

echo Former::open_for_files()
	->class('form-horizontal form-stacked')
	->action(url('api/v1/app-export/app-key'))
	->id('frmKey')
	->target('iSubmitKey')
	->method('POST');

echo Former::hidden()
    ->value($sl)
    ->name('sl');

echo Former::hidden()
    ->value($pg_sl)
    ->name('pg_sl');

echo Former::hidden()
    ->value($platform)
    ->name('platform');

if ($platform == 'ios')
{
	echo '<button type="button" class="btn btn-primary" id="add_key"><i class="fa fa-plus"></i> ' . trans('export.add_certificate') . '</button>';

	echo '<div id="add_key_form">';
	echo '<div class="container-fluid">';

	echo Former::text()
		->name('title')
		->label(trans('export.title'))
		->required();

	echo Former::file()
		->class('styled')
		->name('certificate_p12')
		->label(trans('export.certificate_p12'))
		->required();

	echo Former::file()
		->class('styled')
		->name('provisioning_profile')
		->label(trans('export.provisioning_profile'))
		->required();

	echo Former::password()
		->name('ios_password')
		->label(trans('export.password'));

	echo Former::actions()
		->class('form-group')
		->lg_primary_submit(trans('global.save'))
		->lg_default_link(trans('global.cancel'), "javascript:$('#add_key_form').hide();");

	echo '</div>';
	echo '</div>';
}

if ($platform == 'android')
{
	echo '<button type="button" class="btn btn-primary" id="add_key"><i class="fa fa-plus"></i> ' . trans('export.add_certificate') . '</button>';

	echo '<div id="add_key_form">';
	echo '<div class="container-fluid">';

	echo Former::text()
		->name('title')
		->label(trans('export.title'))
		->required();

	echo Former::text()
		->name('alias')
		->label(trans('export.alias'))
		->required();

	echo Former::file()
		->class('styled')
		->name('keystore_file')
		->label(trans('export.keystore_file'))
		->required();

	echo Former::password()
		->name('certificate_password')
		->label(trans('export.certificate_password'));

	echo Former::password()
		->name('keystore_password')
		->label(trans('export.keystore_password'));

	echo Former::actions()
		->class('form-group')
		->lg_primary_submit(trans('global.save'))
		->lg_default_link(trans('global.cancel'), "javascript:$('#add_key_form').hide();");

	echo '</div>';
	echo '</div>';
}

if ($platform == 'winphone')
{
	echo '<button type="button" class="btn btn-primary" id="add_key"><i class="fa fa-plus"></i> ' . trans('export.add_publisher_id') . '</button>';

	echo '<div id="add_key_form">';
	echo '<div class="container-fluid">';

	echo Former::text()
		->name('title')
		->label(trans('export.title'))
		->required();

	echo Former::text()
		->name('publisher_id')
		->placeholder('xxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx')
		->label(trans('export.publisher_id'))
		->required();

	echo Former::actions()
		->class('form-group')
		->lg_primary_submit(trans('global.save'))
		->lg_default_link(trans('global.cancel'), "javascript:$('#add_key_form').hide();");

	echo '</div>';
	echo '</div>';
}

echo Former::close();
?>
			<br>
			<div id="keys">
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" type="button"><?php echo trans('global.close') ?></button>
		</div>

	</div>
</div>
<style type="text/css">
#add_key_form {
	margin-top:10px;
	display:none;
}
</style>
<script>
$('#frmKey').on('submit', function() {
	blockUI();
});

$('#add_key').on('click', function() {
	$('#add_key_form').toggle();
});

loadKeys();

function loadKeys()
{
	blockUI('#keys');
    var jqxhr = $.ajax({
	  type: 'POST',
	  url: "{{ url('api/v1/app/app-export-keys-table') }}",
	  data: { sl: "{{ $sl }}", platform: "{{ $platform }}" },
	  cache: false
	})
	.done(function(data) {
		$('#keys').html(data);
	})
	.fail(function() {
	  console.log('Error changing layout');
	})
	.always(function() {
		unblockUI('#keys');
	});
}

function deleteRow(id)
{
	if (confirm("{{ trans('global.are_you_sure') }}"))
	{
		blockUI('#keys');
		var jqxhr = $.ajax({
		  type: 'POST',
		  url: "{{ url('api/v1/app/app-export-delete-key-table') }}",
		  data: { sl: "{{ $sl }}", platform: "{{ $platform }}", id: id },
		  cache: false
		})
		.done(function(data) {
			loadKeys();
		})
		.fail(function() {
		  console.log('Error changing layout');
		})
		.always(function() {
			unblockUI('#keys');
		});
	}
}

function setRowActive(id, title)
{
	blockUI('#keys');
	var jqxhr = $.ajax({
	  type: 'POST',
	  url: "{{ url('api/v1/app/app-export-active-key-table') }}",
	  data: { sl: "{{ $sl }}", platform: "{{ $platform }}", id: id, title: title },
	  cache: false
	})
	.done(function(data) {
		$('#key_{{ $platform }}').html('<i class="fa fa-check"></i>  ' + title);
		loadKeys();
	})
	.fail(function() {
	  console.log('Error changing layout');
	})
	.always(function() {
		unblockUI('#keys');
	});
}

function submitReady()
{
	$('#add_key_form').toggle();
	$('#frmKey')[0].reset();
	loadKeys();
	unblockUI();
}
</script>