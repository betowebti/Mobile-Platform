<?php
/*
 |--------------------------------------------------------------------------
 | Events
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.form');
$field_name = 'loyalty_cards';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name . '[]', NULL);

/*
 |--------------------------------------------------------------------------
 | Save as row will save every repeater row as its own record in the
 | database. The value should be the same name as the repeater's name,
 | and the data field should also have the same name (usually hidden textarea).
 | getData should be called like $field_name . '[]'
 |--------------------------------------------------------------------------
 */

echo Former::hidden()
    ->name('save_as_row')
    ->forceValue('loyalty_cards');

$i = 0;
$js = '';
if($field_value != NULL)
{
	foreach($field_value as $row)
	{
        $json_string = str_replace("'", "\'", str_replace('\\', '\\\\', json_encode($row)));
        //dd($json_string);
		$js .= "var data = JSON.parse('" . $json_string . "');
	data.i = '" . $i . "';";

		$js .= "addRepeaterRow('" . $field_name . "', 'insert', data);";
		$i++;
	}
}

?>
<table class="table table-bordered table-striped table-hover" id="{{ $field_name }}-holder">
	<thead>
		<tr>
			<th style="width:54px"> </th>
			<th style="width:170px">{{ trans('widget::global.image') }}</th>
			<th>{{ trans('widget::global.loyalty_card') }}</th>
			<th style="width:54px" class="text-center">{{ trans('widget::global.edit') }}</th>
			<th style="width:54px"> </th>
		</tr>
	</thead>
    <tbody>
    </tbody>
</table>

<button type="button" class="btn btn-success btn-block btn-lg" onclick="openItemPopup()"><i class="fa fa-plus"></i> {{ trans('widget::global.add_loyalty_card') }}</button>

<script>
var i = 0;
$('#{{ $field_name }}-holder tbody').sortable({
    handle: '.handle',
    axis: 'y',
 	placeholder: {
        element: function(currentItem) {
            return $('<tr class="el-placeholder"><td colspan="5"></td></tr>')[0];
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

function openItemPopup(data, i)
{
	if(typeof data === 'undefined') data = '';
	if(typeof i === 'undefined') i = '';

	$('body').modalmanager('loading');
	$modal.load(app_root + '/api/v1/widget/post/loyalty-cards/editCard', {data: data, i: i, sl: "{{ $sl }}"}, function()
	{
		$modal.modal();
	});
}

function addRepeaterRow(field, action, data)
{
    var source = $('#' + field + '-row').html();
    var template = Handlebars.compile(source);

	var thumb = (data.image != '') ? app_root + '/api/v1/thumb/nail?w=420&h=310&img=' + data.image : app_root + '/widgets/loyalty-cards/assets/img/empty.gif';

	if(action == 'update')
	{
		var html = template({
			i: data.i,
			title: data.title,
			code: data.code,
			stamps: data.stamps,
			stamp_icon: data.stamp_icon,
			freebie: data.freebie,
			freebie_icon: data.freebie_icon,
			brief_description: data.brief_description,
			valid_start: data.valid_start,
			valid_end: data.valid_end,
			thumb: thumb,
			card: JSON.stringify(data)
		});

	    $('#' + field + '-holder  #row' + data.i).replaceWith(html);
	}
	else
	{
		var html = template({
			i: i++,
			title: data.title,
			code: data.code,
			stamps: data.stamps,
			stamp_icon: data.stamp_icon,
			freebie: data.freebie,
			freebie_icon: data.freebie_icon,
			brief_description: data.brief_description,
			valid_start: data.valid_start,
			valid_end: data.valid_end,
			thumb: thumb,
			card: JSON.stringify(data)
		});

	    $('#' + field + '-holder tbody').append(html);
	}
}

</script>
<script id="{{ $field_name }}-row" type="text/x-handlebars-template">
<tr id="row@{{i}}">
    <td><div class="btn btn-text handle" style="cursor:ns-resize"><i class="fa fa-bars"></i></div></td>
    <td>
        <img src="@{{thumb}}" style="height:120px">
	</td>
    <td>
        <h4>
			{@{{title}}}
		</h4>
		<p class="text-muted"><span style="float:left;width:20px;text-align:center"> <i class="ion-calendar"></i></span> {@{{valid_start}}} @{{#if valid_end}}- {@{{valid_end}}}@{{/if}}</p>
		<p class="text-muted"><span style="float:left;width:20px;text-align:center"> <i class="@{{stamp_icon}}"></i></span> @{{stamps}} + 1</p>
		<p class="text-muted"><span style="float:left;width:20px;text-align:center"> <i class="@{{freebie_icon}}"></i></span> @{{freebie}}</p>
    </td>
    <td class="text-center">
		<button type="button" data-i="@{{i}}" class="btn btn-info form-options" onclick="openItemPopup('@{{escape card}}', '@{{i}}')"><i class="fa fa-pencil"></i></button>
        <textarea name="{{ $field_name }}[]" class="form-control" id="card@{{i}}" style="display:none">@{{card}}</textarea>
    </td>
    <td><button class="btn btn-danger" type="button" onclick="$(this).closest('tr').remove();"><i class="fa fa-remove"></i></button></td>
</tr>
</script>
<br>
<?php

/*
 |--------------------------------------------------------------------------
 | Social share
 |--------------------------------------------------------------------------
 */

$social_share = \Mobile\Controller\WidgetController::getData($page, 'social_share', 1);

echo Former::checkbox()
    ->name('social_share')
	->label(trans('global.social_share_help'))
    ->check((boolean) $social_share)
    ->dataClass('switcher-success')
	->novalidate();
?>