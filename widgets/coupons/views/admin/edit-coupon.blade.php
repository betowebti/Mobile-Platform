<link rel="stylesheet" href="{{ url('/widgets/coupons/assets/css/app.css') }}" />
<div class="modal-dialog" style="width:770px">
	<div class="modal-content">
		<div class="modal-header">
			<button class="close" type="button" data-dismiss="modal">×</button>
			<?php echo trans('widget::global.edit_coupon') ?>
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

	// Extract currency symbol
	$currency = $currencies[$row->currency];
	preg_match('#\((.*?)\)#', $currency, $match);
	$row->currency_symbol = $match[1];
}
if($data != '')
{
	$action = 'update';
	$row = json_decode($data);
}
else
{
	$row = new stdClass;
	$row->type = 1; // 1 = discount, 2 = buy & get, 3 = custom
	$row->image = '';
	$row->title = '';
	$row->brief_description = '';
	$row->currency = 'USD';
	$row->currency_symbol = '$';
	$row->discount_type = 2; // 1 = amount, 2 = percentage
	$row->valid_start = date('Y-m-d');
	$row->valid_end = '';
	$row->original_price = 10;
	$row->discount = 10;
	$row->buy = 3;
	$row->get = 4;
	$row->details = '';
	$row->conditions = '';
	$row->deal = '';
    $row->code = uniqid();
}

echo Former::vertical_open()
	->class('form form-modal form-serialize')
	->action(url('api/v1/app-edit/save-widget'))
	->method('POST');

echo Former::hidden()
    ->name('type')
    ->id('type')
    ->class('form-control')
    ->forceValue($row->type);

echo Former::hidden()
    ->name('deal')
    ->class('form-control')
    ->forceValue($row->deal);

echo Former::hidden()
    ->name('code')
    ->class('form-control')
    ->forceValue($row->code);
?>
		<div class="modal-body">
			<div class="container-fluid">
				<div class="row">
					<div class="col-xs-8 col-md-8">

						<label class="control-label">{{ trans('widget::global.select_deal_type') }}</label>

                        <div>

                            <a href="javascript:setCouponType(1);" class="coupon-type" data-type="1">
                                <div>10%</div>
                                {{ trans('widget::global.discount') }}
                            </a>

                            <a href="javascript:setCouponType(2);" class="coupon-type" data-type="2">
                                <div>3 + 1</div>
                                {{ trans('widget::global.buy_and_get') }}
                            </a>

                            <a href="javascript:setCouponType(3);" class="coupon-type" data-type="3">
                                <div><i class="icon ion-scissors"></i></div>
                                {{ trans('widget::global.custom_offer') }}
                            </a>

                        </div>

					</div>
					<div class="col-xs-4 col-md-4 text-right">

						<label class="control-label" style="text-align:right">{{ trans('widget::global.image') }}</label>

       					<input type="hidden" name="image" id="image" value="{{ $row->image }}" class="form-control">

						<div>
							<button type="button" class="btn btn-danger btn-xs img-remove" data-id="image" title="{{ trans('global.remove_image') }}" style="position:absolute;left:207px;margin-top:113px;width:20px;padding:2px 0;<?php if($row->image == '') echo 'display:none'; ?>"><i class="fa fa-remove"></i></button>
							<a href="javascript:void(0)" class="img thumbnail card-image img-browse<?php if($row->image != '') echo ' filled'; ?>" id="image-image" data-id="image" title="{{ trans('global.select_image') }}" data-w="420" data-h="310"<?php if($row->image != '') echo ' style="background-image:url(\'' . $row->image . '\')"'; ?>>
								<i class="fa fa-plus-circle"></i>
								<div>
								420x310
								</div>
							</a>
						</div>

					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-xs-6 col-md-6">
<?php
echo Former::text()
    ->name('title')
    ->forceValue($row->title)
	->dataFvNotempty()
	->dataFvNotemptyMessage(trans('global.please_enter_a_value'))
	->label(trans('widget::global.deal_title'));

/*
 |--------------------------------------------------------------------------
 | Date range
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.valid_from');
$field_name = 'valid';

?>
<div class="form-group">
	<label class="control-label" for="{{ $field_name }}">{{ $field_label }}</label>
    <div class="input-group input-daterange" id="{{ $field_name }}-datepicker">
        <input type="text" data-fv-date="YYYY-MM-DD" name="valid_start" id="valid_start" class="form-control date-picker" placeholder="{{ trans('widget::global.valid_from') }}" value="{{ $row->valid_start }}" style="width:100%">
		<span class="input-group-addon">{{ trans('widget::global.until') }}</span>
        <input type="text" data-fv-date="YYYY-MM-DD" name="valid_end" id="valid_end" class="form-control date-picker" placeholder="{{ trans('widget::global.date') }}" value="{{ $row->valid_end }}" style="width:100%">
    </div>
</div>
<?php

echo Former::textarea()
    ->name('brief_description')
    ->forceValue($row->brief_description)
	->dataFvNotempty()
	->dataFvNotemptyMessage(trans('global.please_enter_a_value'))
	->label(trans('widget::global.brief_description'));
?>
					</div>
					<div class="col-xs-6 col-md-6">

                        <div class="deal-type" data-type="1">
<?php

/*
 |--------------------------------------------------------------------------
 | Currency
 |--------------------------------------------------------------------------
 */

echo Former::select()
    ->name('currency')
    ->forceValue($row->currency)
	->label(trans('widget::global.currency'))
	->placeholder(' ')
	->options($currencies)
	->class('select2-required');

echo '<div class="row">';
echo '	<div class="col-xs-5">';

echo Former::number()
    ->name('original_price')
    ->min(0)
    ->step('any')
    ->forceValue($row->original_price)
	->style('text-align:center')
	->prepend('<span class="currency_symbol">' . $row->currency_symbol . '</span>')
	->label(trans('widget::global.original_price'));

echo '	</div>';
echo '	<div class="col-xs-7">';

$discount_type1_active = ($row->discount_type == 1) ? ' active' : '';
$discount_type2_active = ($row->discount_type == 2) ? ' active' : '';
$discount_type1_checked = ($row->discount_type == 1) ? ' checked' : '';
$discount_type2_checked = ($row->discount_type == 2) ? ' checked' : '';

echo '		<div class="row">';
echo '			<div class="col-xs-5">';

echo Former::number()
    ->name('discount')
    ->min(0)
    ->step('any')
    ->forceValue($row->discount)
	->style('text-align:center')
	->label(trans('widget::global.discount'));

echo '			</div>';
echo '			<div class="col-xs-7">';

echo '<div class="btn-group" data-toggle="buttons" style="margin:23px 0 0 0"><label class="btn btn-primary' . $discount_type1_active . '"><input type="radio" class="form-control" name="discount_type" id="discount_type1" value="1"' . $discount_type1_checked . '><span class="currency_symbol">' . $row->currency_symbol . '</span></label><label class="btn btn-primary' . $discount_type2_active . '"><input type="radio" class="form-control" name="discount_type" id="discount_type2" value="2"' . $discount_type2_checked . '>%</label></div>';

echo '			</div>';
echo '		</div>';
echo '	</div>';
echo '</div>';

echo Former::text()
    ->name('new_price')
    ->readonly()
	->style('text-align:center')
	->prepend('<span class="currency_symbol">' . $row->currency_symbol . '</span>')
	->append('<span class="currency_abbr">' . $row->currency . '</span>')
	->label(trans('widget::global.new_price'));
?>
                        </div>

                        <div class="deal-type" data-type="2">
<?php

echo '<div class="row">';
echo '<div class="col-xs-6">';

echo Former::number()
    ->name('buy')
    ->min(0)
    ->forceValue($row->buy)
	->style('text-align:center')
	->label(trans('widget::global.buy'));

echo '</div>';
echo '<div class="col-xs-6">';

echo Former::number()
    ->name('get')
    ->min(0)
    ->forceValue($row->get)
	->style('text-align:center')
	->label(trans('widget::global.get'));

echo '</div>';
echo '</div>';

?>
                        </div>

                        <div class="deal-type" data-type="3">

                        </div>

					</div>
				</div>

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
	$('#details').summernote({
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
    ->name('details')
    ->forceValue($row->details)
	->label(trans('widget::global.deal_details'));

echo Former::textarea()
    ->name('conditions')
    ->forceValue($row->conditions)
	->label(trans('widget::global.conditions'));
?>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary" type="submit"><?php echo ($action == 'update') ? trans('widget::global.update_coupon') : trans('widget::global.add_coupon'); ?></button>
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

function setCouponType(type)
{
	$('#type').val(type);
	$('.coupon-type').removeClass('active');
	$('.deal-type').hide();
	$('.coupon-type[data-type=' + type + ']').addClass('active');
	$('.deal-type[data-type=' + type + ']').show();
}

setCouponType('{{ $row->type }}');

$('#currency').on('change', setCurrency);

function setCurrency()
{
	var currency = $('#currency option:selected').val();
	var text = $('#currency option:selected').text();
	var regExp = /\(([^)]+)\)/;
	var matches = regExp.exec(text);
	var symbol = matches[1];

	$('.currency_symbol').text(symbol);
	$('.currency_abbr').text(currency);
	calculateDiscount();
	calculateBuyAndGet();
}

$('[name=discount_type], #original_price, #discount').on('change keydown keyup', calculateDiscount);

calculateDiscount();

function calculateDiscount()
{
	var type = $('input[name=discount_type]:checked').val();
	var original_price = parseFloat($('#original_price').val()).toFixed(2);
	var discount = $('#discount').val();

	if(type == 1)
	{
		var text = $('#currency option:selected').text();
		var regExp = /\(([^)]+)\)/;
		var matches = regExp.exec(text);
		var symbol = matches[1];

		var new_price = original_price - discount;
		var coupon = symbol + '' + parseFloat(discount).toFixed(2).toString().replace('.00', '');
	}

	if(type == 2)
	{
		var new_price = original_price - ((original_price / 100) * discount);
		var coupon = parseFloat(discount).toFixed(2).toString().replace('.00', '') + '%';
	}

	if(original_price != 'NaN' && discount != 'NaN')
	{
		$('#new_price').val(parseFloat(new_price).toFixed(2).toString().replace('.00', ''));
		$('.coupon-type[data-type=1] div').text(coupon);
	}
}

$('#buy, #get').on('change keydown keyup', calculateBuyAndGet);

calculateBuyAndGet();

function calculateBuyAndGet()
{
	var buy = parseInt($('#buy').val());
	var get = parseInt($('#get').val());

	if(buy != 'NaN' && get != 'NaN')
	{
		$('.coupon-type[data-type=2] div').text(buy + '+' + (get - buy));
	}
}

$('.date-picker').datepicker({
	format: 'yyyy-mm-dd'
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
	var text = $('#currency option:selected').text();
	var regExp = /\(([^)]+)\)/;
	var matches = regExp.exec(text);
	var symbol = matches[1];

	json.currency_symbol = symbol;
	json.currency = $('#currency').val();
	json.i = '{{ \Input::get('i', '') }}';
    json.deal = $('.coupon-type.active div').text();

	addRepeaterRow('coupons', '{{ $action }}', json);

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