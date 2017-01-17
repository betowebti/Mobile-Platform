<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">
			<button class="close" type="button" data-dismiss="modal">×</button>
			<?php echo trans('global.view_details') . ' <span class="text-muted"> - ' . $created_at . '</span>' ?>
        </div>
		<div class="modal-body">
			<div class="container-fluid" id="record-view">
<?php
$data = json_decode($data['value']);

echo '<table class="table table-bordered table-hover table-striped" id="dataTable">';
foreach($data as $key => $val)
{
	echo '<tr>';
	if(isset($val->title) && isset($val->deal))
	{
		echo '<td>' . $val->title . '</td>';
		echo '<td>' . $val->deal . '</td>';
	}

	if(isset($val->name) && isset($val->val))
	{
		echo '<td>' . $val->name . '</td>';
		echo '<td>' . $val->val . '</td>';
	}
	echo '</tr>';
}
echo '</table>';
?>
			</div>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" type="button"><?php echo Lang::get('global.close') ?></button>
			<button class="btn btn-primary" type="button" onclick="printData()"><i class="fa fa-print"></i></button>
		</div>
	</div>
</div>
<script>
function printData()
{
   var divToPrint=document.getElementById("dataTable");
   newWin = window.open("");
   newWin.document.write(divToPrint.outerHTML);
   newWin.document.close();
   newWin.print();
   newWin.close();
}
</script>