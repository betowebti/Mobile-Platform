<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	<meta name="HandheldFriendly" content="True">
	<meta name="MobileOptimized" content="320">
	<meta http-equiv="cleartype" content="on">

	<link rel="stylesheet" href="{{ url('/assets/css/mobile.css?v=' . Config::get('system.mobile_version')) }}" />
<?php
    if(! isset($css))
    {
?>
	<link rel="stylesheet" href="{{ url('themes/' . $theme . '/assets/css/style.css') }}" />
<?php
    }
    else
    {
        echo '<style type="text/css">' . $css . '</style>';
    }
?>
    <link rel="stylesheet" href="{{ url('themes/' . $theme . '/assets/css/custom.css') }}" />
	<script src="{{ url('/assets/js/mobile.js?v=' . Config::get('system.mobile_version')) }}"></script>
    <script>
angular.module('app', ['ionic'])

.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {

  });
})

.config(function($stateProvider, $urlRouterProvider) {

  // Ionic uses AngularUI Router which uses the concept of states
  // Learn more here: https://github.com/angular-ui/ui-router
  // Set up the various states which the app can be in.
  // Each state's controller can be found in controllers.js
  $stateProvider
    
    .state('page', {
      url: '',
      templateUrl: 'page.html'
    });

  // if none of the above states are matched, use this as the fallback
  $urlRouterProvider.otherwise('1');

});

</script>
<style type="text/css">
body,
ion-nav-view,
ion-nav-view .pane {
    background:url(<?php echo url('themes/' . $theme . '/assets/img/background-phone.png') ?>) no-repeat center;
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}
.header{
	line-height: 120px;height: 120px;  border-radius: 3px; text-align: center;
	border: 1px solid rgb(238, 238, 238);
}

</style>
</head>

<body ng-app="app" animation="slide-left-right-ios7">
<div>
    <div>
        <div class="bar bar-header bar-dark">
            <button class="button back-button buttons button-icon icon ion-arrow-left-c header-item"> <span class="back-text"></span></button>

            <h1 class="title"><?php echo ucwords($theme) ?></h1>
        </div>
        <ion-nav-view></ion-nav-view>
        <ion-tabs class="tabs-icon-top tabs-bottom tabs-standard">
            <ion-tab title="About Us" icon-off="ion-ios-information" icon-on="ion-ios-information" href="#/1"> </ion-tab>
            <ion-tab title="Agenda" icon-off="ion-calendar" icon-on="ion-calendar" href="#/2"> </ion-tab>
            <ion-tab title="Photos" icon-off="ion-images" icon-on="ion-images" href="#/3"> </ion-tab>
            <ion-tab title="Call Us" icon-off="ion-android-call" icon-on="ion-android-call" href="#/4"> </ion-tab>
            <ion-tab title="Route" icon-off="ion-location" icon-on="ion-location" href="#/5"> </ion-tab>
        </ion-tabs>

    </div>
</div>
<script id="page.html" type="text/ng-template">
<ion-view title="<?php echo ucwords($theme) ?>">
    <ion-content padding="true" class="has-header">
        <div class="button-bar"></div>

        <div class="header">
            <i class="icon ion-image" style="font-size: 76px; vertical-align: middle;"></i>
        </div>
		<br>

		<div class="transparent">
       		<h3><?php echo ucwords($theme) ?></h3>
			<p>Lorem ipsum dolor sit amet, <a href="#">consectetur adipiscing elit</a>. Nunc sit amet ex sed ex dignissim condimentum sit amet non erat. Cras gravida lectus nulla.</p>
		</div>
        <div class="list card" style="margin-bottom:0">
          <a href="#" class="item item-icon-left">
            <i class="icon ion-social-facebook"></i>
            Facebook page
          </a>
          <a href="#" class="item item-icon-left">
            <i class="icon ion-social-linkedin"></i>
            LinkedIn profile
          </a>
          <a href="#" class="item item-icon-left">
            <i class="icon ion-social-googleplus"></i>
            Google+ page
          </a>
        </div>
<?php /*
		<button class="button button-stable button-full">Stable button</button>
*/ ?>

		<div class="row">
		  <div class="col">
			<button class="button button-positive button-full">Positive button</button>
			<button class="button button-calm button-full">Calm button</button>
			<button class="button button-balanced button-full">Balanced button</button>
		  </div>
		  <div class="col">
			<button class="button button-energized button-full">Energized button</button>
			<button class="button button-assertive button-full">Assertive button</button>
			<button class="button button-royal button-full">Royal button</button>
		  </div>
		</div>
    </ion-content>
</ion-view></script>
  </body>
</html>