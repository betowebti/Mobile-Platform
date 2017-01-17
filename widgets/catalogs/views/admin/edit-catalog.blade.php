<div class="modal-dialog" style="width:770px">
	<div class="modal-content">
		<div class="modal-header">
			<button class="close" type="button" data-dismiss="modal">×</button>
			<?php echo trans('widget::global.edit_catalog') ?>
		</div>
		<?php
			$action = 'insert';
			$data = \Input::get('data', '');
			
			if($data != '')
			{
				$action = 'update';
				$row = json_decode($data);
			
				// Defaults
				foreach($structure['options'] as $name => $option)
				{
					if (! isset($row->{$name}) && isset($option['default'])) $row->{$name} = $option['default'];
				}
			}
			else
			{
				// Defaults
				$row = new stdClass;
			
				foreach($structure['options'] as $name => $option)
				{
					$row->{$name} = (isset($option['default'])) ? $option['default'] : '';
				}
			}
			
			Former::setOption('push_checkboxes', false);
			
			echo Former::vertical_open()
				->class('form form-catalog-modal form-catalog-serialize')
				->method('POST');
			
			?>
		<div class="modal-body">
			<?php
				foreach($structure['options'] as $name => $option)
				{
				
					if ($option['type'] == 'image')
					{
						$display = ($row->{$name} == '') ? 'display:none': '';
						$filled = ($row->{$name} == '') ? '': ' filled';
						$bg = ($row->{$name} == '') ? '': 'background-image:url(\'' . $row->{$name}  . '\');';
				
						$input = '';
						$input .= '<label class="control-label">' . $option['name'] . '</label>';
						$input .= '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $row->{$name} . '" class="form-control">';
						$input .= '<div>';
						$input .= '<button type="button" class="btn btn-danger btn-xs img-remove" data-id="' . $name . '" title="' . trans('global.remove_image') . '" data-toggle="tooltip" title="' . trans('global.select_image') . '" style="width:20px;padding:2px 0;margin:0 0 0 10px;' . $display . '"><i class="fa fa-remove"></i></button>';
						$input .= '<a href="javascript:void(0)" class="img thumbnail card-image img-browse' . $filled . '" id="image-' . $name . '" data-id="' . $name . '" title="' . trans('global.select_image') . '" data-w="' . $option['thumb']['w'] . '" data-h="' . $option['thumb']['h'] . '" style="' . $bg . 'margin:1px 0 20px 0">';
						$input .= '<i class="fa fa-plus-circle"></i>';
						$input .= '<div>';
						$input .= $option['thumb']['w'] . 'x' . $option['thumb']['h'];
						if (isset($option['help']))	$input .= '<br>' . $option['help'];
						$input .= '</div>';
						$input .= '</a>';
						$input .= '</div>';
						$input .= '<br style="clear:both">';
				
						echo $input;
					}
				
					/* ------------------------------------------------------------------------------------------ */
				
					if ($option['type'] == 'text')
					{
						$input = Former::text()
							->name($name)
							->forceValue($row->{$name})
							->label($option['name']);
				
						if (isset($option['required']) && $option['required'])
						{
							$input->dataFvNotempty()
								->dataFvNotemptyMessage(trans('global.please_enter_a_value'));
						}
				
						if (isset($option['help']))
						{
							$input->help($option['help']);
						}
				
						echo $input;
					}
				
					/* ------------------------------------------------------------------------------------------ */
				
					if ($option['type'] == 'textarea')
					{
						$rows = (isset($option['rows'])) ? $option['rows'] : 4;
				
						$input = Former::textarea()
							->name($name)
							->rows($rows)
							->forceValue($row->{$name})
							->label($option['name']);
				
						if (isset($option['required']) && $option['required'])
						{
							$input->dataFvNotempty()
								->dataFvNotemptyMessage(trans('global.please_enter_a_value'));
						}
				
						if (isset($option['help']))
						{
							$input->help($option['help']);
						}
				
						echo $input;
					}
				
					/* ------------------------------------------------------------------------------------------ */
				
					if ($option['type'] == 'boolean')
					{
						$input = Former::checkbox()
							->name($name)
							->value('1')
							->label($option['name'])
							->check((boolean) $row->{$name})
							->dataClass('switcher-success')
							->novalidate();
				
						if (isset($option['help']))
						{
							$input->help($option['help']);
						}
				
						echo $input;
					}
				
				}
				?>
		</div>
		<div class="modal-footer">
			<button class="btn btn-primary" type="submit"><?php echo ($action == 'update') ? trans('widget::global.update_catalog') : trans('widget::global.add_catalog'); ?></button>
			<button class="btn" data-dismiss="modal" type="button"><?php echo Lang::get('global.cancel') ?></button>
		</div>
		<?php
			echo Form::token();
			
			// Use </form> instead of echo Former::close();
			?>
		</form>
	</div>
</div>
<script>
	$('[data-class]').switcher(
	{
		theme: 'square',
		on_state_content: '<span class="fa fa-check"></span>',
		off_state_content: '<span class="fa fa-times"></span>'
	});
	
	$('.date-picker').datepicker({
		format: 'yyyy-mm-dd'
	}).on('changeDate', function(ev) {
		$('.form-catalog-serialize').data('formValidation').resetForm();
	});
	
	$('[data-toggle~=tooltip]').tooltip();
	
	$('.img-browse').on('click', function()
	{
		// trigger the reveal modal with elfinder inside
		$.colorbox(
		{
			href: elfinderUrl + $(this).attr('data-id') + '/processModalWidgetFile',
			fastIframe: true,
			iframe: true,
			width: '70%',
			height: '80%'
		});
	});
	
	$('.img-remove').on('click', function()
	{
		$('#' + $(this).attr('data-id')).val('');
	    $('#' + $(this).attr('data-id') + '-image').css('background-image', 'none');
	    $('#' + $(this).attr('data-id') + '-image').removeClass('filled');
	    $(this).hide();
	});
	
	window.processModalWidgetFile = function(filePath, requestingField)
	{
	    if($('#' + requestingField).attr('type') == 'text')
	    {
		    $('#' + requestingField).val(decodeURI(filePath));
	    }
	
	    if($('#' + requestingField + '-image').length)
	    {
			var w = ($('#' + requestingField + '-image').attr('data-w').length) ? $('#' + requestingField + '-image').attr('data-w') : 120;
			var h = ($('#' + requestingField + '-image').attr('data-h').length) ? $('#' + requestingField + '-image').attr('data-h') : 120;
			var thumb = app_root + '/api/v1/thumb/nail?w=' + w + '&h=' + h + '&img=' + filePath;

			$('#' + requestingField + '-image').addClass('bg-loading');

			$('<img/>').attr('src', decodeURI(thumb)).load(function() {
				$(this).remove();
				$('#' + requestingField + '-image').css('background-image', 'url("' + decodeURI(thumb) + '")');
				$('#' + requestingField + '-image').removeClass('bg-loading');
			});

			$('#' + requestingField + '-image').addClass('filled');
	        $('#' + requestingField).val(decodeURI(filePath));
			$('.img-remove[data-id=' + requestingField + ']').show();
	    }
	}
	
	$('.form-catalog-modal').formValidation(
	{
		framework: 'bootstrap',
		icon: {
			valid: false,
			invalid: false,
			validating: false
		}
	}).on('success.form.fv', function(e)
	{
	    /* Set non-checked checkboxes to value="0" */
	    var cb = $('.form-catalog-modal.form-catalog-serialize')[0].getElementsByTagName('input');
	
	    for(var i=0;i<cb.length;i++){ 
	        if(cb[i].type=='checkbox' && !cb[i].checked)
	        {
	           cb[i].value = 0;
	           cb[i].checked = true;
	        }
	    }
	
		var json = $('.form-catalog-modal.form-catalog-serialize .form-control,.form-catalog-modal.form-catalog-serialize [data-class="switcher-success"]').serializeObject();
	
		json.structure_categories = JSON.parse('{{ (isset($row->structure_categories)) ? str_replace("'", "\'", str_replace("\\", "\\\\", json_encode($row->structure_categories))) : '[]'; }}');
	
		json.i = '{{ \Input::get('i', '') }}';
	
		addRepeaterRow('catalogs', '{{ $action }}', json);
	
	    $modal.modal('hide');
	
		// Prevent form submission
		e.preventDefault();
	}).on('err.form.fv', function(e)
	{
		// Prevent form submission
		e.preventDefault();
	});
	
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