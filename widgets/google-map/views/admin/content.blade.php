<?php

/*
 |--------------------------------------------------------------------------
 | Repeater
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.form');
$field_name = 'form';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

?>
<table class="table table-bordered table-striped table-hover" id="{{ $field_name }}-holder">
	<thead>
		<tr>
			<th> </th>
			<th>{{ trans('widget::global.field_name') }}</th>
			<th>{{ trans('widget::global.type') }}</th>
			<th class="text-center">{{ trans('widget::global.options') }}</th>
			<th>{{ trans('widget::global.mandatory') }}</th>
			<th> </th>
		</tr>
	</thead>
    <tbody>
    </tbody>
</table>

<button type="button" class="btn btn-success btn-block btn-lg" onclick="addRepeaterRow('{{ $field_name }}')"><i class="fa fa-plus"></i> {{ trans('widget::global.add_form_element') }}</button>

<script>
var i = 0;
$('#{{ $field_name }}-holder tbody').sortable({
    handle: '.handle',
    axis: 'y',
 	placeholder: {
        element: function(currentItem) {
            return $('<tr class="el-placeholder"><td colspan="6"></td></tr>')[0];
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

function addRepeaterRow(field)
{
    var source = $('#' + field + '-row').html();
    var template = Handlebars.compile(source);

    var html = template({
        i: i++,
    });
    $('#' + field + '-holder tbody').append(html);

    $('[data-class]').switcher(
    {
        theme: 'square',
        on_state_content: '<span class="fa fa-check"></span>',
        off_state_content: '<span class="fa fa-times"></span>'
    });
}
</script>
<script id="{{ $field_name }}-row" type="text/x-handlebars-template">
<tr id="row@{{i}}">
    <td style="width:54px"><div class="btn btn-text handle" style="cursor:ns-resize"><i class="fa fa-bars"></i></div></td>
    <td>
        <input type="text" name="field_name[]" id="field_name@{{i}}" class="form-control">
	</td>
    <td>
		<select name="type[]" id="type@{{i}}" class="form-control">
			<option>Text</option>
			<option>E-mail</option>
			<option>Name</option>
			<option>Name</option>
		</select>
    </td>
    <td class="text-center">
		<button class="btn btn-info"><i class="fa fa-wrench"></i></button>
		<textarea name="options[]" id="options@{{i}}" style="display:none"></textarea>
    </td>
    <td>
		<input data-class="switcher-success" novalidate="true" id="required@{{i}}" type="checkbox" name="required[]" value="1">
    </td>
    <td style="width:54px"><button class="btn btn-danger" type="button" onclick="$(this).closest('tr').remove();"><i class="fa fa-remove"></i></button></td>
</tr>
</script>
<br>
<?php
?>