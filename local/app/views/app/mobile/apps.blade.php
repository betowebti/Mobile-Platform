@extends('../app.layouts.partial')

@section('content')
	<ul class="breadcrumb breadcrumb-page">
		<div class="breadcrumb-label text-light-gray">{{ trans('global.you_are_here') }} </div>
		<li><a href="{{ trans('global.home_crumb_url') }}">{{ trans('global.home_crumb_text') }}</a></li>
		<li>{{ trans('global.content') }}</li>
		<li class="active">{{ trans('global.apps') }}</li>
	</ul>

    <div class="page-header">
        <div class="row">
            <h1 class="col-xs-12 col-sm-4 text-center text-left-sm"><i class="fa fa-th page-header-icon"></i> {{ trans('global.apps') }}</h1>
            <div class="col-xs-12 col-sm-8">
                <div class="row">
                    <hr class="visible-xs no-grid-gutter-h">
<?php
if(\Auth::user()->getRoleId() != 4)
{
?>
                    <div class="pull-right col-xs-12 col-sm-auto"><a href="#/app/new" class="btn btn-primary btn-labeled" style="width: 100%;"><span class="btn-label icon fa fa-plus"></span> {{ trans('global.new_app') }}</a></div>
<?php
}
?>
                    <div class="visible-xs clearfix form-group-margin"></div>
<?php
if($apps->count() > 0)
{
?>
                    <form action="" class="pull-right col-xs-12 col-sm-6">
                        <div class="input-group no-margin">
                            <span class="input-group-addon" style="border:none;background: #fff;background: rgba(0,0,0,.05);"><i class="fa fa-search"></i></span>
                            <input type="text" id="search_grid" placeholder="{{ trans('global.search_') }}" class="form-control no-padding-hr" style="border:none;background: #fff;background: rgba(0,0,0,.05);">
							<div class="input-group-btn" id="campaign-filter">
								<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-filter"></i> <span class="caret"></span></button>
								<ul class="dropdown-menu dropdown-menu-right" role="menu">
									<li data-id="0" class="active"><a href="javascript:void(0);" tabindex="-1">{{ trans('global.all_campaigns') }}</a></li>
									<li class="divider"></li>
<?php
foreach($campaigns as $campaign)
{
?>
									<li data-id="{{ $campaign->id }}"><a href="javascript:void(0);" tabindex="-1">{{ $campaign->name }}</a></li>
<?php
}
?>								</ul>
							</div>
                        </div>
                    </form>
<?php
}
?>
                </div>
            </div>
        </div>
    </div>

<?php
if($apps->count() > 0)
{
?>
	<div class="row" id="grid">
<?php
$i = 0;
foreach($apps as $app)
{
	$sl = \App\Core\Secure::array2string(array('app_id' => $app['id']));
	$icon = $app->icon(120);
?>
	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-4 campaign campaign{{ $app->campaign_id }}" id="app{{ $i }}">
		<div class="panel panel-default panel-dark panel-body-colorful widget-profile widget-profile-centered widget-profile-default">
			<div class="panel-heading">
				<a href="#/app/edit/{{ $sl }}">
					<img src="{{ $icon }}" alt="" class="widget-profile-avatar">
				</a>
				<div class="widget-profile-header">
					<span class="ellipsis-oneline">{{ $app['name'] }}</span>
				</div>
			</div>
			<div class="widget-profile-counters">
				<div class="col-xs-3 stat-block">
                    <span class="stat-visits"><div class="small-throbber"> </div></span>
                    <div class="ellipsis-oneline-small"><a href="#/stats/{{ $sl }}">{{ trans('global.visits') }}</a></div>
                </div>
				<div class="col-xs-3 stat-block">
                    <span>{{ $app->appPages->count() }}</span>
                    <div class="ellipsis-oneline-small">{{ trans('global.pages') }}</div>
                </div>
				<div class="col-xs-6">
					<a href="#/app/edit/{{ $sl }}" class="btn btn-primary btn-xs" data-toggle="tooltip" title="{{ trans('global.edit_app') }}"><i class="fa fa-pencil fa-1x"></i></a>
					<a href="javascript:void(0);" class="btn btn-default btn-xs" data-modal="{{ url('/app/modal/mobile/qr?id=btnQr' . $i) }}" id="btnQr{{ $i }}" data-text="{{ $app->domain() }}" data-toggle="tooltip" title="{{ trans('global.qr_code') }}"><i class="fa fa-qrcode fa-1x"></i></a>
					<a href="javascript:void(0);" data-sl="{{ $sl }}" class="btn btn-danger btn-xs btn-delete" data-toggle="tooltip" title="{{ trans('global.delete_app') }}"><i class="fa fa-trash fa-1x"></i></a>
				</div>
			</div>
			<br style="clear:both">

		</div>
	</div>
<script>
$.getJSON("{{ url('/api/v1/app-analytics/visits?sl=' . $sl) }}", function(data) {
	$('#app{{ $i }} .stat-visits').html('<a href="#/app/analytics/{{ $sl }}">' + data.visits + '</a>');
});
</script>
<?php
	$i++;
}
?>
	</div>

<script>
$('#grid').liveFilter('#search_grid', 'div.col-xs-12', {
  filterChildSelector: '.widget-profile-header',
  before: function() {
	$('#campaign-filter li').removeClass('active');
	$('#campaign-filter li[data-id=0]').addClass('active');
	$('.campaign').show();
  }
});

</script>


<?php
}
else
{
	// No records yet
	if(\Auth::user()->parent_id != NULL && \Auth::user()->getRoleId() == 4)
	{
		echo '<div class="callout pull-left">' . Lang::get('global.no_app_permission') . '</div>';
	}
	else
	{
		echo '<div class="callout pull-right arrow-right-up" style="margin-right:180px">' . Lang::get('global.create_first_app') . ' <i class="fa fa-arrow-circle-up fa-2x fa-rotate-45"></i></div>';
	}
}
?>

<script>
$('#campaign-filter li:not(.divider)').on('click', function() {
	var id = $(this).attr('data-id');

	$('#campaign-filter li').removeClass('active');
	$(this).addClass('active');

	if(id == 0)
	{
		$('.campaign').show();
	}
	else
	{
		$('.campaign').hide();
		$('.campaign' + id).show();
	}
});

$('.btn-delete').on('click', function() {
	var sl = $(this).attr('data-sl');
    swal({
      title: "{{ trans('global.are_you_sure') }}",
      text: "{{ trans('global.confirm_delete_app') }}",
      type: "warning",
      showCancelButton: true,
      confirmButtonClass: "btn-danger",
      confirmButtonText: "{{ trans('global.delete_app') }}",
      cancelButtonText: "{{ trans('global.cancel') }}",
      closeOnConfirm: true,
      closeOnCancel: true
    },
    function(isConfirm)
    {
      if(isConfirm)
      {
        blockUI();
        var request = $.ajax({
          url: "{{ url('/api/v1/app/delete') }}",
          type: 'GET',
          data: {data : sl},
          dataType: 'json'
        });

        request.done(function(json) {

            /* Decrement count */
            var count = parseInt($('#count_apps').text());
            $('#count_apps').text(count-1);

            /* Open site overview */
            document.location = '#/apps?' + new Date().getTime();
            unblockUI();
        });

        request.fail(function(jqXHR, textStatus) {
            alert('Request failed, please try again (' + textStatus, ')');
            unblockUI();
        });
      }
    });
});

</script>
@stop