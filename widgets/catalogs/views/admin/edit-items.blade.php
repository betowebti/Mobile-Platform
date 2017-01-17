<?php
	/*
	 |--------------------------------------------------------------------------
	 | Items
	 |--------------------------------------------------------------------------
	 */
	
	$catalog_id = \Input::get('catalog_id', 0);
	$category_id = \Input::get('category_id', 0);
	
	$field_name = 'items';
	$field_value = \Input::get('data', '');
	
	$rows = json_decode($field_value);
	
	$i = 0;
	$js = '';
	
	if ($field_value != '')
	{
		foreach($rows as $item_row)
		{
			$json_string = 'var data = {};';
	
			foreach ($structure['item']['options'] as $name => $option)
			{
				$default = (isset($option['default'])) ? $option['default'] : '';
				if (! isset($item_row->{$name})) $item_row->{$name} = $default;
		
				$json_string .= "data." . $name . " = '" . str_replace(PHP_EOL, "\\r\\n", str_replace("'", "\'", str_replace('\\', '\\\\', $item_row->{$name}))) . "';";
			}
	
			$js .= $json_string . "data.i = '" . $i . "';";
			$js .= "addRepeaterRowModalItem('" . $field_name . "', 'insert', data);";
			$i++;
		}
	}
	
	?>
<div class="modal-dialog" style="width:90%" id="widget-modal-items-content">
	<div class="modal-content">
		<div class="modal-header">
			<button class="close" type="button" data-dismiss="modal">×</button>
			<?php echo trans('widget::global.edit_items') ?>
		</div>
		<div class="modal-body">
			<form class="form form-items form-items-serialize">
				<table class="table table-striped table-hover" id="{{ $field_name }}-holder">
					<thead>
						<tr>
							<th style="width:43px"> </th>
							<?php foreach($structure['item']['show_in_list'] as $column) { ?>
							<th<?php if($structure['item']['options'][$column]['type'] == 'image') echo ' style="width:110px"'; ?>>{{ $structure['item']['options'][$column]['name'] }}</th>
							<?php } ?>
							<th style="width:97px;" class="text-center">{{ trans('widget::global.edit') }}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</form>
			<button type="button" class="btn btn-success btn-block btn-lg" onclick="openItemPopup({{ $category_id }})"><i class="fa fa-plus"></i> {{ trans('widget::global.add_item') }}</button>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary" type="button" id="submit-items-data">{{ trans('widget::global.update_items') }}</button>
			<button class="btn" data-dismiss="modal" type="button"><?php echo trans('global.cancel') ?></button>
		</div>
		<?php
			echo Form::token();
			
			// Use </form> instead of echo Former::close();
			?>
		</form>
	</div>
</div>
<div class="modal fade" id="ajax-modal3" data-backdrop="static" data-keyboard="true" tabindex="-1"></div>
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
	
	var $modal3 = $('#ajax-modal3');
	
	function openItemPopup(category_id, item_id)
	{
		if(typeof item_id === 'undefined') item_id = '';
		data = (item_id == '') ? '' : $('#structure_item' + item_id).val();
	
		$('body').modalmanager('loading');
		$modal3.load(app_root + '/api/v1/widget/post/catalogs/editItem', {data: data, item_id: item_id, category_id: category_id, sl: "{{ $sl }}"}, function()
		{
			$modal3.modal();
		});
	}
	
	function addRepeaterRowModalItem(field, action, data)
	{
		var source = $('#' + field + '-row').html();
		var template = Handlebars.compile(source);
	
		if(typeof data === 'undefined')
		{
			data = {};
	<?php 
		foreach($structure['item']['options'] as $column => $option) { 
		?>
			data.<?php echo $column ?> = '<?php echo (isset($option['default'])) ? $option['default'] : '';?>';
	<?php
		}
		
		?>
		}
	
	<?php 
		foreach($structure['item']['options'] as $column => $option)
		{
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
				structure_item: JSON.stringify(data)
			});
	
			$('#' + field + '-holder  #item_row' + data.i).replaceWith(html);
		}
		else
		{
			var html = template({
				i: i++,
				data: data,
				structure_item: JSON.stringify(data)
			});
	
			$('#' + field + '-holder tbody').append(html);
		}
	
		$('[data-toggle~=tooltip]').tooltip(
		{
			container: 'body'
		});
	}
</script>
<script id="{{ $field_name }}-row" type="text/x-handlebars-template">
	<tr id="item_row@{{i}}">
	    <td><div class="btn btn-text btn-xs handle" style="cursor:ns-resize"><i class="fa fa-bars"></i></div></td>
	<?php 
		foreach($structure['item']['options'] as $column => $option)
		{ 
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
	        <textarea name="{{ $field_name }}[]" class="form-control" id="structure_item@{{i}}" style="display:none">@{{structure_item}}</textarea>
			<button class="btn btn-info btn-xs" type="button" onclick="openItemPopup({{ $category_id }}, '@{{i}}')" data-toggle="tooltip" title="<?php echo $structure['item']['name_edit'] ?>"><i class="fa fa-pencil"></i></button>
	    	<button class="btn btn-danger btn-xs" type="button" onclick="$(this).closest('tr').remove();"><i class="fa fa-remove"></i></button>
		</td>
	</tr>
</script>
<?php
	/*
	 |--------------------------------------------------------------------------
	 | General CSS + JavaScript
	 |--------------------------------------------------------------------------
	 */
	
	?>
<style type="text/css">
</style>
<script>
	$('#submit-items-data').on('click', function() {
	
		var json = [];
	
		$('.form-items-serialize table > tbody > tr').each(function() {
			var row = '.form-items-serialize #' + $(this).attr('id') + ' textarea';
			row = $(row).val();
			json.push(JSON.parse(row));
		});
	
		$('#structure_items{{ $category_id }}').val(JSON.stringify(json));
	
		$modal2.modal('hide');
	});
	
	bsTooltipsPopovers();
	
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