<?php
// Extract all JS and CSS from widgets
$html = '';
$urls = array();
$cache_urls = array();

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

    $html .= \View::make('widget' . $widgetName . '::app.head', array(
      'app' => $app,
      'page' => $page
    ))->render();

  }

  \View::share('head_included_' . camel_case($page->widget), true);
}

$dom = new DOMDocument();
$dom->loadHTML($html);

// Extract CSS links
$tags = $dom->getElementsByTagName('link');
foreach ($tags as $tag) {
  $urls[] =  $tag->getAttribute('href');
}

// Extract JS links
$tags = $dom->getElementsByTagName('script');
foreach ($tags as $tag) {
  $urls[] =  $tag->getAttribute('src');
}

// Make urls absolute and remove external links
foreach ($urls as $url) {
  $url = str_replace(\Request::root(), '', $url);
  if (! starts_with($url, 'http')) $cache_urls[] = $url;
}


?><!-- skipmin --><script>
'use strict';

self.addEventListener('install', event => {
  function onInstall () {
    return caches.open('static')
      .then(cache =>
        cache.addAll([
          '/assets/css/mobile.css',
          '/themes/other/assets/css/style.css',
          '/themes/other/assets/css/custom.css',
          '/assets/js/mobile.js',
<?php
foreach ($cache_urls as $url) {
  echo "          '" . $url . "'," . PHP_EOL;
}
?>

          '/'
        ])
      );
  }

  event.waitUntil(onInstall(event));
});

self.addEventListener('activate', event => {

});

</script>{{--skipmin--}}