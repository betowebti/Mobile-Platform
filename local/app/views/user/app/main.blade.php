<!doctype html>
<html ng-app="ngApp" lang="{{ $app->language }}">
<head>
  <title><?php echo $app->name ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">
  <meta name="apple-mobile-web-app-title" content="<?php echo str_replace('"', '&quot;', $app->name) ?>">
  <meta http-equiv="cleartype" content="on">

  <link rel="manifest" href="{{ $app->domain() }}/manifest.json">
<?php /*  <script src="{{ url('sw.js?d=' . $local_domain) }}"></script>*/ ?>

  <link rel="apple-touch-icon-precomposed" href="{{ $app->icon(76) }}">
  <link rel="apple-touch-icon-precomposed" sizes="76x76" href="{{ $app->icon(76) }}">
  <link rel="apple-touch-icon-precomposed" sizes="120x120" href="{{ $app->icon(120) }}">
  <link rel="apple-touch-icon-precomposed" sizes="152x152" href="{{ $app->icon(152) }}">

  <link rel="stylesheet" href="{{ url('/assets/css/mobile.css') }}" />
  <link rel="stylesheet" href="{{ url('themes/' . $app->theme . '/assets/css/style.css') }}" />
  <link rel="stylesheet" href="{{ url('themes/' . $app->theme . '/assets/css/custom.css') }}" />
  <link rel="stylesheet" href="{{ url('/api/v1/app-asset/style/' . $local_domain) }}" />
  <link href="{{ url('/api/v1/mobile/global-css/' . $app_hash) }}" rel="stylesheet" type="text/css">

  <script src="{{ url('/assets/js/mobile.js') }}"></script>
  <script src="{{ url('/api/v1/app-asset/app/' . $local_domain) }}"></script>
  <script src="{{ url('/api/v1/app-asset/controllers/' . $local_domain) }}"></script>
  <script src="{{ url('/api/v1/app-asset/services/' . $local_domain) }}"></script>
  <script src="{{ url('/api/v1/mobile/global-js/' . $app_hash) }}" type="text/javascript"></script>
  <script src="{{ url('/api/v1/app-track/app/' . $app_hash) }}" type="text/javascript" async></script>
<?php
// widgets head includes
foreach($app->appPages as $page)
{
  $head_include = public_path() . '/widgets/' . $page->widget . '/views/app/head.blade.php';

  if(\File::exists($head_include))
  {
    // Load widget, namespace views, translation and config
    $widgetName = camel_case($page->widget);
    $widget_dir = public_path() . '/widgets/' . $page->widget;
    \View::addLocation($widget_dir . '/views');
    \View::addNamespace('widget' . $widgetName, $widget_dir . '/views');
    \Lang::addNamespace('widget' . $widgetName, $widget_dir . '/lang');
    \Config::addNamespace('widget' . $widgetName, $widget_dir . '/config');

    echo \View::make('widget' . $widgetName . '::app.head', array(
      'app' => $app,
      'page' => $page
    ));
  }
  \View::share('head_included_' . camel_case($page->widget), true);
}

$settings = json_decode($app->settings);
$head_tag = (isset($settings->head_tag)) ? $settings->head_tag : '';

if($head_tag != '')
{
  echo '<script>' . $head_tag . '</script>';
}
?>
</head>

<body ng-controller="MainCtrl" ng-class="bodyClass">

  <ion-nav-bar class="bar-dark">
    <ion-nav-back-button class="button-icon icon ion-arrow-left-c">
    </ion-nav-back-button>
  </ion-nav-bar>

  <ion-nav-view></ion-nav-view>
<?php
$end_of_body_tag = (isset($settings->end_of_body_tag)) ? $settings->end_of_body_tag : '';

if($end_of_body_tag != '')
{
  echo '<script>' . $end_of_body_tag . '</script>';
}
?>
</body>
</html>