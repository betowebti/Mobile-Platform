@extends('../app.layouts.partial')
@section('content')
<ul class="breadcrumb breadcrumb-page">
	<div class="breadcrumb-label text-light-gray">{{ trans('global.you_are_here') }} </div>
	<li><a href="{{ trans('global.home_crumb_url') }}">{{ trans('global.home_crumb_text') }}</a></li>
	<li>{{ trans('global.content') }}</li>
	<?php
		if($campaign === false && $app === false) {
		?>
	<li><a href="#/apps">{{ trans('global.apps') }}</a></li>
	<li class="active">{{ trans('global.widget_data') }}</li>
	<?php
		} elseif($app === false && isset($campaign->name)) {
		?>
	<li><a href="#/apps">{{ trans('global.apps') }}</a></li>
	<li><a href="#/app/widget-data">{{ trans('global.widget_data') }}</a></li>
	<li class="active">{{ $campaign->name }}</li>
	<?php
		} elseif(isset($campaign->name)) {
			$sl_campaign = \App\Core\Secure::array2string(array('campaign_id' => $campaign->id));
		?>
	<li><a href="#/apps">{{ trans('global.apps') }}</a></li>
	<li><a href="#/app/widget-data">{{ trans('global.widget_data') }}</a></li>
	<li><a href="#/app/widget-data/{{ $sl_campaign }}">{{ $campaign->name }}</a></li>
	<li class="active">{{ $app->name }}</li>
	<?php
		}
		?>
</ul>
<?php
	$title = (isset($campaign->name)) ? $campaign->name : trans('global.widget_data');
	$title = ($app !== false) ? $app->name : $title;
	?>
<div class="page-header">
	<div class="row">
		<h1 class="col-xs-12 col-sm-4 text-center text-left-sm" style="height:32px"><i class="fa fa-plug page-header-icon"></i> {{ $title }}</h1>
		<div class="col-xs-12 col-sm-8">
			<div class="row">
				<hr class="visible-xs no-grid-gutter-h">
				<?php
					if(count($apps) > 0 && $stats_found !== false)
					{
					    $filter_title = (isset($campaign->name)) ? $campaign->name : trans('global.filter');
						$filter_title = ($app !== false) ? $app->name : $filter_title;
					?>
				<div class="pull-right col-xs-12 col-sm-auto">
					<div class="btn-group" style="width:100%;">
						<button class="btn btn-primary btn-labeled dropdown-toggle" style="width:100%;" type="button" data-toggle="dropdown"><span class="btn-label icon fa fa-filter"></span> {{ $filter_title }} &nbsp; <span class="fa fa-caret-down"></span></button>
						<ul class="dropdown-menu dropdown-menu-right" role="menu">
							<li><a href="#/app/widget-data/{{ $date_start }}/{{ $date_end }}/" tabindex="-1">{{ trans('global.remove_filter') }}</a></li>
							<li class="divider"></li>
							<?php
								$campaign_name_old = '';
								foreach($apps as $app_select)
								{
									if($campaign_name_old != $app_select->campaign_name)
									{
										$sl_campaign = \App\Core\Secure::array2string(array('campaign_id' => $app_select->campaign_id));
										$class = ($app === false && isset($campaign->id) && $campaign->id == $app_select->campaign_id) ? ' active' : '';
										echo '<li class="nav-header nav-link' . $class . '"><a href="#/app/widget-data/' . $sl_campaign . '">' . $app_select->campaign_name . '</a></li>';
									}
								
									$campaign_name_old = $app_select->campaign_name;
								
								    $sl_app = \App\Core\Secure::array2string(array('app_id' => $app_select->id));
								    $class = (isset($app->id) && $app->id == $app_select->id) ? 'active': '';
								?>
							<li class="{{ $class }}"><a href="#/app/widget-data/{{ $date_start }}/{{ $date_end }}/{{ $sl_app }}" tabindex="-1">{{ $app_select->name }}</a></li>
							<?php
								}
								?>
						</ul>
					</div>
				</div>
				<?php
					}
					?>
				<?php
					if($first_created !== false && $stats_found !== false)
					{
					?>
				<div class="pull-right col-xs-12 col-sm-auto">
					<div id="stats-range" class="pull-right daterange-selector btn btn-default"> 
						<i class="fa fa-calendar" style="margin:-1px 2px 0 0"></i> <span></span> <b class="caret" style="margin-left:5px"></b> 
					</div>
				</div>
				<?php
					}
					?>
			</div>
		</div>
	</div>
</div>
<?php
	if(count($apps) == 0 && $stats_found)
	{
	    // No apps
	?>
<div class="callout pull-left">{{ Lang::get('global.no_apps') }}</div>
<?php
	}
	elseif($stats_found === false)
	{
	    // No stats found for period
	?>
<div class="callout pull-left">{{ Lang::get('global.no_stats_found') }}</div>
<?php
	}
	else
	{
?>
<script>
var monthNames = ['<?php echo trans('global.january') ?>', '<?php echo trans('global.february') ?>', '<?php echo trans('global.march') ?>', '<?php echo trans('global.april') ?>', '<?php echo trans('global.may') ?>', '<?php echo trans('global.june') ?>', '<?php echo trans('global.july') ?>', '<?php echo trans('global.august') ?>', '<?php echo trans('global.september') ?>', '<?php echo trans('global.october') ?>', '<?php echo trans('global.november') ?>', '<?php echo trans('global.december') ?>'];
var monthNamesAbbr = ['<?php echo trans('global.january_abbr') ?>', '<?php echo trans('global.february_abbr') ?>', '<?php echo trans('global.march_abbr') ?>', '<?php echo trans('global.april_abbr') ?>', '<?php echo trans('global.may_abbr') ?>', '<?php echo trans('global.june_abbr') ?>', '<?php echo trans('global.july_abbr') ?>', '<?php echo trans('global.august_abbr') ?>', '<?php echo trans('global.september_abbr') ?>', '<?php echo trans('global.october_abbr') ?>', '<?php echo trans('global.november_abbr') ?>', '<?php echo trans('global.december_abbr') ?>'];
</script>

<div class="row">
<?php
// Forms
if($widget_allow['forms'])
{
    $app_pages_with_widget = \Mobile\Model\AppPage::whereIn('app_id', $apps_array_ids)
        ->where('widget', '=', 'forms')
        ->select('id')
        ->get()
        ->toArray();

    $app_pages_with_widget = array_flatten($app_pages_with_widget);

    $app_user_data = \Mobile\Model\AppUserData::whereIn('app_page_id', $app_pages_with_widget)
        ->where(\DB::raw('date(created_at)'), '>=', $date_start)
        ->where(\DB::raw('date(created_at)'), '<=', $date_end)
        ->select(\DB::raw('date(created_at) as date'), \DB::raw('count(*) as total'))
        ->groupBy(\DB::raw('date(created_at)'))
        ->get()
        ->toArray();

	$totals = NULL;
    foreach($app_user_data as $day)
    {
        $totals[$day['date']] = $day['total'];
    }

    $range = \Analytics\Controller\AppAnalyticsController::getRange($date_start, $date_end);
    $data = '';
    $total = 0;
    foreach($range as $date)
    {
        $count = (isset($totals[$date])) ? $totals[$date] : 0;
        $total += $count;
        $data .= "{ day: '" . $date . "', cnt: " . $count . " },";
    }

    $data = trim($data, ',');

?>
<div class="col-md-6">
	<script>
		Morris.Area({
			element: 'form_submissions',
			data: [ <?php echo $data ?> ],
			ykeys: ['cnt'],
			labels: ['{{ trans('global.submissions') }}'],
			ymin: 0,
			yLabelFormat: function(y){return y != Math.round(y)?'':y;},
			hideHover: 'auto',
			lineColors: ['#fff', '#ccc'],
			fillOpacity: 0.3,
			behaveLikeLine: true,
			lineWidth: 2,
			pointSize: 4,
			gridLineColor: '#cfcfcf',
			gridTextColor: '#fff',
			xkey: 'day',
			xLabels: 'day',
			xLabelFormat: function(d) {
				return monthNamesAbbr[d.getMonth()] + ' ' + d.getDate(); 
			},
			resize: true
		});
	</script>
	<div class="stat-panel">
		<div class="stat-row">
			<div class="stat-cell bg-info padding-sm">
				<div class="text-lg" style="margin-top: 5px;"><div class="sprite-xs xs-sprite-notebook26 pull-left"> </div> &nbsp; {{ trans('global.forms') }}</div>
				<div id="form_submissions" style="width: 100%; height:180px"></div>
			</div>
		</div>
		<div class="stat-row">
			<div class="stat-counters bordered no-border-t text-center">
				<div class="stat-cell col-xs-4 padding-sm no-padding-hr"> 
					<span class="text-bg"><strong><?php echo count($apps_array_ids) ?></strong></span><br>
					<span class="text-xs">{{ trans('global.apps') }}</span> 
				</div>
				<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
					<span class="text-bg"><strong><?php echo count($app_pages_with_widget) ?></strong></span><br>
					<span class="text-xs">{{ trans('global.pages') }}</span> 
				</div>
				<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
					<span class="text-bg"><strong><?php echo $total ?></strong></span><br>
					<span class="text-xs">{{ trans('global.submissions') }}</span> 
				</div>
			</div>
		</div>
	</div>

</div>
<?php } ?>
<?php
// Coupons
if($widget_allow['coupons'])
{
    $app_pages_with_widget = \Mobile\Model\AppPage::whereIn('app_id', $apps_array_ids)
        ->where('widget', '=', 'coupons')
        ->select('id')
        ->get()
        ->toArray();

    $app_pages_with_widget = array_flatten($app_pages_with_widget);

    $app_user_data = \Mobile\Model\AppUserData::whereIn('app_page_id', $app_pages_with_widget)
        ->where(\DB::raw('date(created_at)'), '>=', $date_start)
        ->where(\DB::raw('date(created_at)'), '<=', $date_end)
        ->select(\DB::raw('date(created_at) as date'), \DB::raw('count(*) as total'))
        ->groupBy(\DB::raw('date(created_at)'))
        ->get()
        ->toArray();

	$totals = NULL;
    foreach($app_user_data as $day)
    {
        $totals[$day['date']] = $day['total'];
    }

    $range = \Analytics\Controller\AppAnalyticsController::getRange($date_start, $date_end);
    $data = '';
    $total = 0;
    foreach($range as $date)
    {
        $count = (isset($totals[$date])) ? $totals[$date] : 0;
        $total += $count;
        $data .= "{ day: '" . $date . "', cnt: " . $count . " },";
    }

    $data = trim($data, ',');

?><div class="col-md-6">
	<script>
		Morris.Area({
			element: 'coupons_redeemed',
			data: [ <?php echo $data ?> ],
			ykeys: ['cnt'],
			labels: ['{{ trans('global.redeemed') }}'],
			ymin: 0,
			yLabelFormat: function(y){return y != Math.round(y)?'':y;},
			hideHover: 'auto',
			lineColors: ['#fff', '#ccc'],
			fillOpacity: 0.3,
			behaveLikeLine: true,
			lineWidth: 2,
			pointSize: 4,
			gridLineColor: '#cfcfcf',
			gridTextColor: '#fff',
			xkey: 'day',
			xLabels: 'day',
			xLabelFormat: function(d) {
				return monthNamesAbbr[d.getMonth()] + ' ' + d.getDate(); 
			},
			resize: true
		});
		
	</script>
	<div class="stat-panel">
		<div class="stat-row">
			<div class="stat-cell bg-success padding-sm">
				<div class="text-lg" style="margin-top: 5px;"><div class="sprite-xs xs-sprite-cutting pull-left"> </div> &nbsp; {{ trans('global.coupons') }}</div>
				<div id="coupons_redeemed" style="width: 100%; height:180px"></div>
			</div>
		</div>
		<div class="stat-row">
			<div class="stat-counters bordered no-border-t text-center">
				<div class="stat-cell col-xs-4 padding-sm no-padding-hr"> 
					<span class="text-bg"><strong><?php echo count($apps_array_ids) ?></strong></span><br>
					<span class="text-xs">{{ trans('global.apps') }}</span> 
				</div>
				<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
					<span class="text-bg"><strong><?php echo count($app_pages_with_widget) ?></strong></span><br>
					<span class="text-xs">{{ trans('global.pages') }}</span> 
				</div>
				<div class="stat-cell col-xs-4 padding-sm no-padding-hr">
					<span class="text-bg"><strong><?php echo $total ?></strong></span><br>
					<span class="text-xs">{{ trans('global.redeemed') }}</span> 
				</div>
			</div>
		</div>
	</div>

</div>
<?php 
}
?>
</div>
<div class="row">
	<div class="col-md-12">


<script>
stats_widget_data_table = $('#dt-table-stats-forms').DataTable({
    ajax: "{{ url('/api/v1/widget/widget-table-data?sl=' . $sl) }}",
    order: [
        [1, "desc"]
    ],
    dom: "<'row'<'col-sm-12 dt-header'<'pull-left'lr><'pull-right'f><'pull-right hidden-sm hidden-xs'T><'clearfix'>>>t<'row'<'col-sm-12 dt-footer'<'pull-left'i><'pull-right'p><'clearfix'>>>",
    processing: true,
    serverSide: true,
    stateSave: false,
    stripeClasses: [],
    lengthMenu: [
        [10, 25, 50, 75, 100, 1000000],
        [10, 25, 50, 75, 100, "{{ trans('global.all') }}"]
    ],
    columns: [
    {
        data: "name"
    },
    {
        data: "created_at"
    },
    {
        data: "sl",
        sortable: false
    }],
	fnDrawCallback: function() {
		onDataTableLoad();
	},
	columnDefs: [
		{
			render: function (data, type, row) {
				return '<span data-moment="fromNowDateTime">' + data + '</span>';
			},
			targets: 1 /* Column to re-render */
		},
		{
			render: function (data, type, row) {
				return '<div class="row-actions-wrap"><div class="text-right row-actions" data-sl="' + data + '">' + 
					'<a href="javascript:viewSingleRecord(\'' + data + '\');" class="btn btn-xs btn-primary row-btn-view" data-toggle="tooltip" title="{{ trans('global.view') }}"><i class="fa fa-search"></i></a> ' + 
					'<a href="javascript:void(0);" class="btn btn-xs btn-danger row-btn-delete" data-toggle="tooltip" title="{{ trans('global.delete') }}"><i class="fa fa-trash"></i></a>' + 
					'</div></div>';
			},
			targets: 2 /* Column to re-render */
		}
	],
    language: {
        emptyTable: "{{ trans('global.empty_table') }}",
        info: "{{ trans('global.dt_info') }}",
        infoEmpty: "",
        infoFiltered: "(filtered from _MAX_ total entries)",
        thousands: "{{ trans('i18n.thousands_sep') }}",
        lengthMenu: "{{ trans('global.show_records') }}",
        processing: '<i class="fa fa-circle-o-notch fa-spin"></i>',
        paginate: {
            first: '<i class="fa fa-fast-backward"></i>',
            last: '<i class="fa fa-fast-forward"></i>',
            next: '<i class="fa fa-caret-right"></i>',
            previous: '<i class="fa fa-caret-left"></i>'
        }
    },
    oTableTools: {
        sSwfPath: "{{ url('/assets/swf/tabletools/copy_csv_xls_pdf.swf') }}",
        sRowSelect: "os",
        aButtons: [{
            "sExtends": "copy",
            "sButtonText": '<i class="fa fa-files-o"></i>'
        }, {
            "sExtends": "xls",
            "sFileName": "*.xls",
            "sButtonText": '<i class="fa fa-file-excel-o"></i>'
        }, {
            "sExtends": "pdf",
            "sButtonText": '<i class="fa fa-file-pdf-o"></i>'
        }]
    }
})
.on('init.dt', function() {
});

function viewSingleRecord(sl)
{
	$('body').modalmanager('loading');
	
	$modal.load("{{ url('/api/v1/widget/view-data-modal?sl=') }}" + sl, '', function(){
		$modal.modal();
		onModalLoad();
	});
}

$('#dt-table-stats-forms_wrapper .dataTables_filter input').attr('placeholder', "{{ trans('global.search_') }}");

$('#dt-table-stats-forms').on('click', '.row-btn-delete', function() {
    var sl = $(this).parent('.row-actions').attr('data-sl');

	swal({
	  title: _lang['confirm'],
	  type: "warning",
	  showCancelButton: true,
	  cancelButtonText: _lang['cancel'],
	  confirmButtonColor: "#DD6B55",
	  confirmButtonText: _lang['yes_delete']
	}, 
	function(){
		blockUI();
	
		var jqxhr = $.ajax({
			url: "{{ url('/api/v1/widget/delete-data') }}",
			data: { sl: sl},
			method: 'POST'
		})
		.done(function(data) {
            if(data.result == 'success')
            {
    			stats_widget_data_table.ajax.reload();
            }
            else
            {
                swal(data.msg);
            }
		})
		.fail(function() {
			console.log('error');
		})
		.always(function() {
			unblockUI();
		});
	});
});

</script>

	<div class="table-primary">
		<table class="table table-striped table-bordered table-hover" id="dt-table-stats-forms">
			<thead>
				<tr>
					<th>{{ Lang::get('global.app') }} / {{ Lang::get('global.page') }}</th>
					<th>{{ Lang::get('global.created') }}</th>
					<th class="text-right">{{ Lang::get('global.actions') }}</th>
				</tr>
			</thead>
		</table>
	</div>


	</div>
</div>

<?php
	} // No app selected or stats found
	?>
<script>
	<?php
		if($first_created !== false)
		{
		?>
	
	$('#stats-range').daterangepicker({
		ranges: {
			 '<?php echo trans('global.today') ?>': [ Date.today(), Date.today() ],
			 '<?php echo trans('global.yesterday') ?>': [ Date.today().add({ days: -1 }), Date.today().add({ days: -1 }) ],
			 '<?php echo trans('global.last_7_days') ?>': [ Date.today().add({ days: -6 }), Date.today() ],
			 '<?php echo trans('global.last_30_days') ?>': [ Date.today().add({ days: -29 }), Date.today() ],
			 '<?php echo trans('global.this_month') ?>': [ Date.today().moveToFirstDayOfMonth(), Date.today().moveToLastDayOfMonth() ],
			 '<?php echo trans('global.last_month') ?>': [ Date.today().moveToFirstDayOfMonth().add({ months: -1 }), Date.today().moveToFirstDayOfMonth().add({ days: -1 }) ]
		},
		opens: 'left',
		format: 'MM-DD-YYYY',
		separator: ' <?php echo trans('global.date_to') ?> ',
		startDate: Date.parse('<?php echo $date_start ?>').toString('MM-d-yyyy'),
		endDate: Date.parse('<?php echo $date_end ?>').toString('MM-d-yyyy'),
		minDate: Date.parse('<?php echo $first_created ?>').toString('MM-d-yyyy'),
		maxDate: '<?php echo date('m/d/Y') ?>',
		locale: {
			applyLabel: '<?php echo trans('global.submit') ?>',
			cancelLabel: '<?php echo trans('global.reset') ?>',
			fromLabel: '<?php echo trans('global.date_from') ?>',
			toLabel: '<?php echo trans('global.date_to') ?>',
			customRangeLabel: '<?php echo trans('global.custom_range') ?>',
			daysOfWeek: ['<?php echo trans('global.su') ?>', '<?php echo trans('global.mo') ?>', '<?php echo trans('global.tu') ?>', '<?php echo trans('global.we') ?>', '<?php echo trans('global.th') ?>', '<?php echo trans('global.fr') ?>','<?php echo trans('global.sa') ?>'],
			monthNames: monthNames,
			firstDay: 1
		},
		showWeekNumbers: true,
		buttonClasses: ['btn']
	});
	
	$('#stats-range').on('apply.daterangepicker', function(ev, picker) {
	    var start = picker.startDate.format('YYYY-MM-DD');
	    var end = picker.endDate.format('YYYY-MM-DD');
	    document.location = '#/app/widget-data/' + start + '/' + end + '/{{ $sl }}';
	});
	
	/* Set the initial state of the picker label */
	var d_start = Date.parse('<?php echo $date_start ?>');
	var d_end = Date.parse('<?php echo $date_end ?>');
	
	d_start = monthNames[d_start.getMonth()] + ' ' + d_start.toString('d, yyyy');
	d_end = monthNames[d_end.getMonth()] + ' ' + d_end.toString('d, yyyy');
	
	var d_string = (d_start == d_end) ? d_start : d_start + ' - ' + d_end;
	
	$('#stats-range span').html(d_string);
	<?php
		}
		?>
	
	var setEqHeight = function () {
		$('#content-wrapper .row').each(function () {
			var $p = $(this).find('.stat-panel');
			if (! $p.length) return;
			$p.attr('style', '');
			var h = $p.first().height(), max_h = h;
			$p.each(function () {
				h = $(this).height();
				if (max_h < h) max_h = h;
			});
			$p.css('height', max_h);
		});
	};
/*
	setEqHeight();
	$(window).on('pa.resize', setEqHeight);
	$(window).resize();
*/
</script>
@stop