<?php

/*
 |--------------------------------------------------------------------------
 | Title
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.title');
$field_name = 'title';
$field_default_value = trans('widget::global.title_default');
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help);

/*
 |--------------------------------------------------------------------------
 | Content
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.content');
$field_name = 'content';
$field_default_value = trans('widget::global.content_default');
$field_rows = 6;
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

?>
<script>
if (! $('html').hasClass('ie8')) {
	$('#{{ $field_name }}').summernote({
		height: 100,
		tabsize: 2,
		codemirror: {
			theme: 'monokai'
		},
		toolbar: [
			['fontname', ['fontname']],
			['fontsize', ['fontsize']],
			['style', ['bold', 'italic', 'underline', 'clear']],
			['color', ['color']],
			['para', ['ul', 'ol']],
			//['height', ['height']], // line height
			['insert', ['picture', 'link']],
			['codeview', ['codeview']]
		]
	});
}
</script>
<?php

echo Former::textarea()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help);

/*
 |--------------------------------------------------------------------------
 | Form elements
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.form');
$field_name = 'form';
$field_default_value = trans('widget::global.form_default');
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

$field_value = json_decode($field_value);

$i = 0;
$js = '';
if($field_value != NULL)
{
	foreach($field_value->name as $row)
	{
		$js .= "var data = {};
	data.name = '" . $field_value->name[$i] . "';
	data.type = '" . $field_value->type[$i] . "';
	data.options = '" . str_replace(chr(11), '\\n', str_replace(chr(13), '\\n', str_replace(chr(10), '\\n', $field_value->options[$i]))) . "';
	data.required = '" . (boolean)$field_value->required[$i] . "';";

		$js .= "addRepeaterRow('" . $field_name . "', data);";
		$i++;
	}
}

?>
<input type="hidden" name="{{ $field_name }}" value="">
<label class="control-label">{{ trans('widget::global.form_elements') }}</label>
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
		data.name = '';
		data.type = 'text';
		data.options = '';
		data.required = false;
	}

    var html = template({
        i: i++,
        name: data.name,
        type: data.type,
        options: data.options,
        required: data.required,
    });
    $('#' + field + '-holder tbody').append(html);

    formCheckType('#type' + (i - 1));

    $('[data-toggle~=option-popover]').popover(
    {
        container: 'body',
        html: true,
        template: '<div class="popover" role="tooltip" style="width:340px"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    });

    $('[data-class]').switcher(
    {
        theme: 'square',
        on_state_content: '<span class="fa fa-check"></span>',
        off_state_content: '<span class="fa fa-times"></span>'
    });
}

$('#{{ $field_name }}-holder').on('change', '.form-element-type', function() {
    formCheckType(this);
});

$('#{{ $field_name }}-holder').on('click', '.form-options', function() {
    var i = $(this).attr('data-i');
    $('#popover_options' + i).val($('#options' + i).val());
});

$('body').on('click', '.form-opts-save', function() {
    var i = $(this).attr('data-i');
    $('#options' + i).val($('#popover_options' + i).val());

    $('[data-toggle="option-popover"]').each(function () {
        $(this).popover('hide');
    });
});

$('body').on('click', '.form-opts-cancel', function() {
    $('[data-toggle="option-popover"]').each(function () {
        $(this).popover('hide');
    });
});

/* Close popovers when clicking outside */
$('body').on('click', function(e) {
    $('[data-toggle="option-popover"]').each(function () {
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
});

function formCheckType(el)
{
    var val = $(el).val();
    var opts = $(el).parent().next('td').find('.form-options');

    switch(val)
    {
        case 'dropdown':
        case 'options':
        case 'multiplechoice':
            $(opts).attr('disabled', false);
        break;
        default:
            $(opts).attr('disabled', true);
    }
}
</script>
<script id="{{ $field_name }}-row" type="text/x-handlebars-template">
<tr id="row@{{i}}">
    <td style="width:54px"><div class="btn btn-text handle" style="cursor:ns-resize"><i class="fa fa-bars"></i></div></td>
    <td>
        <input type="text" name="{{ $field_name }}[name][]" id="field_name@{{i}}" value="@{{name}}" class="form-control">
	</td>
    <td>
		<select name="{{ $field_name }}[type][]" id="type@{{i}}" class="form-control form-element-type">
            @{{#select type}}
			<option value="text">{{ trans('widget::global.text') }}</option>
			<option value="textarea">{{ trans('widget::global.textarea') }}</option>
			<option value="email">{{ trans('widget::global.email') }}</option>
			<option value="number">{{ trans('widget::global.number') }}</option>
			<option value="tel">{{ trans('widget::global.tel') }}</option>
			<option value="date">{{ trans('widget::global.date') }}</option>
			<option value="time">{{ trans('widget::global.time') }}</option>
			<option value="datetime">{{ trans('widget::global.datetime') }}</option>
			<option value="checkbox">{{ trans('widget::global.checkbox') }}</option>
			<option value="dropdown">{{ trans('widget::global.dropdown') }}</option>
			<option value="options">{{ trans('widget::global.options') }}</option>
			<option value="multiplechoice">{{ trans('widget::global.multiplechoice') }}</option>
            @{{/select}}
		</select>
    </td>
    <td class="text-center">
		<button type="button" data-i="@{{i}}" class="btn btn-info form-options" data-toggle="option-popover" data-content="{{ trans('widget::global.options_info') }}<textarea class='form-control form-opts-textarea' id='popover_options@{{i}}'>@{{options}}</textarea> <button type='button' class='btn btn-primary form-opts-save' data-i='@{{i}}'>{{ trans('widget::global.save') }}</button> <button type='button' class='btn btn-default form-opts-cancel'>{{ trans('widget::global.cancel') }}</button>"><i class="fa fa-wrench"></i></button>
        <textarea name="{{ $field_name }}[options][]" class="form-control" id="options@{{i}}" style="display:none">@{{options}}</textarea>
    </td>
    <td>
		<input data-class="switcher-success" novalidate="true" id="required@{{i}}" type="checkbox" name="{{ $field_name }}[required][]" value="1" @{{#if required}}checked@{{/if}}>
    </td>
    <td style="width:54px"><button class="btn btn-danger" type="button" onclick="$(this).closest('tr').remove();"><i class="fa fa-remove"></i></button></td>
</tr>
</script>
<style type="text/css">
.form-opts-textarea {
    margin:5px 0;
    height:68px !important;
}
</style>
<br>
<?php

/*
 |--------------------------------------------------------------------------
 | Submission button
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.submission_button');
$field_name = 'submission_button';
$field_default_value = trans('widget::global.submit');
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help);

/*
 |--------------------------------------------------------------------------
 | Recipient(s)
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.recipients');
$field_name = 'recipients';
$field_default_value = '[""]';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';
$field_value = json_decode($field_value);
$field_value = implode(',', $field_value);

echo '<div class="select2-primary">';
echo Former::text()
    ->name($field_name . '[]')
    ->forceValue($field_value)
    ->id($field_name)
	->label($field_label)
    ->help($field_help)
    ->prepend('<i class="fa fa-envelope-o"></i>');
echo '</div>';

?>
<script>
$('#{{ $field_name }}').select2(
{
	tags: ['{{ \Auth::user()->email }}'],
	tokenSeparators: [',', ';', ' ']
}); 
</script>
<?php

/*
 |--------------------------------------------------------------------------
 | Success message
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.success_message');
$field_name = 'success_message';
$field_default_value = trans('widget::global.success_message_default');
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::textarea()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help)
    ->style("height:72px");

?>