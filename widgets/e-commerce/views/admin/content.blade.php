<ul class="nav nav-tabs" id="widgetTabs">
	<li class="active">
		<a href="#tab-products">{{ trans('widget::global.products') }}</a>
	</li>
	<li>
		<a href="#tab-general">{{ trans('widget::global.general_settings') }}</a>
	</li>
	<li>
		<a href="#tab-payment">{{ trans('widget::global.payment_provider') }}</a>
	</li>
</ul>

<div class="tab-content tab-content-bordered panel-padding">
	<div class="tab-pane fade in active" id="tab-products">

<?php

/*
 |--------------------------------------------------------------------------
 | Products
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.products');
$field_name = 'products';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

$field_value = json_decode($field_value);

$i = 0;
$js = '';
if($field_value != NULL)
{
	foreach($field_value->title as $row)
	{
		$photo = $field_value->photo[$i];
		$thumb = ($photo != '') ? '<img src="' . url('/api/v1/thumb/nail?w=100&h=100&img=' . $photo) . '" class="thumbnail" style="max-width:100%; margin:0">' : '';
		$js .= "var data = {};
	data.photo = '" . $photo . "';
	data.thumb = '" . $thumb . "';
	data.title = '" . str_replace("'", "\'", $field_value->title[$i]) . "';
	data.price = '" . str_replace("'", "\'", $field_value->price[$i]) . "';
	data.desc = '" . str_replace(chr(11), '\\n', str_replace(chr(13), '\\n', str_replace(chr(10), '\\n', $field_value->desc[$i]))) . "';
	data.active = '" . (boolean)$field_value->active[$i] . "';";

		$js .= "addRepeaterRow('" . $field_name . "', data);";
		$i++;
	}
}

?>
<table class="table table-bordered table-striped table-hover" id="{{ $field_name }}-holder">
	<thead>
		<tr>
			<th> </th>
			<th>{{ trans('widget::global.photo') }}</th>
			<th>{{ trans('widget::global.thumbnail') }}</th>
			<th>{{ trans('widget::global.title') }}</th>
			<th class="text-center">{{ trans('widget::global.description') }}</th>
			<th>{{ trans('widget::global.price') }}</th>
			<th>{{ trans('widget::global.active') }}</th>
			<th> </th>
		</tr>
	</thead>
    <tbody>
    </tbody>
</table>

<button type="button" class="btn btn-success btn-block btn-lg" onclick="addRepeaterRow('{{ $field_name }}')"><i class="fa fa-plus"></i> {{ trans('widget::global.add_product') }}</button>

<script>
var i = 0;
$('#{{ $field_name }}-holder tbody').sortable({
    handle: '.handle',
    axis: 'y',
 	placeholder: {
        element: function(currentItem) {
            return $('<tr class="el-placeholder"><td colspan="8"></td></tr>')[0];
        },
        update: function(container, p) {
            return;
        }
    },
    helper: function(e, tr)
    {
        var $originals = tr.children();
        var $helper = tr.clone();
		$helper.addClass('el-dragging');
        $helper.children().each(function(index)
        {
			$(this).width(parseInt($originals.eq(index).width()) + 21);
			$(this).height($originals.eq(index).height());
        });
        return $helper;
    }
});

<?php
echo $js;
?>

function addRepeaterRow(field, data)
{
    var source = $('#' + field + '-row').html();
    var template = Handlebars.compile(source);

	if(typeof data === 'undefined')
	{
		data = {};
		data.photo = '';
		data.thumb = '';
		data.title = '';
		data.desc = '';
		data.price = '';
		data.active = true;
	}

    var html = template({
        i: i++,
        photo: data.photo,
        thumb: data.thumb,
        title: data.title,
        desc: data.desc,
        price: data.price,
        active: data.active,
    });
    $('#' + field + '-holder tbody').append(html);

    $('[data-toggle~=desc-popover]').popover(
    {
        container: 'body',
        html: true,
        template: '<div class="popover" role="tooltip" style="width:380px"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    });

    $('[data-toggle~=tooltip]').tooltip(
    {
        container: 'body'
    });

    $('[data-class]').switcher(
    {
        theme: 'square',
        on_state_content: '<span class="fa fa-check"></span>',
        off_state_content: '<span class="fa fa-times"></span>'
    });
}

$('#{{ $field_name }}-holder').on('click', '.product-desc', function() {
    var i = $(this).attr('data-i');
    $('#popover_desc' + i).val($('#desc' + i).val());
});

$('body').on('click', '.product-desc-save', function() {
    var i = $(this).attr('data-i');
    $('#desc' + i).val($('#popover_desc' + i).val());

    $('[data-toggle="desc-popover"]').each(function () {
        $(this).popover('hide');
    });
});

$('body').on('click', '.product-desc-cancel', function() {
    $('[data-toggle="desc-popover"]').each(function () {
        $(this).popover('hide');
    });
});

/* Close popovers when clicking outside */
$('body').on('click', function(e) {
    $('[data-toggle="desc-popover"]').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});

</script>
<script id="{{ $field_name }}-row" type="text/x-handlebars-template">
<tr id="row@{{i}}">
    <td style="width:54px"><div class="btn btn-text handle" style="cursor:ns-resize"><i class="fa fa-bars"></i></div></td>
    <td style="width:94px">
        <input type="hidden" name="{{ $field_name }}[photo][]" id="photo@{{i}}" value="@{{photo}}" class="form-control">
		<div class="btn-group" role="group" style="width:76px">
			<button type="button" class="btn btn-primary img-browse" data-id="photo@{{i}}" data-toggle="tooltip" title="{{ trans('widget::global.select_photo') }}"><i class="fa fa-picture-o"></i></button>
			<button type="button" class="btn btn-danger img-remove" data-id="photo@{{i}}" data-toggle="tooltip" title="{{ trans('widget::global.remove_photo') }}"><i class="fa fa-remove"></i></button>
		</div>
	</td>
    <td>
        <div id="photo@{{i}}-image" class="img-thumb" data-w="100" data-h="100">{@{{thumb}}}</div>
    </td>
    <td>
		<input type="text" name="{{ $field_name }}[title][]" id="field_name@{{i}}" value="@{{title}}" class="form-control">
    </td>
    <td class="text-center">
		<button type="button" data-i="@{{i}}" class="btn btn-info product-desc" data-toggle="desc-popover" data-content="{{ trans('widget::global.description_info') }}<textarea class='form-control product-desc-textarea' id='popover_desc@{{i}}'>@{{options}}</textarea> <button type='button' class='btn btn-primary product-desc-save' data-i='@{{i}}'>{{ trans('widget::global.save') }}</button> <button type='button' class='btn btn-default product-desc-cancel'>{{ trans('widget::global.cancel') }}</button>"><i class="fa fa-pencil"></i></button>
        <textarea name="{{ $field_name }}[desc][]" class="form-control" id="desc@{{i}}" style="display:none">@{{desc}}</textarea>
    </td>
    <td>
		<input type="number" name="{{ $field_name }}[price][]" id="field_price@{{i}}" step="any" value="@{{price}}" class="form-control" style="width:94px;text-align:right">
    </td>
    <td>
		<input data-class="switcher-success" novalidate="true" id="active@{{i}}" type="checkbox" name="{{ $field_name }}[active][]" value="1" @{{#if active}}checked@{{/if}}>
    </td>
    <td style="width:54px"><button class="btn btn-danger" type="button" onclick="$(this).closest('tr').remove();"><i class="fa fa-remove"></i></button></td>
</tr>
</script>
<style type="text/css">
.product-desc-textarea {
    margin:5px 0;
    height:158px !important;
}
</style>

	</div>
	<div class="tab-pane fade" id="tab-general">
	<h4>{{ trans('widget::global.currency') }}</h4>
	<hr>
<?php

/*
 |--------------------------------------------------------------------------
 | Currency
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.currency');
$field_name = 'currency';
$field_options = array(
	'USD' => 'US Dollar',
	'AUD' => 'Australian Dollar',
	'BRL' => 'Brazilian Real',
	'CAD' => 'Canadian Dollar',
	'CZK' => 'Czech Koruna',
	'DKK' => 'Danish Krone',
	'EUR' => 'Euro',
	'HKD' => 'Hong Kong Dollar',
	'HUF' => 'Hungarian Forint',
	'ILS' => 'Israeli New Sheqel',
	'JPY' => 'Japanese Yen',
	'MYR' => 'Malaysian Ringgit',
	'MXN' => 'Mexican Peso',
	'NOK' => 'Norwegian Krone',
	'NZD' => 'New Zealand Dollar',
	'PLN' => 'Polish Zloty',
	'GBP' => 'Pound Sterling',
	'SGD' => 'Singapore Dollar',
	'SEK' => 'Swedish Krona',
	'CHF' => 'Swiss Franc',
	'THB' => 'Thai Baht',
	'BTC' => 'Bitcoin'
);
$field_default_value = 'USD';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::select()
    ->name($field_name)
    ->forceValue($field_value)
	->label('')
	->placeholder(' ')
	->options($field_options)
    ->help($field_help)
	->class('select2-required');

?>
	<br>
	<h4>{{ trans('widget::global.shipping') }}</h4>
	<hr>
<?php 

/*
 |--------------------------------------------------------------------------
 | Flat rate
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.flat_rate');
$field_help = trans('widget::global.flat_rate_help');
$field_name = 'flat_rate';
$field_default_value = '0';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo Former::number()
    ->name($field_name)
    ->forceValue($field_value)
    ->help($field_help)
	->label($field_label);

/*
 |--------------------------------------------------------------------------
 | Quantity rate
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.quantity_rate');
$field_help = trans('widget::global.quantity_rate_help');
$field_name = 'quantity_rate';
$field_default_value = '0';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo Former::number()
    ->name($field_name)
    ->forceValue($field_value)
    ->help($field_help)
	->label($field_label);

/*
 |--------------------------------------------------------------------------
 | Total rate
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.total_rate');
$field_help = trans('widget::global.total_rate_help');
$field_name = 'total_rate';
$field_default_value = '0.00';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo Former::number()
    ->name($field_name)
    ->forceValue($field_value)
    ->help($field_help)
    ->step('0.01')
	->label($field_label);

?>
	<br>
	<h4>{{ trans('widget::global.tax') }}</h4>
	<hr>
<?php 

/*
 |--------------------------------------------------------------------------
 | Tax
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.tax_rate');
$field_name = 'tax_rate';
$field_default_value = '0.00';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = trans('widget::global.tax_rate_help');

echo Former::number()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->step('0.01')
    ->help($field_help);

/*
 |--------------------------------------------------------------------------
 | Tax shipping
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.tax_shipping');
$field_name = 'tax_shipping';
$field_default_value = 1; // checked 1/0
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo Former::checkbox()
    ->name($field_name)
	->label($field_label)
    ->check((boolean)$field_value)
    ->dataClass('switcher-success')
    ->help(trans('widget::global.tax_shipping_help'))
	->novalidate();

?>

	</div>
	<div class="tab-pane fade" id="tab-payment">

<?php

/*
 |--------------------------------------------------------------------------
 | Payment provider
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.payment_provider');
$field_name = 'payment_provider';
$field_options = array('PayPal' => 'PayPal');
$field_default_value = 'PayPal';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::select()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
	->placeholder(' ')
	->options($field_options)
    ->help($field_help)
	->class('select2-required');

/*
 |--------------------------------------------------------------------------
 | E-mail address
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.email');
$field_name = 'payment_provider_email';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = trans('widget::global.checkout_email_help');

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help)
    ->prepend('<i class="fa fa-envelope-o"></i>');

/*
 |--------------------------------------------------------------------------
 | Sandbox
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.sandbox_mode');
$field_name = 'sandbox';
$field_default_value = 1; // checked 1/0
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo Former::checkbox()
    ->name($field_name)
	->label($field_label)
    ->check((boolean)$field_value)
    ->dataClass('switcher-success')
    ->help(trans('widget::global.sandbox_help'))
	->novalidate();
?>

	</div>
</div>
<script>
$('#widgetTabs a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})
</script>