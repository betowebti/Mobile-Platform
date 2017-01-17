<?php
/*
 |--------------------------------------------------------------------------
 | Videos
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.text');
$field_name = 'video';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name);

$field_value = json_decode($field_value);

$i = 0;
$js = '';
if($field_value != NULL)
{
	foreach($field_value->url as $row)
	{
		$js .= "var data = {};
	data.url = '" . $field_value->url[$i] . "';";

		$js .= "addRepeaterRow('" . $field_name . "', data);";
		$i++;
	}
}

?>
<input type="hidden" name="{{ $field_name }}" value="">
<table class="table table-bordered table-striped table-hover" id="{{ $field_name }}-holder">
	<thead>
		<tr>
			<th style="width:54px"></th>
			<th>{{ trans('widget::global.video') }}</th>
			<th style="width:54px"></th>
		</tr>
	</thead>
    <tbody>
    </tbody>
</table>

<button type="button" class="btn btn-success btn-block btn-lg" onclick="addRepeaterRow('{{ $field_name }}')"><i class="fa fa-plus"></i> {{ trans('widget::global.add_video') }}</button>

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
		data.url = '';
	}

    var html = template({
        i: i++, 
        url: data.url, 
        field: field
    });
    $('#' + field + '-holder tbody').append(html);
}
</script>
<script id="{{ $field_name }}-row" type="text/x-handlebars-template">
<tr id="row@{{i}}">
    <td><div class="btn btn-text handle" style="cursor:ns-resize"><i class="fa fa-bars"></i></div></td>
    <td>
		<input class="form-control" placeholder="{{ trans('widget::global.url_placeholder') }}" type="text" value="@{{url}}" name="{{ $field_name }}[url][]">
    </td>
    <td><button class="btn btn-danger" type="button" onclick="$(this).closest('tr').remove();"><i class="fa fa-remove"></i></button></td>
</tr>
</script>