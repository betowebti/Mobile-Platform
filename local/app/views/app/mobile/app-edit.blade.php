@extends('../app.layouts.partial')

@section('content')
<ul class="breadcrumb breadcrumb-page">
    <div class="breadcrumb-label text-light-gray">{{ trans('global.you_are_here') }} </div>
    <li><a href="{{ trans('global.home_crumb_url') }}">{{ trans('global.home_crumb_text') }}</a></li>
	<li>{{ trans('global.content') }}</li>
	<li><a href="#/apps">{{ trans('global.apps') }}</a></li>
	<li>{{ $app->campaign->name }}</li>
	<li class="active">{{ trans('global.edit_app') }}</li>
</ul>

<div class="page-header">
	<div class="row">
		<h1 class="col-xs-12 col-sm-7 text-center text-left-sm" style="height:32px"><a href="#/apps"><img src="{{ $app->icon(40) }}" style="position:absolute; top:-5px" id="app-icon-top"></a> <span style="margin-left:50px" id="bs-x-campaign" data-type="select" data-value="{{ $app->campaign->id }}" data-pk="{{ $sl }}">{{ $app->campaign->name }}</span> <small>\</small> <small id="app_name" class="bs-x-text" data-type="text" data-clear="false" data-mode="inline" data-pk="{{ $sl }}">{{ $app->name }}</small></h1>

		<div class="col-xs-12 col-sm-5">
			<div class="row">
				<hr class="visible-xs no-grid-gutter-h">

				<div class="pull-right col-xs-12 col-sm-auto">
					<div class="btn-group" style="width:100%;" id="tour-options">
						<button class="btn btn-default dropdown-toggle" style="width:100%;" type="button" data-toggle="dropdown" tooltip="{{ trans('global.options') }}"><i class="icon fa fa-bars"></i> &nbsp; <i class="fa fa-caret-down"></i></button>
						<ul class="dropdown-menu dropdown-menu-right" role="menu">
							<li><a href="javascript:void(0);" data-toggle="modal" data-target="#qrModal" tabindex="-1"><i class="fa fa-qrcode"></i> &nbsp; {{ trans('global.qr_code') }}</a></li>
							<li><a href="javascript:void(0);" data-modal="{{ url('/app/modal/mobile/app-redirect?sl=' . $sl) }}" tabindex="-1"><i class="fa fa-code"></i> &nbsp; {{ trans('global.redirect_code') }}</a></li>
							<li class="divider"></li>
							<li><a href="javascript:void(0);" data-modal="{{ url('/app/modal/mobile/app-settings?sl=' . $sl) }}" tabindex="-1"><i class="fa fa-cogs"></i> &nbsp; {{ trans('global.app_settings') }}</a></li>
							<li class="divider"></li>
<?php if ($download) { ?>
							<li><a href="javascript:void(0);" data-modal="{{ url('/app/modal/mobile/app-export?sl=' . $sl) }}" tabindex="-1"><i class="fa fa-download"></i> &nbsp; {{ trans('global.download_app') }}</a></li>
<?php } ?>
<?php
if(\Auth::user()->getRoleId() != 4)
{
?>
							<li<?php if ($app_limit) { echo ' class="disabled"'; } ?>><a href="javascript:void(0);" tabindex="-1" <?php if (! $app_limit) { ?>id="btnDuplicateApp"<?php } ?>><i class="fa fa-copy"></i> &nbsp; {{ trans('global.duplicate_app') }}</a></li>
							<li><a href="javascript:void(0);" tabindex="-1" id="btnDeleteApp"><i class="fa fa-trash-o"></i> &nbsp; {{ trans('global.delete_app') }}</a></li>
							<li class="divider"></li>
<?php
}
?>							<li><a href="javascript:void(0);" tabindex="-1" onClick="appLaunchEditorTour()"><i class="fa fa-info-circle"></i> &nbsp; {{ trans('global.help') }}</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="qrModal" data-ng-app="monospaced.qrcode">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">{{ trans('global.qr_code') }}</h4>
            </div>
            <div class="modal-body" data-ng-init="url='{{ $app->domain() }}';v=6;e='M';s=256;" style="padding-bottom:0">

                <qrcode version="@{{v}}" error-correction-level="@{{e}}" size="@{{s}}" data="@{{url}}" download id="qrcode"></qrcode>

                <div class="form-group">
                    <label for="url">{{ trans('global.url') }}</label>
                    <textarea id="url" class="form-control" data-ng-model="url" maxlength="2953">http://{{ $app->domain() }}</textarea>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="size">{{ trans('global.size') }}</label>
							<div class="input-group">
	                            <input id="size" class="form-control" type="number" data-ng-model="s">
								<div class="input-group-addon">px</div>
							</div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="version">{{ trans('global.version') }}</label>
                            <input id="version" class="form-control" type="number" data-ng-model="v" min="1" max="40">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="level">{{ trans('global.level') }}</label>
                            <select class="form-control" id="level" data-ng-model="e" data-ng-options="option.version as option.name for option in [{name:'{{ trans('global.low') }}', version:'L'},{name:'{{ trans('global.medium') }}', version:'M'},{name:'{{ trans('global.quartile') }}', version:'Q'},{name:'{{ trans('global.high') }}', version:'H'}]">
                            </select>
                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-primary" onclick="downloadQr(this, '.qrcode', 'qr.png');">{{ trans('global.download') }}</a>
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('global.close') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
function downloadQr(link, canvasClass, filename) {
    link.href = $(canvasClass).get(0).toDataURL();
    link.download = filename;
}
</script>

<div class="row">
<div class="col-xs-12 col-md-8">

    <div class="panel panel-default panel-dark">

<!-- Tabs ===========================================================================

	Pages and style tabs
-->

        <div class="panel-heading" style="height:36px;">
            <ul class="nav nav-tabs nav-justified bg-primary" style="right:0;height:36px;">
                <li ng-class="{active: selectedTab == 'pages'}">
                    <a href="javascript:void(0);" ng-click="selectedTab = 'pages';"><span class="fa fa-th"></span> &nbsp; {{ trans('global.pages') }} (<span id="app-page-count">{{ count($app_pages) }}</span>)</a>
                </li>
                <li ng-class="{active: selectedTab == 'style'}" id="tour-design">
                    <a href="javascript:void(0);" ng-click="selectedTab = 'style';"><span class="fa fa-paint-brush"></span> &nbsp; {{ trans('global.design_and_layout') }}</a>
                </li>
            </ul>
        </div>
        <div class="panel-body no-padding" ng-init="selectedTab = 'pages';">

            <div class="tab-content no-padding">

                <div class="tab-pane fade " ng-class="{'in active': selectedTab == 'pages'}" id="app-pages">

<!-- Pages ===========================================================================

	Pages carousel
-->

					<div style="margin:20px 40px 0">
						<wrap-owlcarousel class="owl-carousel panel-pages unselectable" id="carousel_pages"  
							data-options="{
								navigation: true,
								navigationText: ['<i class=\'car-btn-prev fa fa-angle-left\'></i>', '<i class=\'car-btn-next fa fa-angle-right\'></i>'],
								items: 6,
								autoWidth:true,
								itemsDesktop: [1199,4],
								itemsDesktopSmall: [979,4],
								itemsTablet: [768,3],
								itemsTabletSmall: false,
								itemsMobile: [479,2],
								mouseDrag: false,
								touchDrag: false,
								loop: true
							}">

<?php

foreach($app_pages as $page)
{
	$sl_app_page = \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));

	$class = ($page->hidden == 1) ? ' page-hidden': '';;

	// Get widget info
	$widget_config = \Mobile\Controller\WidgetController::loadWidgetConfig($page->widget);

	echo '<li>';
	echo '<div class="app-page-container' . $class . '" data-sl="' . $sl_app_page . '" data-slug="' . $page->slug . '" data-color="' . $widget_config['color'] . '" data-id="' . $page->id . '">';
	echo '<div class="app-page-drag-handle"></div>';
	echo '<div class="app-page-icon-holder bg-' . $widget_config['color'] . '">';
	echo '<div class="app-page-icon sprite-xs xs-sprite-' . $widget_config['icon'] . '"> </div>';
	echo '<div class="app-page-title ellipsis">' . $page->name . '</div>';
	echo '</div>';
	echo '<div class="app-page-selected"></div>';
	echo '</div>';
	echo '</li>';
}

echo '<li class="ui-state-disabled" id="tour-add-page">';
echo '<div class="app-page-container ui-state-disabled">';
echo '<div class="app-page-icon-holder app-page-new">';
echo '<i class="app-page-icon fa fa-plus"></i>';
echo '</div>';
echo '<div class="app-page-selected"></div>';
echo '</div>';
echo '</li>';

?>

						</wrap-owlcarousel>
					</div>

                </div>

                <div class="tab-pane fade panel-pages-overflow" ng-class="{'in active': selectedTab == 'style'}" id="app-style">

<!-- Styles ===========================================================================

	Styles carousel
-->

<?php
// Get all themes
$themes = \Mobile\Controller\ThemeController::loadAllThemeConfig($app->theme);
?>

<div style="margin:10px 40px 10px">
	<wrap-owlcarousel class="owl-carousel unselectable panel-themes app-border-selection" id="carousel_themes"  
		data-options="{
			navigation: true,
			navigationText: ['<i class=\'car-btn-prev fa fa-angle-left\'></i>', '<i class=\'car-btn-next fa fa-angle-right\'></i>'],
			items: 3,
			autoWidth:true,
			itemsDesktop: [1199,3],
			itemsDesktopSmall: [979,3],
			itemsTablet: [768,3],
			itemsTabletSmall: false,
			itemsMobile: [479,2],
			mouseDrag: false,
			touchDrag: false,
			loop: true
		}" style="margin-top:10px">
<?php
foreach($themes as $theme)
{
	if(in_array($app->appType->id, $theme['categories']) && $theme['active'])
	{
		$class = ($theme['dir'] == $app->theme) ? ' active': '';
?>
		<div>
			<div class="text-center">
				<div class="text-center">
					<div class="app-border-container{{ $class }}" data-theme="{{ $theme['dir'] }}">
                       <div class="theme-container">
         					<img src="{{ url('/themes/' . $theme['dir'] . '/assets/img/preview.png') }}">
                       </div>
					</div>
				</div>
			</div>
		</div>
<?php
	}
}
?>
	</wrap-owlcarousel>
</div>
                </div>

            </div>
<style type="text/css">
#carousel_themes .owl-prev,
#carousel_themes .owl-next {
	top:113px;
}
</style>
				<div id="page-general-tab" style="display:none; margin:18px 0 0 7px" ng-class="{display_none: selectedTab == 'style'}">
	
					<div class="spinner" id="spinner" style="margin:10px auto">
						<div class="rect1"></div>
						<div class="rect2"></div>
						<div class="rect3"></div>
						<div class="rect4"></div>
						<div class="rect5"></div>
					</div>
	
				</div>

        </div>
    </div>

<!-- App pages ===========================================================================

	App pages
-->

    <div class="panel panel-default" id="page-new" style="display:none;margin-top: -22px; border-top: 0px;" ng-class="{display_none: selectedTab == 'style'}">
        <div class="panel-body" style="padding-top:10px">
            <ul class="nav nav-tabs nav-tabs-simple nav-justified">
                <li ng-class="{active: selectedTabType == 'recommended'}">
                    <a href="javascript:void(0);" ng-click="selectedTabType = 'recommended';">{{ trans('global.recommended') }}</a>
                </li>
<?php
foreach(trans('global.app_type_groups') as $group => $group_name)
{
?>
                <li ng-class="{active: selectedTabType == 'cat{{ $group }}'}">
                    <a href="javascript:void(0);" ng-click="selectedTabType = 'cat{{ $group }}';">{{ $group_name }}</a>
                </li>
<?php
}
?>
            </ul>
<br>

        <div class="panel-body no-padding" ng-init="selectedTabType = 'recommended';" id="app-select-pages">
            <div class="tab-content no-padding">

                <div class="tab-pane fade panel-pages-overflow" ng-class="{'in active': selectedTabType == 'recommended'}">
                    <div class="container-fluid" style="padding-top:2px">
	                    <div class="row">
<?php
foreach($widgets as $name => $config)
{
	if (in_array($app->appType->id, $config['recommended']))
	{
		if ($plan_widgets === false || in_array($config['dir'], $plan_widgets))
		{
			echo '<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">';
			echo '<div class="app-page-icon-holder bg-' . $config['color'] . '" data-widget="' . $config['dir'] . '">';
			echo '<div class="app-page-icon sprite-xs xs-sprite-' . $config['icon'] . '"> </div>';
			echo '</div>';
			echo '<div class="app-page-title">' . $name . '</div>';
			echo '</div>';
		}
		else
		{
			echo '<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">';
			echo '<div class="ribbon-wrapper-upgrade"><div class="ribbon-upgrade">' . trans('admin.upgrade') . '</div></div>';
			echo '<a href="#/account" class="app-page-icon-holder widget-upgrade bg-' . $config['color'] . '">';
			echo '<div class="app-page-icon sprite-xs xs-sprite-' . $config['icon'] . '"> </div>';
			echo '</a>';
			echo '<div class="app-page-title">' . $name . '</div>';
			echo '</div>';
		}
	}
}
?>
                		</div>
                	</div>
                </div>
<?php
foreach(trans('global.app_type_groups') as $group => $group_name)
{
?>
                <div class="tab-pane fade panel-pages-overflow" ng-class="{'in active': selectedTabType == 'cat{{ $group }}'}">
                    <div class="container-fluid" style="padding-top:2px">
	                    <div class="row">
<?php
    foreach ($widgets as $name => $config)
    {
		if ($config['group'] == $group)
		{
			if ($plan_widgets === false || in_array($config['dir'], $plan_widgets))
			{
				echo '<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">';
				echo '<div class="app-page-icon-holder bg-' . $config['color'] . '" data-widget="' . $config['dir'] . '">';
				echo '<div class="app-page-icon sprite-xs xs-sprite-' . $config['icon'] . '"> </div>';
				echo '</div>';
				echo '<div class="app-page-title">' . $name . '</div>';
				echo '</div>';
			}
		else
		{
			echo '<div class="col-xs-3 col-sm-3 col-md-2 col-lg-2">';
			echo '<div class="ribbon-wrapper-upgrade"><div class="ribbon-upgrade">' . trans('admin.upgrade') . '</div></div>';
			echo '<a href="#/account" class="app-page-icon-holder widget-upgrade bg-' . $config['color'] . '">';
			echo '<div class="app-page-icon sprite-xs xs-sprite-' . $config['icon'] . '"> </div>';
			echo '</a>';
			echo '<div class="app-page-title">' . $name . '</div>';
			echo '</div>';
		}
		}
    }
?>
                   		</div>
                    </div>
                </div>
<?php
}
?>

            </div>
        </div>

        </div>
    </div>

<!-- Page content ===========================================================================

	Page content accordion
-->

    <accordion close-others="false" id="page-content" style="display:none" ng-class="{display_none: selectedTab == 'style'}">

        <accordion-group heading="{{ trans('global.content') }}" ng-init="page_content = true" is-open="page_content">
            <div id="page-content-tab">

                <div class="spinner" id="spinner" style="margin:10px auto">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>

            </div>
        </accordion-group>
    
        <accordion-group heading="{{ trans('global.page_design') }}" ng-init="page_design = false" is-open="page_design">
            <div id="page-design-tab">

                <div class="spinner" id="spinner" style="margin:10px auto">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                    <div class="rect4"></div>
                    <div class="rect5"></div>
                </div>

            </div>
        </accordion-group>

    </accordion>


<!-- Page content ===========================================================================

	Page content accordion
-->

    <accordion close-others="false" id="app-layouts" style="display:none" ng-class="{'display_none': selectedTab == 'pages', 'display': selectedTab == 'style'}">

        <accordion-group heading="{{ trans('global.navigation') }}" ng-init="style_navigation = true" is-open="style_navigation">
            <div id="style-navigation-tab">

				<ul class="app-border-selection-ul">
					<li data-layout="tabs-bottom"><img src="{{ url('/assets/images/interface/nav-layouts/tabs-bottom.png') }}" style="width:64px"></li>
					<li data-layout="tabs-top"><img src="{{ url('/assets/images/interface/nav-layouts/tabs-top.png') }}" style="width:64px"></li>
					<li data-layout="side-left"><img src="{{ url('/assets/images/interface/nav-layouts/side-left.png') }}" style="width:64px"></li>
					<li data-layout="side-right"><img src="{{ url('/assets/images/interface/nav-layouts/side-right.png') }}" style="width:64px"></li>
				</ul>

            </div>
        </accordion-group>

        <accordion-group heading="{{ trans('global.app_design') }}" ng-init="app_design = true" is-open="app_design">
            <div id="app-design-tab">

				<div>
					<label>{{ trans('global.background') }}</label>
				</div>
<?php
$app_bg_default = url('/themes/' . $app->theme() . '/assets/img/background-phone.png');
$app_bg = ' style="background-image:url(\'' . url('/themes/' . $app->theme()) . '/assets/img/background-phone.png\')"';
$app_bg = ($app->background_smarthpones_file_name != '') ? ' style="background-image:url(\'' . $app->background_smarthpones->url('bg') . '\')"': $app_bg ;
$app_bg_class = ($app_bg != '') ? ' filled': '';
$app_bg_delete = ($app->background_smarthpones_file_name != '') ? '' : 'display:none';
$bg_custom = ($app->background_smarthpones_file_name != '') ? '1' : '0';
?>
				<a href="javascript:void(0);" class="btn btn-sm btn-danger" id="remove-app-bg" tooltip="{{ trans('global.remove_image') }}" style="position:absolute;left:95px;margin-top:114px;width:20px;padding:2px 0;{{ $app_bg_delete }}"><i class="fa fa-remove"></i></a>
				<a href="javascript:void(0)" class="img thumbnail vertical select-image{{ $app_bg_class }}" id="bg-app" data-id="bg-app"{{ $app_bg }} data-default="{{ $app_bg_default }}" data-custom="{{ $bg_custom }}">
					<i class="fa fa-plus-circle"></i>
					<div>
					640x1136
					</div>
				</a>

				<div class="list-group" style="float:left;">
					<a href="javascript:void(0);" class="list-group-item select-image" id="upload-app-bg" data-id="bg-app"><i class="fa fa-cloud-upload"></i> {{ trans('global.upload_image') }}</a>
					<a href="javascript:void(0);" class="list-group-item" id="select-no-app-bg"><i class="fa fa-ban"></i> {{ trans('global.no_image') }}</a>
					<a href="javascript:void(0);" class="list-group-item" id="select-app-bg"><i class="fa fa-folder-o"></i> {{ trans('global.select_image') }}</a>
				</div>

                <div id="app-bg-selection">
<?php
$backgrounds = \File::files(public_path() . '/static/app-backgrounds');

foreach($backgrounds as $background)
{
    $background_img = basename($background);
    $background_path = str_replace($background_img, '', $background);
    $thumb = $background_path . '/thumbs/100x140-' . $background_img;
    $img_url = '/static/app-backgrounds/' . $background_img;
    $thumb_url = '/static/app-backgrounds/thumbs/100x140-' . $background_img;

    if(! \File::exists($thumb))
    {
        $img = \Image::make($background)->fit(100, 140, function ($constraint) {
            //$constraint->aspectRatio();
        })->save($thumb);
    }
?>
				<a href="javascript:void(0)" class="img thumbnail vertical" style="background:url('{{ url($thumb_url); }}')" data-img="{{ $img_url }}"> </a>
<?php
}
?>
                </div>

				<br style="clear:both">
				<hr>

                <div class="row">
                    <div class="col-md-6">

                        <div>
                            <label>{{ trans('global.icon') }}</label>
                        </div>

				        <a href="javascript:void(0);" id="app-icon-picker"><img src="{{ $app->icon(120, true); }}"></a>
<script>
$('#app-icon-picker').popover({
    content: '<?php

$icons = \File::directories(public_path() . '/static/app-icons');

foreach($icons as $icon)
{
    $name = basename($icon);
?>

				<a href="javascript:void(0)" data-icon="{{ $name }}"><img src="{{ url('/static/app-icons/' . $name . '/40.png'); }}"></a>
<?php
}

?>',
	placement: 'right',
	template: '<div class="popover" role="tooltip" style="width:592px;max-width:600px"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content" id="app-icon-picker-content"></div></div>',
	html: true
});

</script>
                    </div>
                    <div class="col-md-6">

                        <div>
                            <label>{{ trans('global.custom_icon') }}</label>
                        </div>
<?php
$app_icon = '';
$app_icon = ($app->header_file_name != '') ? ' style="background-image:url(\'' . $app->header->url('icon120') . '\')"': $app_icon;
$app_icon_class = ($app_icon != '') ? ' filled': '';
$app_icon_delete = ($app->header_file_name != '') ? '' : 'display:none';
$app_icon_custom = ($app->header_file_name != '') ? '1' : '0';
?>
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger" id="remove-app-icon" tooltip="{{ trans('global.remove_image') }}" style="position:absolute;left:106px;margin-top:94px;width:20px;padding:2px 0;{{ $app_icon_delete }}"><i class="fa fa-remove"></i></a>
                        <a href="javascript:void(0)" class="img thumbnail square select-image{{ $app_icon_class }}" id="app-icon" data-id="app-icon"{{ $app_icon }} data-custom="{{ $app_icon_custom }}" data-original="{{ $app->icon(40, true); }}">
                            <i class="fa fa-plus-circle"></i>
                            <div>
                            1024x1024
                            </div>
                        </a>

                    </div>
                </div>

            </div>
        </accordion-group>

    </accordion>


</div>
    <div class="col-xs-12 col-md-4 text-center">

<!-- Phone ===========================================================================

	CSS phone preview
-->

        <div class="marvel-device iphone6">
            <div class="top-bar"></div>
            <div class="sleep"></div>
            <div class="volume"></div>
            <div class="camera"></div>
            <div class="sensor"></div>
            <div class="speaker"></div>
            <div class="screen">
                <iframe src="{{ url('/mobile/' . $app->local_domain) }}" frameborder="0" id="device-screen"></iframe>
            </div>
            <div class="home"></div>
            <div class="bottom-bar"></div>
        </div>

        <br><br>

        <div class="btn-group dropup device-selector">
            <button type="button" class="btn btn-rounded dropdown-toggle" data-toggle="dropdown"><i class="fa fa-android fa-3x"></i></button>
            <ul class="dropdown-menu">
                <li data-phone="nexus5"><a href="javascript:void(0);">Nexus 5</a></li>
<?php /*                    <li data-phone="lumia920"><a href="javascript:void(0);">Lumia 920</a></li>*/ ?>
                <li data-phone="s5"><a href="javascript:void(0);">Samsung Galaxy S5</a></li>
<?php /*                    <li data-phone="htc-one"><a href="javascript:void(0);">HTC One</a></li>*/ ?>
            </ul>
        </div>

        <div class="btn-group dropup device-selector">
            <button type="button" class="btn btn-rounded dropdown-toggle" data-toggle="dropdown"><i class="fa fa-apple fa-3x"></i></button>
            <ul class="dropdown-menu">
                <li data-phone="iphone6" class="active"><a href="javascript:void(0);">iPhone 6</a></li>
                <li data-phone="iphone5s"><a href="javascript:void(0);">iPhone 5S</a></li>
                <li data-phone="iphone5c"><a href="javascript:void(0);">iPhone 5C</a></li>
<?php /*                    <li data-phone="iphone4s"><a href="javascript:void(0);">iPhone 4S</a></li>*/ ?>
            </ul>
        </div>

    </div>
</div>

<script src="{{ url('/assets/js/custom/app.editor.js?v=' . Config::get('system.version')) }}"></script>
<script>
var sl = '{{ $sl }}';
var local_domain = '{{ $app->local_domain }}';

/* Set layout */
$('#app-layouts li[data-layout="{{ $app->layout }}"]').addClass('active');

$('#btnDuplicateApp').on('click', function() {

	var name = prompt("{{ trans('global.enter_name_new_app') }}", "{{ trans('global.duplicate_app_name', ['name' => str_replace('"','', $app->name)]) }}");

	if (name != null) {

		blockUI();
        var request = $.ajax({
          url: "{{ url('/api/v1/app/duplicate') }}",
          type: 'POST',
          data: {
			  sl: "{{ $sl }}",
			  name: name
		  },
          dataType: 'json'
        });

        request.done(function(json) {

            /* Increment count */
            var count = parseInt($('#count_apps').text());
            $('#count_apps').text(count+1);

            /* Open app */
            document.location = '#/app/' + json.sl;
            unblockUI();
        });

        request.fail(function(jqXHR, textStatus) {
            alert('Request failed, please try again (' + textStatus, ')');
            unblockUI();
        });
		
	}

});

$('#btnDeleteApp').on('click', function() {
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
          data: {data : "{{ $sl }}"},
          dataType: 'json'
        });

        request.done(function(json) {

            /* Decrement count */
            var count = parseInt($('#count_apps').text());
            $('#count_apps').text(count-1);

            /* Open overview */
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

$('#bs-x-campaign').editable({
	url: app_root + '/api/v1/app-edit/app-campaign',
    ajaxOptions: {
        type: 'post'
    },
	success: function(response, newValue) {
        if(response.status == 'error') return response.msg;
		showSaved();
    },
	source: [
<?php
$site_campaigns = '';
foreach($campaigns as $campaign) {
	$site_campaigns .= '{value: ' . $campaign->id . ', text: "' . $campaign->name . '"},';
}
$site_campaigns = trim($site_campaigns, ',');
echo $site_campaigns;
?>
	]
});
</script> 
@stop