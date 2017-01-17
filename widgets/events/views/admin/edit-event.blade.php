<div class="modal-dialog" style="width:770px">
	<div class="modal-content">
		<div class="modal-header">
			<button class="close" type="button" data-dismiss="modal">×</button>
			<?php echo trans('widget::global.edit_event') ?>
        </div>
<?php
$action = 'insert';
$data = \Input::get('data', '');

$row_id = '';

if($row_id != '')
{
	$action = 'update';
	$row = \Mobile\Controller\WidgetController::getData($page, 'row[' . $row_id . ']');
	$row = json_decode($row);
}
if($data != '')
{
	$action = 'update';
	$row = json_decode($data);
}
else
{
	$row = new stdClass;
	$row->image = '';
	$row->title = '';
	$row->location = '';
	$row->brief_description = '';
	$row->description = '';
	$row->event_start = date('Y-m-d');
	$row->event_end = '';
}

echo Former::vertical_open()
	->class('form form-modal form-serialize')
	->action(url('api/v1/app-edit/save-widget'))
	->method('POST');

?>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-8 col-md-8">
<?php
echo Former::text()
    ->name('title')
    ->forceValue($row->title)
	->dataFvNotempty()
	->dataFvNotemptyMessage(trans('global.please_enter_a_value'))
	->label(trans('widget::global.event'));

$field_label = trans('widget::global.location');
$field_name = 'location';

echo Former::text()
    ->name($field_name)
    ->forceValue($row->location)
	->label($field_label)
	->dataFvNotempty()
	->dataFvNotemptyMessage(trans('global.please_enter_a_value'))
    ->prepend('<i class="fa fa-map-marker"></i>');

/*
 |--------------------------------------------------------------------------
 | Date range
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.date');
$field_name = 'date';

?>
<div class="form-group">
	<label class="control-label" for="{{ $field_name }}">{{ $field_label }}</label>
    <div class="input-group input-daterange" id="{{ $field_name }}-datepicker">
        <input type="text" data-fv-date="YYYY-MM-DD" name="event_start" id="event_start" data-fv-notempty="true" data-fv-notempty-message="{{ trans('global.please_enter_a_value') }}" class="form-control date-picker" placeholder="{{ trans('widget::global.start') }}" value="{{ $row->event_start }}" style="width:100%">
		<span class="input-group-addon">{{ trans('widget::global.until') }}</span>
        <input type="text" data-fv-date="YYYY-MM-DD" name="event_end" id="event_end" class="form-control date-picker" placeholder="{{ trans('widget::global.end') }}" value="{{ $row->event_end }}" style="width:100%">
    </div>
</div>

					</div>
					<div class="col-xs-4 col-md-4 text-right">

						<label class="control-label" style="margin-right:142px">{{ trans('widget::global.image') }}</label>

       					<input type="hidden" name="image" id="image" value="{{ $row->image }}" class="form-control">

						<div>
							<button type="button" class="btn btn-danger btn-xs img-remove" data-id="image" title="{{ trans('global.remove_image') }}" style="position:absolute;left:207px;margin-top:113px;width:20px;padding:2px 0;<?php if($row->image == '') echo 'display:none'; ?>"><i class="fa fa-remove"></i></button>
							<a href="javascript:void(0)" class="img thumbnail card-image img-browse<?php if($row->image != '') echo ' filled'; ?>" id="image-image" data-id="image" title="{{ trans('global.select_image') }}" data-w="420" data-h="310"style="<?php if($row->image != '') echo 'background-image:url(\'' . $row->image . '\')'; ?>;margin:1px 0 0 0;float:right">
								<i class="fa fa-plus-circle"></i>
								<div>
								420x310
								</div>
							</a>
						</div>

					</div>
				</div>
				<hr>
<?php
echo Former::textarea()
    ->name('brief_description')
    ->forceValue($row->brief_description)
	->dataFvNotempty()
	->dataFvNotemptyMessage(trans('global.please_enter_a_value'))
	->label(trans('widget::global.brief_description'));
?>

				<hr>
				<div class="row">
					<div class="col-xs-12">
<?php
/*
 |--------------------------------------------------------------------------
 | Details
 |--------------------------------------------------------------------------
 */

?>
<script>
if (! $('html').hasClass('ie8')) {
	$('#description').summernote({
		height: 100,
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
    ->name('description')
    ->forceValue($row->description)
	->label(trans('widget::global.description'));

?>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary" type="submit"><?php echo ($action == 'update') ? trans('widget::global.update_event') : trans('widget::global.add_event'); ?></button>
			<button class="btn" data-dismiss="modal" type="button"><?php echo Lang::get('global.cancel') ?></button>
		</div>
<?php
echo Form::token();

// Use </form> instead of echo Former::close();
?>
		</form>
	</div>
</div>
<script>

$('.date-picker').datepicker({
	format: 'yyyy-mm-dd'
}).on('changeDate', function(ev) {
	$('.form-serialize').data('formValidation').resetForm();
});

$('.img-browse').on('click', function()
{
	// trigger the reveal modal with elfinder inside
	$.colorbox(
	{
		href: elfinderUrl + $(this).attr('data-id') + '/processModalWidgetFile',
		fastIframe: true,
		iframe: true,
		width: '70%',
		height: '80%'
	});
});

$('.img-remove').on('click', function()
{
	$('#' + $(this).attr('data-id')).val('');
    $('#' + $(this).attr('data-id') + '-image').css('background-image', 'none');
    $('#' + $(this).attr('data-id') + '-image').removeClass('filled');
    $(this).hide();
});

window.processModalWidgetFile = function(filePath, requestingField)
{
    if($('#' + requestingField).attr('type') == 'text')
    {
	    $('#' + requestingField).val(decodeURI(filePath));
    }

    if($('#' + requestingField + '-image').length)
    {
		var w = ($('#' + requestingField + '-image').attr('data-w').length) ? $('#' + requestingField + '-image').attr('data-w') : 120;
		var h = ($('#' + requestingField + '-image').attr('data-h').length) ? $('#' + requestingField + '-image').attr('data-h') : 120;
		var thumb = app_root + '/api/v1/thumb/nail?w=' + w + '&h=' + h + '&img=' + filePath;

		$('#' + requestingField + '-image').addClass('bg-loading');

		$('<img/>').attr('src', decodeURI(thumb)).load(function() {
			$(this).remove();
			$('#' + requestingField + '-image').css('background-image', 'url("' + decodeURI(thumb) + '")');
			$('#' + requestingField + '-image').removeClass('bg-loading');
		});

		$('#' + requestingField + '-image').addClass('filled');
        $('#' + requestingField).val(decodeURI(filePath));
		$('.img-remove[data-id=' + requestingField + ']').show();
    }
}

select2();

$('.form-modal').formValidation(
{
	framework: 'bootstrap',
	icon: {
		valid: false,
		invalid: false,
		validating: false
	}
}).on('success.form.fv', function(e)
{
	var json = $('.form-modal.form-serialize .form-control').serializeObject();

	json.i = '{{ \Input::get('i', '') }}';

	addRepeaterRow('events', '{{ $action }}', json);

    $modal.modal('hide');

	// Prevent form submission
	e.preventDefault();
}).on('err.form.fv', function(e)
{
	// Prevent form submission
	e.preventDefault();
});

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
</script>