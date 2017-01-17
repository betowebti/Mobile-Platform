@extends('../app.layouts.partial')
@section('content')
<script src="{{ url('/assets/js/leaflet-heat.js') }}"></script>

<ul class="breadcrumb breadcrumb-page">
	<div class="breadcrumb-label text-light-gray">{{ trans('global.you_are_here') }} </div>
	<li><a href="{{ trans('global.home_crumb_url') }}">{{ trans('global.home_crumb_text') }}</a></li>
	<li>{{ trans('global.content') }}</li>
	<?php
		if($campaign === false && $app === false) {
		?>
	<li><a href="#/apps">{{ trans('global.apps') }}</a></li>
	<li class="active">{{ trans('global.analytics') }}</li>
	<?php
		} elseif($app === false && isset($campaign->name)) {
		?>
	<li><a href="#/apps">{{ trans('global.apps') }}</a></li>
	<li><a href="#/app/analytics">{{ trans('global.analytics') }}</a></li>
	<li class="active">{{ $campaign->name }}</li>
	<?php
		} elseif(isset($campaign->name)) {
			$sl_campaign = \App\Core\Secure::array2string(array('campaign_id' => $campaign->id));
		?>
	<li><a href="#/apps">{{ trans('global.apps') }}</a></li>
	<li><a href="#/app/analytics">{{ trans('global.analytics') }}</a></li>
	<li><a href="#/app/analytics/{{ $sl_campaign }}">{{ $campaign->name }}</a></li>
	<li class="active">{{ $app->name }}</li>
	<?php
		}
		?>
</ul>
<?php
	$title = (isset($campaign->name)) ? $campaign->name : trans('global.analytics');
	$title = ($app !== false) ? $app->name : $title;
	?>
<div class="page-header">
	<div class="row">
		<h1 class="col-xs-12 col-sm-4 text-center text-left-sm" style="height:32px"><i class="fa fa-line-chart page-header-icon"></i> {{ $title }}</h1>
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
							<li><a href="#/app/analytics/{{ $date_start }}/{{ $date_end }}/" tabindex="-1">{{ trans('global.remove_filter') }}</a></li>
							<li class="divider"></li>
							<?php
								$campaign_name_old = '';
								foreach($apps as $app_select)
								{
									if($campaign_name_old != $app_select->campaign_name)
									{
										$sl_campaign = \App\Core\Secure::array2string(array('campaign_id' => $app_select->campaign_id));
										$class = ($app === false && isset($campaign->id) && $campaign->id == $app_select->campaign_id) ? ' active' : '';
										echo '<li class="nav-header nav-link' . $class . '"><a href="#/app/analytics/' . $sl_campaign . '">' . $app_select->campaign_name . '</a></li>';
									}
								
									$campaign_name_old = $app_select->campaign_name;
								
								    $sl_app = \App\Core\Secure::array2string(array('app_id' => $app_select->id));
								    $class = (isset($app->id) && $app->id == $app_select->id) ? 'active': '';
								?>
							<li class="{{ $class }}"><a href="#/app/analytics/{{ $date_start }}/{{ $date_end }}/{{ $sl_app }}" tabindex="-1">{{ $app_select->name }}</a></li>
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
<?php
$app_range = array();
$labels = array();
$ykeys = array();
$data = '';
foreach($app_stats['apps'] as $app_data)
{
	$labels[] = "'" . str_replace("'", "\'", $app_data['name']) . "'";
	$ykeys[] = "'app" . $app_data['id'] . "'";

	foreach($app_data['range'] as $date => $visits)
	{
		if(! isset($app_range[$date])) $app_range[$date] = '';
		if($visits === NULL) $visits = 'null';
		$app_range[$date] .= "app" . $app_data['id'] . ": " . $visits . ",";
	}
}

foreach($app_range as $date => $stat_string)
{
	$data .= "{ day: '" . $date . "', " . trim($stat_string, ',') . " },";
}
$data = trim($data, ',');

if($data == '')
{
	// No visits
	echo '<h2 class="padding-sm">' . trans('global.no_stats_found_for_period') . '</h2>';
}
else
{
?>
<div class="row">
	<div class="col-md-<?php echo (empty($app_stats['segments']['heatmap'])) ? '12' : '8'; ?>">
	<script>
		Morris.Line({
			element: 'app_visits',
			data: [ <?php echo $data ?> ],
			ykeys: [{{ implode(',', $ykeys) }}],
			labels: [{{ implode(',', $labels) }}],
			ymin: 0,
			yLabelFormat: function(y){return y != Math.round(y)?'':y;},
			hideHover: 'auto',
			lineColors: ['#2196F3', '#009688', '#CDDC39', '#FF9800', '#9E9E9E', '#9C27B0', '#795548', '#FFC107', '#8BC34A', '#00BCD4', '#3F51B5', '#E91E63', '#607D8B', '#FF5722', '#FFEB3B', '#4CAF50', '#03A9F4', '#673AB7', '#F44336'],
			fillOpacity: 0.3,
			behaveLikeLine: true,
			lineWidth: 2,
			pointSize: 4,
			gridLineColor: '#cfcfcf',
			gridTextColor: '#222',
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
				<div class="stat-cell padding-sm">
					<div class="text-lg" style="margin-bottom:10px;"><i class="fa fa-mobile"></i> {{ trans('global.visits') }}</div>
					<div id="app_visits" class="graph" style="height: 320px;"></div>
				</div>
			</div>
		</div>
	</div>
<?php if(! empty($app_stats['segments']['heatmap'])) { ?>
	<div class="col-md-4">
<?php
$heatmap = '';
foreach($app_stats['segments']['heatmap'] as $latlng => $count)
{
	$heatmap .= '[' . $latlng . ', "' . $count . '"],';
}
$heatmap = trim($heatmap, ',');
?>
<script>
var map = L.map('heat_map');

L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="http://openstreetmap.org" target="_blank">OpenStreetMap</a>',
    maxZoom: 16
}).addTo(map);

var heatPoints = [{{ $heatmap }}];
var heat = L.heatLayer(heatPoints, {
	maxZoom: 12
}).addTo(map);

var bounds = new L.LatLngBounds(heatPoints);
map.fitBounds(bounds);

if(map.getZoom() > 14)
{
	map.setZoom(12);
}

</script>
		<div class="stat-panel">
			<div class="stat-row">
				<div class="stat-cell padding-sm">
					<div class="text-lg" style="margin-bottom:10px;"><i class="fa fa-globe"></i> {{ trans('global.heatmap') }}</div>
					<div id="heat_map" style="height:320px; border:1px solid #cfcfcf"></div>
				</div>
			</div>
		</div>
	</div>
<?php } ?>
</div>
<div class="row">
<?php
$segments = array(
	array(
		'segment' => 'os',
		'title' => trans('global.os'),
		'icon' => 'fa-desktop'
	),
	array(
		'segment' => 'client',
		'title' => trans('global.browser'),
		'icon' => 'fa-mobile'
	),
	array(
		'segment' => 'city',
		'title' => trans('global.city'),
		'icon' => 'fa-map-marker'
	)
);

foreach($segments as $segment)
{
	if(isset($app_stats['segments'][$segment['segment']]))
	{
?>
	<div class="col-md-4">
		<div class="stat-panel">
			<div class="stat-row">
				<div class="stat-cell padding-sm">
					<div class="text-lg" style="margin-bottom:10px;"><i class="fa {{ $segment['icon'] }}"></i> {{ $segment['title'] }}</div>
<?php
$data = '';
foreach($app_stats['segments'][$segment['segment']] as $key => $val)
{
  if ($key == '') $key = trans('global._not_set_');
	$data .= "{label: \"" . $key . "\", value: " . $val . "},";
}
$data = trim($data, ',');
?>
<script>
Morris.Donut({
	element: 'segment_{{ $segment['segment'] }}',
	resize: true,
	colors: ['#4ab6d5', '#5cbdd9', '#6ec5de', '#80cce2', '#92d3e6', '#a4daea', '#b7e2ee'],
	data: [{{ $data }}]
});

$('#table_segment_{{ $segment['segment'] }}').dataTable({
	dom: "t"+
		  "<'table-footer clearfix'<'DT-label'i><'DT-pagination'p>>",
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
	}
});
</script>

                     <div id="segment_{{ $segment['segment'] }}" class="graph"></div>
				</div>
			</div>
		</div>

        <div class="table-primary">
            <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="table_segment_{{ $segment['segment'] }}" style="margin:0">
                <thead>
                    <tr>
                        <th>{{ $segment['title'] }}</th>
                        <th class="text-right">{{ trans('global.visits') }} &nbsp;</th>
                    </tr>
                </thead>
                <tbody>
<?php
foreach($app_stats['segments'][$segment['segment']] as $key => $val)
{
  if ($key == '') $key = trans('global._not_set_');
?>
                    <tr>
                        <td>{{ $key }}</td>
                        <td class="text-right">{{ $val }}</td>
                    </tr>
<?php
}
?>
                </tbody>
            </table>
        </div>

	</div>
<?php
	}
}
?>
</div>
<?php
}
?>
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
	    document.location = '#/app/analytics/' + start + '/' + end + '/{{ $sl }}';
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