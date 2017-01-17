<?php

/*
 |--------------------------------------------------------------------------
 | Repeater
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.images');
$field_name = 'images';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

$field_value = json_decode($field_value);

$i = 0;
$js = '';
if($field_value != NULL)
{
	foreach($field_value->image as $row)
	{
		$image = $field_value->image[$i];
		$thumb = ($image != '') ? '<img src="' . url('/api/v1/thumb/nail?w=100&h=100&img=' . $image) . '" class="thumbnail" style="max-width:100%; margin:0">' : '';
		$js .= "var data = {};
	data.image = '" . $image . "';
	data.thumb = '" . $thumb . "';";

		$js .= "addRepeaterRow('" . $field_name . "', data);";
		$i++;
	}
}

?>
<input type="hidden" name="{{ $field_name }}" value="">
<table class="table table-bordered table-striped table-hover" id="{{ $field_name }}-holder">
    <tbody>
    </tbody>
</table>

<button type="button" class="btn btn-success btn-block btn-lg" onclick="addRepeaterRow('{{ $field_name }}', 'data')"><i class="fa fa-plus"></i> {{ trans('widget::global.add_image') }}</button>

<br>

<script>
var i = 0;
$('#{{ $field_name }}-holder tbody').sortable({
    handle: '.handle',
 	placeholder: {
        element: function(currentItem) {
            return $('<tr class="el-placeholder"><td colspan="4"></td></tr>')[0];
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
		data.image = '';
		data.thumb = '';
	}

    var html = template({
        i: i++, 
        image: data.image, 
        thumb: data.thumb
    });
    $('#' + field + '-holder tbody').append(html);
}
</script>
<style type="text/css">
#{{ $field_name }}-holder td {
	vertical-align:middle;
}
</style>
<script id="{{ $field_name }}-row" type="text/x-handlebars-template">
<tr id="row@{{i}}">
    <td style="width:54px"><div class="btn btn-text handle" style="cursor:ns-resize"><i class="fa fa-bars"></i></div></td>
    <td>
        <input type="hidden" name="{{ $field_name }}[image][]" id="image@{{i}}" value="@{{image}}" class="form-control">
		<div class="btn-group" role="group">
			<button type="button" class="btn btn-primary img-browse" data-id="image@{{i}}"><i class="fa fa-picture-o"></i> {{ trans('global.select_image') }}</button>
			<button type="button" class="btn btn-danger img-remove" data-id="image@{{i}}" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
		</div>
	</td>
    <td>
        <div id="image@{{i}}-image" class="img-thumb" data-w="100" data-h="100">{@{{thumb}}}</div>
    </td>
    <td style="width:54px"><button class="btn btn-danger" type="button" onclick="$(this).closest('tr').remove();"><i class="fa fa-remove"></i></button></td>
</tr>
</script> 