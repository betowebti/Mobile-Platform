<?php
	/*
	 |--------------------------------------------------------------------------
	 | Categories
	 |--------------------------------------------------------------------------
	 */
	
	$catalog_id = \Input::get('i', '');
	
	$field_name = 'categories';
	$field_value = \Input::get('data', '');
	
	$rows = json_decode($field_value);
	$rows = (isset($rows->structure_categories)) ? $rows->structure_categories : new stdClass;
	
	$i = 0;
	$js = '';
	
	if ($field_value != '')
	{
		foreach($rows as $row)
		{
	
			$json_string = 'var data = {};';
	
			foreach ($structure['category']['options'] as $name => $option)
			{
				$default = (isset($option['default'])) ? $option['default'] : '';
				if (! isset($row->{$name})) $row->{$name} = $default;
		
				$json_string .= "data." . $name . " = '" . str_replace(PHP_EOL, "\\r\\n", str_replace("'", "\'", str_replace('\\', '\\\\', $row->{$name}))) . "';";
			}
	
			$structure_items = (isset($row->structure_items)) ? $row->structure_items : [];
			$json_string .= "data.structure_items = '" . str_replace(PHP_EOL, "\\r\\n", str_replace("'", "\'", str_replace('\\', '\\\\', json_encode($structure_items)))) . "';";
	
			$js .= $json_string . "data.i = '" . $i . "';";
			$js .= "addRepeaterRowModal('" . $field_name . "', 'insert', data);";
		}
	}
	
	?>
<div class="modal-dialog" style="width:90%" id="widget-modal-content">
	<div class="modal-content">
		<div class="modal-header">
			<button class="close" type="button" data-dismiss="modal">×</button>
			<?php echo trans('widget::global.edit_categories') ?>
		</div>
		<div class="modal-body">
			<form class="form form-categories-modal form-categories-modal-serialize">
				<table class="table table-striped table-hover" id="{{ $field_name }}-holder">
					<thead>
						<tr>
							<th style="width:43px"> </th>
							<?php foreach($structure['category']['options'] as $column => $option) { ?>
							<?php if($option['type'] == 'image') {  ?>
							<th style="width:70px">{{ $option['name'] }}</th>
							<th style="width:110px"> </th>
							<?php } else { ?>
							<th>{{ $option['name'] }}</th>
							<?php } ?>
							<?php } ?>
							<th style="width:97px;" class="text-center">{{ trans('widget::global.edit') }}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</form>
			<button type="button" class="btn btn-success btn-block btn-lg" onclick="addRepeaterRowModal(('{{ $field_name }}'))"><i class="fa fa-plus"></i> {{ trans('widget::global.add_category') }}</button>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary" type="button" id="submit-categories-data">{{ trans('widget::global.update_categories') }}</button>
			<button class="btn" data-dismiss="modal" type="button"><?php echo trans('global.cancel') ?></button>
		</div>
		<?php
			echo Form::token();
			
			// Use </form> instead of echo Former::close();
			?>
		</form>
	</div>
</div>
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
	
	function addRepeaterRowModal(field, action, data)
	{
		var source = $('#' + field + '-row').html();
		var template = Handlebars.compile(source);
	
		if(typeof data === 'undefined')
		{
			data = {};
	<?php 
		foreach($structure['category']['options'] as $column => $option) { 
		?>
			data.<?php echo $column ?> = '<?php echo (isset($option['default'])) ? $option['default'] : '';?>';
	<?php
		}
		
		?>
		}
	
	<?php 
		foreach($structure['category']['options'] as $column => $option)
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
		if (typeof data.structure_items === 'undefined') data.structure_items = [];
	
		if(action == 'update')
		{
			var html = template({
				i: data.i,
				data: data,
				category: JSON.stringify(data),
				structure_items: data.structure_items
			});
	
			$('#' + field + '-holder  #cat_row' + data.i).replaceWith(html);
		}
		else
		{
			var html = template({
				i: i++,
				data: data,
				category: JSON.stringify(data),
				structure_items: data.structure_items
			});
	
			$('#' + field + '-holder tbody').append(html);
		}
	
		$('[data-toggle~=tooltip]').tooltip();
	
		$('[data-class]').switcher(
		{
			theme: 'square',
			on_state_content: '<span class="fa fa-check"></span>',
			off_state_content: '<span class="fa fa-times"></span>'
		});
	}
</script>
<script id="{{ $field_name }}-row" type="text/x-handlebars-template">
	<tr id="cat_row@{{i}}">
	    <td><div class="btn btn-text btn-xs handle" style="cursor:ns-resize"><i class="fa fa-bars"></i></div></td>
	<?php 
		foreach($structure['category']['options'] as $column => $option)
		{ 
			if ($option['type'] == 'text')
			{
		?>
		<td><input type="text" name="<?php echo $column ?>" id="<?php echo $column ?>@{{i}}" value="{{data.<?php echo $column ?>}}" class="form-control input-sm" style="height:23px;padding:0 5px"></td>
	<?php
		}
		elseif ($option['type'] == 'textarea')
		{
		?>
		<td><textarea name="<?php echo $column ?>" id="<?php echo $column ?>@{{i}}" class="form-control input-sm" rows="2" style="padding:0 5px">{{data.<?php echo $column ?>}}</textarea></td>
	<?php
		}
		elseif ($option['type'] == 'image')
		{
		?>
		<td style="width:100px">
			<input type="hidden" name="<?php echo $column ?>" id="<?php echo $column ?>@{{i}}" value="{{data.<?php echo $column ?>}}" class="form-control">
			<div class="btn-group" role="group" style="width:76px">
				<button type="button" class="btn btn-primary btn-xs img-browse" data-id="<?php echo $column ?>@{{i}}" data-toggle="tooltip" title="{{ trans('global.select_image') }}"><i class="fa fa-picture-o"></i></button>
				<button type="button" class="btn btn-danger btn-xs img-remove" data-id="<?php echo $column ?>@{{i}}" data-toggle="tooltip" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
			</div>
		</td>
		<td>
			<div id="<?php echo $column ?>@{{i}}-image" class="img-thumb" data-w="100" data-h="100">{{#if data.<?php echo $column ?>_thumb}}<img src="{{data.<?php echo $column ?>_thumb}}" class="thumbnail" style="max-width:100%; margin:0">@{{/if}}</div>
		</td>
	<?php
		}
		elseif ($option['type'] == 'boolean')
		{
		?>
		<td style="width:32px;text-align:center;line-height:10px">
			<input data-class="switcher-success" novalidate="true" id="<?php echo $column ?>@{{i}}" type="checkbox" name="<?php echo $column ?>" value="1" {{#if data.<?php echo $column ?>}}checked@{{/if}}>
		</td>
	<?php
		}
		else
		{
		?>
		<td><input type="text" name="<?php echo $column ?>" id="<?php echo $column ?>@{{i}}" value="{{data.<?php echo $column ?>}}" class="form-control input-sm" style="height:23px;padding:0 5px"></td>
	<?php
		}
		} 
		?>
	    <td class="text-center">
	<?php
		if (isset($structure['item']))
		{
		?>
		<button class="btn btn-info btn-xs" type="button" onclick="openItemsPopup('@{{i}}')" data-toggle="tooltip" title="<?php echo $structure['item']['name_edit'] ?>"><i class="fa fa-pencil"></i></button>
	<?php
		}
		?>
	        <textarea name="{{ $field_name }}[]" class="structure_items" id="structure_items@{{i}}" style="display:none">@{{structure_items}}</textarea>
	    	<button class="btn btn-danger btn-xs" type="button" onclick="$(this).closest('tr').remove();" data-toggle="tooltip" title="{{ trans('widget::global.delete_row') }}"><i class="fa fa-remove"></i></button>
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
	.switcher {
	margin-top:0 !important;
	}
</style>
<script>
	var $modal2 = $('#ajax-modal2');
	
	function openItemsPopup(category_id)
	{
		var data = $('#structure_items' + category_id).val();
	
		$('body').modalmanager('loading');
		$modal2.load(app_root + '/api/v1/widget/post/catalogs/editItems', {data: data, i: i, category_id: category_id,  catalog_id: {{ \Input::get('i', 0) }}, sl: "{{ $sl }}"}, function()
		{
			$modal2.modal();
			onModalLoad();
		});
	}
	
	$('#submit-categories-data').on('click', function() {
	
	    // Set non-checked checkboxes to value="0"
	    var cb = $('.form-categories-modal-serialize')[0].getElementsByTagName('input');
	
	    for(var i=0;i<cb.length;i++){ 
	        if(cb[i].type=='checkbox' && !cb[i].checked)
	        {
	           cb[i].value = 0;
	           cb[i].checked = true;
	        }
	    }
	
		var json = [];
	
		$('.form-categories-modal-serialize table > tbody > tr').each(function() {
			var row = '.form-categories-modal-serialize #' + $(this).attr('id') + ' .form-control, .form-categories-modal-serialize #' + $(this).attr('id') + ' [data-class="switcher-success"]';
			row = $(row).serializeObject();
	
			var items = '.form-categories-modal-serialize #' + $(this).attr('id') + ' textarea.structure_items';
			items = $(items).val();
			if (items == '') items = '[]';
	
			row.structure_items = JSON.parse(items);
	
			json.push(row);
		});
	
		var data = '{{ str_replace("'", "\'", str_replace("\\", "\\\\", $field_value)) }}';
	
		data = JSON.parse(data);
	
		/* Add JSON */
		data.structure_categories = json;
	
		data = JSON.stringify(data);
	
		data.replace(/(['"\\])/g, '\\$1');
	
		$('#catalog{{ $catalog_id }}').val(data);
	
		$modal.modal('hide');
	});
	
	$('[data-class]').switcher(
	{
		theme: 'square',
		on_state_content: '<span class="fa fa-check"></span>',
		off_state_content: '<span class="fa fa-times"></span>'
	});
	
	select2();
	
	$('[data-toggle~=tooltip]').tooltip();
	
	$('.date-picker').datepicker({
		format: 'yyyy-mm-dd'
	});
	
	$('.time-picker').timepicker({
		minuteStep: 5,
		showSeconds: false,
		showMeridian: false,
		showInputs: false,
		orientation: $('body').hasClass('right-to-left') ? { x: 'right', y: 'auto'} : { x: 'auto', y: 'auto'}
	});
	
	$('#widget-modal-content').on('click', '.file-browse,.img-browse', function(event)
	{
	  if(event.handled !== true)
	  {
		// trigger the reveal modal with elfinder inside
		$.colorbox(
		{
			href: elfinderUrl + $(this).attr('data-id') + '/processWidgetModalFile',
			fastIframe: true,
			iframe: true,
			width: '70%',
			height: '80%'
		});
	    event.handled = true;
	  }
	  return false;
	
	});
	
	$('#widget-modal-content').on('click', '.img-remove', function(event)
	{
	  if(event.handled !== true)
	  {
		$('#' + $(this).attr('data-id') + '-image').html('');
		$('#' + $(this).attr('data-id')).val('');
	    event.handled = true;
	  }
	  return false;
	});
	
	$('#widget-modal-content').on('click', '.file-remove', function(event)
	{
	  if(event.handled !== true)
	  {
		$('#' + $(this).attr('data-id')).val('');
	
	    event.handled = true;
	  }
	  return false;
	});
	
	// Callback after elfinder selection
	window.processWidgetModalFile = function(filePath, requestingField)
	{
	    if($('#' + requestingField).attr('type') == 'text')
	    {
		    $('#' + requestingField).val(decodeURI(filePath));
	    }
	
	    if($('#' + requestingField + '-image').length)
	    {
			var w = (typeof $('#' + requestingField + '-image').attr('data-w') !== 'undefined') ? $('#' + requestingField + '-image').attr('data-w') : 120;
			var h = (typeof $('#' + requestingField + '-image').attr('data-h') !== 'undefined') ? $('#' + requestingField + '-image').attr('data-h') : 120;
			var img = decodeURI(filePath);
			var thumb = '{{ url('/api/v1/thumb/nail?') }}w=' + w + '&h=' + h + '&img=' + filePath;

			$('#' + requestingField + '-image').addClass('bg-loading');

			$('<img/>').attr('src', decodeURI(thumb)).load(function() {
				$(this).remove();
				$('#' + requestingField + '-image').html('<img src="' + thumb + '" class="thumbnail" style="max-width:100%; margin:0">');
				$('#' + requestingField + '-image').removeClass('bg-loading');
			});

	        $('#' + requestingField).val(img);
	    }
	}
	
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