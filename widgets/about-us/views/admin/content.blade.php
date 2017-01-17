<?php
/*
 |--------------------------------------------------------------------------
 | Image
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.image');
$field_name = 'image';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

?>
<div class="row">
	<div class="col-md-8">
		<div class="form-group">
			<div>
				<input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $field_value }}">
				<label class="control-label">{{ $field_label }}</label>
			</div>

			<div class="btn-group" role="group" style="margin-bottom:10px">
				<button type="button" class="btn btn-info img-browse" data-id="{{ $field_name }}"><i class="fa fa-picture-o"></i> {{ trans('global.select_image') }}</button>
				<button type="button" class="btn btn-danger img-remove" data-id="{{ $field_name }}" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
			</div>

			<div id="{{ $field_name }}-image" data-w="240" data-h="120">
<?php
if($field_value != '')
{
    echo '<img src="' . url('/api/v1/thumb/nail?w=240&h=120&img=' . $field_value) . '" class="thumbnail widget-thumb">';
}
?>
			</div>
		</div>
	</div>
<?php

/*
 |--------------------------------------------------------------------------
 | Checkbox
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.box_image');
$field_name = 'box_image';
$field_default_value = 1; // checked 1/0
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo '<div class="col-md-4">';

echo Former::checkbox()
    ->name($field_name)
	->label($field_label)
    ->check((boolean)$field_value)
    ->dataClass('switcher-success')
	->novalidate();

echo '</div>';
echo '</div>';

/*
 |--------------------------------------------------------------------------
 | Text
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.title');
$field_name = 'title';
$field_default_value = trans('widget::global.default_title');
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help);

/*
 |--------------------------------------------------------------------------
 | WYSIWIG
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.content');
$field_name = 'content';
$field_default_value = trans('widget::global.default_content');
$field_rows = 6;
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

?>
<script>
if (! $('html').hasClass('ie8')) {
	$('#{{ $field_name }}').summernote({
		height: 160,
		tabsize: 2,
		codemirror: {
			theme: 'monokai'
		},
		toolbar: [
			['fontname', ['fontname']],
			['fontsize', ['fontsize']],
			['style', ['bold', 'italic', 'underline', 'clear']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			//['height', ['height']], // line height
			['insert', ['picture', 'link']],
			['table', ['table']],
			['codeview', ['codeview']]
		]
	});
}
</script>
<?php

echo Former::textarea()
    ->name($field_name)
    ->class('summernote')
    ->forceValue($field_value)
	->label($field_label)
    ->help($field_help)
	->rows($field_rows);

/*
 |--------------------------------------------------------------------------
 | Repeater
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.text');
$field_name = 'list';
$field_default_value = trans('widget::global.list_default');
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

$field_value = json_decode($field_value);

$i = 0;
$js = '';
if($field_value != NULL)
{
	foreach($field_value->icon as $row)
	{
		$js .= "var data = {};
	data.icon = '" . $field_value->icon[$i] . "';
	data.url = '" . $field_value->url[$i] . "';
	data.label = '" . $field_value->label[$i] . "';";

		$js .= "addRepeaterRow('" . $field_name . "', data);";
		$i++;
	}
}

?>
<input type="hidden" name="{{ $field_name }}" value="">
<table class="table table-bordered table-striped table-hover" id="{{ $field_name }}-holder">
	<thead>
		<tr>
			<th></th>
			<th>{{ trans('widget::global.link') }}</th>
			<th>{{ trans('widget::global.button_text') }}</th>
			<th></th>
		</tr>
	</thead>
    <tbody>
    </tbody>
</table>

<button type="button" class="btn btn-success btn-block btn-lg" onclick="addRepeaterRow('{{ $field_name }}')"><i class="fa fa-plus"></i> {{ trans('widget::global.add_row') }}</button>

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
		data.icon = 'ion-android-globe';
		data.url = '';
		data.label = '';
	}

    var html = template({
        i: i++, 
        icon: data.icon, 
        url: data.url, 
        label: data.label, 
        field: field
    });
    $('#' + field + '-holder tbody').append(html);

	$('.icon-picker').iconpicker({ 
		arrowClass: 'btn-danger',
		arrowPrevIconClass: 'fa fa-chevron-left',
		arrowNextIconClass: 'fa fa-chevron-right',
		cols: 5,
		rows: 5,
		iconset: $.iconset_ionicon,
		labelHeader: '{0} of {1} pages',
		labelFooter: '{0} - {1} of {2} icons',
		placement: 'bottom',
		search: true,
		searchText: 'Search',
		selectedClass: 'btn-primary',
		unselectedClass: ''
	}).on('change', function(e) { 
		$('#' + $(this).attr('data-id')).val(e.icon);
	});
}
</script>
<script id="{{ $field_name }}-row" type="text/x-handlebars-template">
<tr id="row@{{i}}">
    <td style="width:54px"><div class="btn btn-text handle" style="cursor:ns-resize"><i class="fa fa-bars"></i></div></td>
    <td>
        <input type="hidden" name="{{ $field_name }}[icon][]" id="{{ $field_name }}-icon@{{i}}" value="@{{icon}}" class="form-control">
		<div class="btn-group" role="group">

			<div class="input-group">
				<span class="input-group-btn">
					<button class="btn btn-default icon-picker iconpicker" data-icon="@{{icon}}" data-id="{{ $field_name }}-icon@{{i}}" role="iconpicker" type="button"></button>
				</span>
				<input class="form-control" placeholder="http://" value="@{{url}}" type="text" name="{{ $field_name }}[url][]">
			</div>

		</div>
	</td>
    <td style="min-width:200px">
		<input class="form-control" placeholder="{{ trans('widget::global.button_text') }}" type="text" value="@{{label}}" name="{{ $field_name }}[label][]">
    </td>
    <td style="width:54px"><button class="btn btn-danger" type="button" onclick="$(this).closest('tr').remove();"><i class="fa fa-remove"></i></button></td>
</tr>
</script> 
<br>
<?php

/*
 |--------------------------------------------------------------------------
 | Social share
 |--------------------------------------------------------------------------
 */

echo '<div class="row"><div class="col-md-8">';

$social_share_text = \Mobile\Controller\WidgetController::getData($page, 'social_share_text');
$social_share = \Mobile\Controller\WidgetController::getData($page, 'social_share', 0);

echo Former::text()
    ->name('social_share_text')
    ->forceValue($social_share_text)
	->label(trans('global.social_share_text'))
    ->placeholder($app->name . ' - ' . $page->name)
    ->prepend('<i class="fa fa-share-alt"></i>');

echo '</div><div class="col-md-4">';

echo Former::checkbox()
    ->name('social_share')
	->label(trans('global.social_share_help'))
    ->check((boolean) $social_share)
    ->dataClass('switcher-success')
	->novalidate();

echo '</div></div>';
?>