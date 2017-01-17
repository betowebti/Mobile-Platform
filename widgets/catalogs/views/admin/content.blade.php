<?php
	/*
	 |--------------------------------------------------------------------------
	 | Catalogs
	 |--------------------------------------------------------------------------
	 */
	
	$field_name = 'catalogs';
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
		->forceValue('catalogs');
	
	$i = 0;
	$js = '';
	if($field_value != NULL)
	{
		foreach($field_value as $row)
		{
			$json_string = str_replace("'", "\'", str_replace('\\', '\\\\', json_encode($row)));
	
			$js .= "var data = JSON.parse('" . $json_string . "');data.i = '" . $i . "';";
			$js .= "addRepeaterRow('" . $field_name . "', 'insert', data);";
			$i++;
		}
	}
	
	?>
<table class="table table-striped table-hover" id="{{ $field_name }}-holder">
	<thead>
		<tr>
			<th style="width:43px"> </th>
			<?php foreach($structure['catalog']['show_in_list'] as $column) { ?>
			<th<?php if($structure['catalog']['options'][$column]['type'] == 'image') echo ' style="width:110px"'; ?>>{{ $structure['catalog']['options'][$column]['name'] }}</th>
			<?php } ?>
			<th style="width:97px;" class="text-center">{{ trans('widget::global.edit') }}</th>
		</tr>
	</thead>
	<tbody>
	</tbody>
</table>
<button type="button" class="btn btn-success btn-block btn-lg" onclick="openCatalogPopup()"><i class="fa fa-plus"></i> {{ trans('widget::global.add_catalog') }}</button>
<script>
	var i = 0;
	
	$('#{{ $field_name }}-holder tbody').sortable({
		handle: '.handle',
		axis: 'y',
		placeholder: {
			element: function(currentItem) {
				return $('<tr class="el-placeholder"><td colspan="{{ $colspan }}"></td></tr>')[0];
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
	
	function openCatalogPopup(i)
	{
		var data = $('#catalog' + i).val();
		if(typeof i === 'undefined') i = '';
	
		$('body').modalmanager('loading');
		$modal.load(app_root + '/api/v1/widget/post/catalogs/editCatalog', {data: data, i: i, sl: "{{ $sl }}"}, function()
		{
			$modal.modal();
		});
	}
	
	function openCategoryPopup(i)
	{
		var data = $('#catalog' + i).val();
		if(typeof i === 'undefined') i = '';
	
		$('body').modalmanager('loading');
		$modal.load(app_root + '/api/v1/widget/post/catalogs/editCategories', {data: data, i: i, sl: "{{ $sl }}"}, function()
		{
			$modal.modal();
		});
	}
	
	function addRepeaterRow(field, action, data)
	{
		var source = $('#' + field + '-row').html();
		var template = Handlebars.compile(source);
	
	<?php 
		foreach($structure['catalog']['show_in_list'] as $column) { 
		
			$option = $structure['catalog']['options'][$column];
		
			if ($option['type'] == 'image')
			{
		?>
		data.<?php echo $column ?>_thumb = (data.<?php echo $column ?> != '') ? app_root + '/api/v1/thumb/nail?w=420&h=310&img=' + data.<?php echo $column ?> : '';
	<?php
		}
		if ($option['type'] == 'boolean')
		{
		?>
		data.<?php echo $column ?> = (data.<?php echo $column ?> == '1') ? 1 : 0;
	<?php
		}
		} 
		?>
	
		if(action == 'update')
		{
			var html = template({
				i: data.i,
				data: data,
				catalog: JSON.stringify(data)
			});
	
			$('#' + field + '-holder  #row' + data.i).replaceWith(html);
		}
		else
		{
			var html = template({
				i: i++,
				data: data,
				catalog: JSON.stringify(data)
			});
	
			$('#' + field + '-holder tbody').append(html);
		}
	}
</script>
<script id="{{ $field_name }}-row" type="text/x-handlebars-template">
	<tr id="row@{{i}}">
	    <td><div class="btn btn-text btn-xs handle" style="cursor:ns-resize"><i class="fa fa-bars"></i></div></td>
	<?php 
		foreach($structure['catalog']['show_in_list'] as $column) { 
		
			$option = $structure['catalog']['options'][$column];
		
			if ($option['type'] == 'image')
			{
		?>
		<td style="width:100px"><img src="{{data.<?php echo $column ?>_thumb}}" {{#if data.<?php echo $column ?>}}class="thumbnail" style="height:100px;margin-bottom:0"@{{/if}}></td>
	<?php
		}
		elseif ($option['type'] == 'boolean')
		{
		?>
		<td style="width:24px;text-align:center;line-height:22px">{{#if data.<?php echo $column ?>}}<i class="fa fa-check"></i>@{{else}}<i class="fa fa-times"></i>@{{/if}}</td>
	<?php
		}
		else
		{
		?>
		<td style="line-height:22px">{{data.<?php echo $column ?>}}</td>
	<?php
		}
		} 
		?>
	    <td class="text-center">
	
	<div class="btn-group">
	 <button type="button" class="btn btn-info btn-xs form-options dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
		<i class="fa fa-pencil"></i>
		<span class="caret"></span>
		<span class="sr-only">Toggle Dropdown</span>
	 </button>
	 <ul class="dropdown-menu">
	   <li><a href="javascript:void(0);" onclick="openCatalogPopup('@{{i}}')">{{ trans('widget::global.edit_catalog') }}</a></li>
	<?php
		if (isset($structure['category']))
		{
		?>
	   <li><a href="javascript:void(0);" onclick="openCategoryPopup('@{{i}}')"><?php echo $structure['category']['name_edit'] ?></a></li>
	<?php
		}
		?>
	 </ul>
	</div>
	        <textarea name="{{ $field_name }}[]" class="form-control" id="catalog@{{i}}" style="display:none">@{{catalog}}</textarea>
	
	    	<button class="btn btn-danger btn-xs" type="button" onclick="$(this).closest('tr').remove();"><i class="fa fa-remove"></i></button>
		</td>
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