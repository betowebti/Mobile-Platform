@extends('layouts.master')

@section('content')
		<nav class="navbar navbar-default navbar-fixed-top" id="main-navbar">
			<div class="container-fluid">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-navbar-collapse"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </button>
					<?php if ($logo != '') { ?><a class="navbar-brand" href="#home" onclick="$.scrollTo($('#home'), 800); return false;"><img src="{{ $logo }}" data-at2x="{{ $logo_retina }}"></a><?php } ?>
				</div>
				<div class="collapse navbar-collapse" id="main-navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a href="#home" onclick="$.scrollTo($('#home'), 800); return false;">{{ trans('website::global.txt_home') }}</a></li>
						<li><a href="#overview" onclick="$.scrollTo($('#overview').offset().top - 60, 800); return false;">{{ trans('website::global.txt_overview') }}</a></li>
						<li><a href="#features" onclick="$.scrollTo($('#features').offset().top - 60, 800); return false;">{{ trans('website::global.txt_features') }}</a></li>
						<li><a href="#pricing" onclick="$.scrollTo($('#pricing').offset().top - 60, 800); return false;">{{ trans('website::global.txt_pricing') }}</a></li>
						<li><a href="#contact" onclick="$.scrollTo($('#contact').offset().top - 60, 800); return false;">{{ trans('website::global.txt_contact') }}</a></li>
<?php if ($demo_mode == 1) { ?>
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="ion-gear-b"></i> <span class="caret"></span></a>
							<ul class="dropdown-menu mega-menu">
		
								<li class="mega-menu-column">
								<ul id="demo-color">
									<li class="nav-header">Main color</li>
									<li><i class="ion-ios-checkmark-empty"></i> <a href="#" data-class="scheme1"><div class="demo-color" style="background-color:#2196F3"></div><br style="clear:both"></a></li>
									<li><i class="icon-empty"></i> <a href="#" data-class="scheme2"><div class="demo-color" style="background-color:#009688;"></div><br style="clear:both"></a></li>
									<li><i class="icon-empty"></i> <a href="#" data-class="scheme3"><div class="demo-color" style="background-color:#F44336;"></div><br style="clear:both"></a></li>
									<li><i class="icon-empty"></i> <a href="#" data-class="scheme4"><div class="demo-color" style="background-color:#00BCD4;"></div><br style="clear:both"></a></li>
									<li><i class="icon-empty"></i> <a href="#" data-class="scheme5"><div class="demo-color" style="background-color:#607D8B;"></div><br style="clear:both"></a></li>
									<li><i class="icon-empty"></i> <a href="#" data-class="scheme6"><div class="demo-color" style="background-color:#795548;"></div><br style="clear:both"></a></li>
								</ul>
								</li>
			
								<li class="mega-menu-column">
								<ul id="demo-header">
									<li class="nav-header">Header image</li>
									<li><i class='ion-ios-checkmark-empty'></i> <a href="#" data-src="{{ url('website/lander/assets/images/headers/header01.jpg') }}">Header 01</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/headers/header02.jpg') }}">Header 02</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/headers/header03.jpg') }}">Header 03</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/headers/header04.jpg') }}">Header 04</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/headers/header05.jpg') }}">Header 05</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/headers/header06.jpg') }}">Header 06</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/headers/header07.jpg') }}">Header 07</a></li>
								</ul>
								</li>
			
								<li class="mega-menu-column">
								<ul id="demo-bg">
									<li class="nav-header">Section</li>
									<li><i class='ion-ios-checkmark-empty'></i> <a href="#" data-src="{{ url('website/lander/assets/images/backgrounds/triangles.png') }}">Triangles</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/backgrounds/hexagons.jpg') }}">Hexagons</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/backgrounds/gradient.jpg') }}">Gradient</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/backgrounds/bokeh.jpg') }}">Bokeh</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/backgrounds/bokeh2.jpg') }}">Bokeh 2</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-src="{{ url('website/lander/assets/images/backgrounds/waves.jpg') }}">Waves</a></li>
								</ul>
								</li> 
	
								<li class="mega-menu-column">
								<ul id="demo-phone"> 
									<li class="nav-header">Phone</li>
									<li><i class="icon-empty"></i> <a href="#" data-class="galaxy-s6">Galaxy S6 Edge</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-class="iphone6-space">iPhone 6 Space</a></li>
									<li><i class="icon-empty"></i> <a href="#" data-class="iphone6-silver">iPhone 6 Silver</a></li>
									<li><i class='ion-ios-checkmark-empty'></i> <a href="#" data-class="iphone6-gold">iPhone 6 Gold</a></li>
								</ul>
								</li> 
								
							</ul><!-- dropdown-menu -->

						</li>
<?php } ?>
					</ul>
					<ul class="nav navbar-nav navbar-right">
						<li class="divider-vertical"></li>
						<li><a href="{{ url('/login') }}"><i class="icon ion-locked"></i> {{ trans('website::global.txt_login') }}</a></li>
					</ul>
				</div>
				<!-- /.navbar-collapse -->
			</div>
			<!-- /.container-fluid -->
		</nav>
		<section class="full-height" id="home">
			<div class="row-fluid full-height">
				<div class="image-cover full-height parallax-window" data-parallax="scroll" data-image-src="{{ $header }}">
					<div class="color-overlay full-height vertical-xs-parent">
						<div class="full-height vertical-xs-child-center">
							<div class="container color-text-base-child">
								<div class="row">
									<div class="color-text-base-child col-xs-12 col-sm-10 col-md-6">
										<h1>{{ trans('website::global.txt_page_head') }}</h1>
									</div>
								</div>
								<div class="row">
									<div class="color-text-base-light col-xs-12 col-sm-10 col-md-6">
										<h3>{{ trans('website::global.area_page_subline') }}
										</h3>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-6 top-xs-offset-10 top-sm-offset-5">
										<a href="{{ url('/signup') }}" class="btn btn-primary btn-lg btn-xs-block btn-sm-inline-block margin-xs-r-0 margin-md-r-20 margin-sm-b-0 margin-xs-b-20">{{ trans('website::global.txt_try_now') }}</a> 
										<a class="btn btn-ghost btn-lg btn-xs-block btn-sm-inline-block" href="javascript:void(0);" onclick="$.scrollTo($('#overview').offset().top - 60, 800);"><i class="icon ion-ios-arrow-down pull-right"></i> {{ trans('website::global.txt_learn_more') }}</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Overview -->
		<section id="overview">
			<div class="bg-color-start-white">
				<div class="web-block">
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-xs-center">
								<h1>{{ trans('website::global.txt_launch_today') }}</h1>
							</div>
						</div>
						<div class="row margin-md-t-50">
							<div class="row-md-height">
								<div class="col-md-4 col-md-height col-middle col-xs-12 margin-xs-t-30 text-xs-center text-md-right animated vp" data-vp-add-class="fadeInLeft">
									<div>
										<p class="lead">{{ trans('website::global.rich_overview_headline') }}</p>
									</div>
								</div>
								<div class="col-md-4 col-md-height text-center col-sm-6 margin-xs-t-30 col-xs-12">

									<div class="swiper-container">
										<div class="swiper-wrapper">
											<div class="swiper-slide"><img src="website/lander/assets/images/slider/mobile_about_us.jpg" /></div>
											<div class="swiper-slide"><img src="website/lander/assets/images/slider/mobile_map.jpg" /></div>
											<div class="swiper-slide"><img src="website/lander/assets/images/slider/mobile_contact_us.jpg" /></div>
											<div class="swiper-slide"><img src="website/lander/assets/images/slider/mobile_ecommerce.jpg" /></div>
										</div>
									</div>
									<!-- Add Pagination -->
									<div class="swiper-pagination"></div>

								</div>
								<div class="col-md-4 col-md-height col-middle col-sm-6 margin-xs-t-30 col-xs-12 text-xs-center text-sm-left animated vp" data-vp-add-class="fadeInRight">
									<div>
										<h2><i class="icon ion-android-hand"></i> {{ trans('website::global.txt_overview1_title') }}</h2>
										<p>{{ trans('website::global.txt_overview1') }}</p>
										<h2><i class="icon ion-heart"></i> {{ trans('website::global.txt_overview2_title') }}</h2>
										<p>{{ trans('website::global.txt_overview2') }}</p>
										<h2><i class="icon ion-stats-bars"></i> {{ trans('website::global.txt_overview3_title') }}</h2>
										<p>{{ trans('website::global.txt_overview3') }}</p>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Feature -->
		<section id="features" class="parallax-window" data-parallax="scroll" data-image-src="{{ $section }}">
			<div class="bg-color-start-base">
				<div class="web-block">
					<div class="container">
						<div class="row">
							<div class="col-sm-6 text-center"> <img src="website/lander/assets/images/visuals/feature-left-qr-apps.png" class="img-resp animated vp" data-vp-add-class="fadeIn" /> </div>
							<div class="col-sm-6 animated vp" data-vp-add-class="fadeInRight">
								<div class="top-xs-offset-12">
									<h2>{{ trans('website::global.txt_feature1_title') }}</h2>
									<p class="lead">{{ trans('website::global.rich_feature1_txt') }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Feature -->
		<section>
			<div class="bg-color-start-white">
				<div class="web-block">
					<div class="container">
						<div class="row">
							<div class="col-sm-6 animated vp" data-vp-add-class="fadeInLeft">
								<div class="top-xs-offset-12 text-right">
									<h2>{{ trans('website::global.txt_feature2_title') }}</h2>
									<p class="lead">{{ trans('website::global.rich_feature2_txt') }}</p>
								</div>
							</div>
							<div class="col-sm-6 text-center"> <img src="website/lander/assets/images/visuals/feature-right-coupons.png" class="img-resp animated vp" data-vp-add-class="fadeIn" /> </div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Feature -->
		<section class="parallax-window" data-parallax="scroll" data-image-src="{{ $section }}">
			<div class="bg-color-start-base">
				<div class="web-block">
					<div class="container">
						<div class="row">
							<div class="col-sm-6 text-center"> <img src="website/lander/assets/images/visuals/feature-left-media.png" class="img-resp animated vp" data-vp-add-class="fadeIn" /> </div>
							<div class="col-sm-6 animated vp" data-vp-add-class="fadeInRight">
								<div class="top-xs-offset-12">
									<h2>{{ trans('website::global.txt_feature3_title') }}</h2>
									<p class="lead">{{ trans('website::global.rich_feature3_txt') }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Pricing -->
		<section id="pricing">
			<div class="bg-color-start-white">
				<div class="web-block">
					<div class="container">
						<div class="row">
							<div class="col-xs-12 text-xs-center margin-xs-b-40">
								<h1>{{ trans('website::global.txt_plan_title') }}</h1>
							</div>
						</div>
						<div class="row">
<?php
foreach ($plans as $plan)
{
	$settings = $plan->settings;
	if ($settings != '') $settings = json_decode($settings);

	$apps = (isset($settings->max_apps)) ? $settings->max_apps : 0;
	if ($apps == 0) $apps = trans('website::global.txt_unlimited');

    $plan_widgets = (isset($settings->widgets)) ? $settings->widgets : array();
    $plan_widgets_count = (isset($settings->widgets)) ? count($settings->widgets) : 0;
    if ($settings == '' || count($plan_widgets) == count($widgets)) $plan_widgets_count = trans('website::global.txt_all');

    $plan_support = (isset($settings->support)) ? $settings->support : '-';

	$monthly = (isset($settings->monthly)) ? $settings->monthly : 0;
	$annual = (isset($settings->annual)) ? $settings->annual : 0;
	$annual = str_replace('.00', '', number_format($annual * 12, 2));
	$currency = (isset($settings->currency)) ? $settings->currency : 'USD';
	$currencies = trans('currencies');
	$currency_symbol = $currencies[$currency][1];

	$featured = (isset($settings->featured) && $settings->featured == 1) ? 1 : 0;
?>
							<!-- Pricing table -->
							<div class="{{ $plans_class }}">
								<div class="plan<?php if ($featured == 1) echo ' featured'; ?>">
									<?php if ($featured == 1) echo '<div class="ribbon"><span>' . trans('website::global.txt_popular') . '</span></div>'; ?>
									<table class="table table-striped">
										<thead>
											<tr>
												<th colspan="2">
													<h3>{{ $plan->name }}</h3>
												</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="td-left">{{ trans('website::global.txt_monthly') }}</td>
												<td class="td-right">{{ $currency_symbol }}{{ $monthly }}</td>
											</tr>
											<tr>
												<td class="td-left">{{ trans('website::global.txt_annual') }}</td>
												<td class="td-right">{{ $currency_symbol }}{{ $annual }}</td>
											</tr>
											<tr>
												<td class="td-left">{{ trans('website::global.txt_apps') }}</td>
												<td class="td-right">{{ $apps }}</td>
											</tr>
											<tr>
												<td class="td-left">{{ trans('website::global.txt_widgets') }}</td>
												<td class="td-right">{{ $plan_widgets_count }} <a href="#" class="toggle-widgets">[?]</a></td>
											</tr>
<?php
foreach ($widgets as $widget_name => $widget)
{
	if ($widget['active'])
	{
		$included = (in_array($widget['dir'], $plan_widgets)) ? '<i class="icon ion-checkmark icon-active"></i>' : '<i class="icon ion-close icon-nonactive"></i>';
		if($settings == '') $included = '<i class="icon ion-checkmark icon-active"></i>';
		echo '<tr class="widget-details"><td class="td-left">' . $widget_name . '</td><td class="td-right">' . $included . '</td></tr>';
	}
}
?>
											<tr>
												<td class="td-left">{{ trans('website::global.txt_support') }}</td>
												<td class="td-right">{{ $plan_support }}</td>
											</tr>
											<tr>
												<td colspan="2">
													<a href="{{ url('/signup') }}" class="btn btn-success btn-block btn-lg">{{ trans('website::global.txt_sign_up') }} <i class="icon ion-android-checkmark-circle" ></i></a>
												</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
<?php 
} 
?>

						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Contact -->
		<section id="contact" class="parallax-window" data-parallax="scroll" data-image-src="{{ $section }}">
			<div class="bg-color-start-base color-text-base-child">
				<div class="web-block">
					<div class="container">
						<div class="row">
							<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-5 col-md-offset-0">
								<h2>{{ trans('website::global.txt_get_in_touch') }}</h2>
								<p>{{ trans('website::global.txt_contact_txt') }}</p>
								<div class="margin-xs-t-30">
									<form method="post" class="ajax" data-toggle="validator" id="frmContact" action="{{ url('/api/v1/website/post/lander/form') }}">
										<div style="display:none" class="success-msg">{{ trans('website::global.txt_contact_thanks') }}</div>
										<div class="form-group form-group-lg">
											<label for="name">{{ trans('website::global.txt_name') }}</label>
											<input type="text" class="form-control" id="name" name="name" required>
											<div class="help-block with-errors"></div>
										</div>
										<div class="form-group form-group-lg">
											<label for="email">{{ trans('website::global.txt_email') }}</label>
											<input type="email" class="form-control" id="email" name="email" required>
											<div class="help-block with-errors"></div>
										</div>
										<div class="form-group form-group-lg">
											<label for="message">{{ trans('website::global.txt_message') }}</label>
											<textarea class="form-control" rows="4" id="message" name="message" required></textarea>
											<div class="help-block with-errors"></div>
										</div>
										<div class="form-group form-group-lg">
											<button type="submit" class="btn btn-primary btn-xs-block btn-lg btn-md-inline-block ladda-button" data-style="expand-right"><span class="ladda-label">{{ trans('website::global.txt_submit') }}</span></button>
										</div>
									</form>
								</div>
							</div>
							<div class="col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-1 margin-xs-t-40 margin-md-t-0">
								<h2>{{ trans('website::global.txt_iframe_title') }}</h2>
								<p><i class="icon ion-ios-location"></i> {{ trans('website::global.txt_iframe_txt') }}</p>
								<iframe class="thumbnail" width="100%" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src=
								"{{ trans('website::global.area_iframe_src') }}"
								></iframe>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Footer -->

		<section>
			<div class="bg-color-start-white">
				<div class="web-block web-block-slim">
					<div class="container">
						<div class="row margin-xs-t-40 margin-xs-b-10">
							<div class="col-xs-12 text-xs-center">
								<img class="img-resp" src="{{ $logo_footer }}" data-at2x="{{ $logo_footer_retina }}">
							</div>
							<div class="col-xs-12 text-xs-center">
								<div class="footer-links margin-xs-t-20 margin-xs-b-20">
									<a href="#home" onclick="$.scrollTo($('#home'), 800); return false;">{{ trans('website::global.txt_home') }}</a>
									<a href="#overview" onclick="$.scrollTo($('#overview').offset().top - 60, 800); return false;">{{ trans('website::global.txt_overview') }}</a>
									<a href="#features" onclick="$.scrollTo($('#features').offset().top - 60, 800); return false;">{{ trans('website::global.txt_features') }}</a>
									<a href="#pricing" onclick="$.scrollTo($('#pricing').offset().top - 60, 800); return false;">{{ trans('website::global.txt_pricing') }}</a>
									<a href="#contact" onclick="$.scrollTo($('#contact').offset().top - 60, 800); return false;">{{ trans('website::global.txt_contact') }}</a>
								</div>
							</div>
						</div>
					</div>
					<div class="parallax-window" data-parallax="scroll" data-image-src="{{ $section }}">
						<div class="bg-color-start-base-dark color-text-base-child">
							<div class="container">
								<div class="row">
									<div class="col-xs-12 text-xs-center margin-xs-t-30 margin-xs-b-40">
										<h3>{{ trans('website::global.txt_footer_title') }}</h3>
										<small>{{ trans('website::global.txt_footer') }}</small>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
@stop