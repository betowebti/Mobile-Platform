<div class="modal-dialog" style="width:770px">
	<div class="modal-content">
		<div class="modal-header">
			<button class="close" type="button" data-dismiss="modal">×</button>
			<?php echo trans('widget::global.edit_loyalty_card') ?>
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
	if (! isset($row->multiple_use)) $row->multiple_use = false;
}
else
{
	$row = new stdClass;
	$row->image = '';
	$row->title = '';
	$row->code = mt_rand(1000,9999);
	$row->stamp_icon = 'ion-android-star-outline';
	$row->stamps = 11;
	$row->freebie_icon = 'ion-ios-box';
	$row->freebie = '';
	$row->brief_description = '';
	$row->description = '';
	$row->valid_start = date('Y-m-d');
	$row->valid_end = '';
	$row->multiple_use = true;
}

Former::setOption('push_checkboxes', false);

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

echo '<div class="row">';
echo '<div class="col-md-8">';

echo Former::text()
    ->name('title')
    ->forceValue($row->title)
	->dataFvNotempty()
	->dataFvNotemptyMessage(trans('global.please_enter_a_value'))
	->label(trans('widget::global.title'));


$field_label = trans('widget::global.stamps');
$field_name = 'stamps';

for ($i = 2; $i < 25; $i++)
{
	$stamp_options[$i] = trans('widget::global.is_one_freebie', ['amount' => $i]);
}

echo Former::select()
    ->name($field_name)
    ->forceValue($row->stamps)
	->label($field_label)
	->options($stamp_options)
	->dataFvNotempty()
	->dataFvNotemptyMessage(trans('global.please_enter_a_value'))
    ->prepend('<button class="btn btn-default" id="stamp_icon_select" style="font-size:22px;padding:4px 7px 4px;"></button><input type="hidden" class="form-control" name="stamp_icon" id="stamp_icon" value="' . $row->stamp_icon . '">');

?>
<script>
$('#stamp_icon_select').iconpicker({ 
	arrowClass: 'btn-danger',
	arrowPrevIconClass: 'fa fa-chevron-left',
	arrowNextIconClass: 'fa fa-chevron-right',
	cols: 8,
	rows: 6,
	icon: '{{ $row->stamp_icon }}',
	iconset: $.iconset_ionicon,
	labelHeader: '{0} of {1} pages',
	labelFooter: '{0} - {1} of {2} icons',
	placement: 'bottom',
	search: true,
	searchText: '{{ trans('global.search_') }}',
	selectedClass: 'btn-primary',
	unselectedClass: ''
}).on('change', function(e) { 
	$('#stamp_icon').val(e.icon);
});
</script>
<?php

echo '</div>';
echo '<div class="col-md-4">';

echo Former::text()
    ->name('code')
    ->forceValue($row->code)
	->label(trans('widget::global.unlock_code'))
    ->append(Former::button('<i class="fa fa-undo"></i>')->id('random_code'))
	->help(trans('widget::global.unlock_code_info'));

echo '</div>';
echo '</div>';
?>
<script>
$('#random_code').on('click', function()
{
    $('#code').val(randomCode(4));
});
</script>
<?php

$field_label = trans('widget::global.freebie_name');
$field_name = 'freebie';

echo Former::text()
    ->name($field_name)
    ->forceValue($row->freebie)
	->label($field_label)
	->dataFvNotempty()
	->dataFvNotemptyMessage(trans('global.please_enter_a_value'))
    ->prepend('<button class="btn btn-default" id="freebie_icon_select" style="font-size:22px;padding:4px 7px 4px;"></button><input type="hidden" class="form-control" name="freebie_icon" id="freebie_icon" value="' . $row->freebie_icon . '">');


?>
<script>
$('#freebie_icon_select').iconpicker({ 
	arrowClass: 'btn-danger',
	arrowPrevIconClass: 'fa fa-chevron-left',
	arrowNextIconClass: 'fa fa-chevron-right',
	cols: 8,
	rows: 6,
	icon: '{{ $row->freebie_icon }}',
	iconset: $.iconset_ionicon,
	labelHeader: '{0} of {1} pages',
	labelFooter: '{0} - {1} of {2} icons',
	placement: 'bottom',
	search: true,
	searchText: '{{ trans('global.search_') }}',
	selectedClass: 'btn-primary',
	unselectedClass: ''
}).on('change', function(e) {
	$('#freebie_icon').val(e.icon);
});
</script>
<?php


/*
 |--------------------------------------------------------------------------
 | Date range
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.valid_from');
$field_name = 'date';

?>
<div class="form-group">
	<label class="control-label" for="{{ $field_name }}">{{ $field_label }}</label>
    <div class="input-group input-daterange" id="{{ $field_name }}-datepicker">
        <input type="text" data-fv-date="YYYY-MM-DD" name="valid_start" id="valid_start" data-fv-notempty="true" data-fv-notempty-message="{{ trans('global.please_enter_a_value') }}" class="form-control date-picker" placeholder="{{ trans('widget::global.date_format') }}" value="{{ $row->valid_start }}" style="width:100%">
		<span class="input-group-addon">{{ trans('widget::global.until') }}</span>
        <input type="text" data-fv-date="YYYY-MM-DD" name="valid_end" id="valid_end" class="form-control date-picker" placeholder="{{ trans('widget::global.date_format') }}" value="{{ $row->valid_end }}" style="width:100%">
    </div>
</div>
<?php

echo Former::checkbox()
    ->name('multiple_use')
    ->value('1')
	->label(trans('widget::global.can_be_used_multiple_times'))
    ->check((boolean) $row->multiple_use)
    ->dataClass('switcher-success')
	->novalidate();

?>
					</div>
					<div class="col-xs-4 col-md-4 text-right">

						<label class="control-label" style="text-align:right">{{ trans('widget::global.image') }}</label>

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
	->label(trans('widget::global.description_and_disclaimer'));

?>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary" type="submit"><?php echo ($action == 'update') ? trans('widget::global.update_loyalty_card') : trans('widget::global.add_loyalty_card'); ?></button>
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
$('[data-class]').switcher(
{
	theme: 'square',
	on_state_content: '<span class="fa fa-check"></span>',
	off_state_content: '<span class="fa fa-times"></span>'
});

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
    /* Set non-checked checkboxes to value="0" */
    var cb = $('.form-modal.form-serialize')[0].getElementsByTagName('input');

    for(var i=0;i<cb.length;i++){ 
        if(cb[i].type=='checkbox' && !cb[i].checked)
        {
           cb[i].value = 0;
           cb[i].checked = true;
        }
    }

	var json = $('.form-modal.form-serialize .form-control,.form-modal.form-serialize [data-class="switcher-success"]').serializeObject();

console.log(json);

	json.i = '{{ \Input::get('i', '') }}';

	addRepeaterRow('loyalty_cards', '{{ $action }}', json);

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