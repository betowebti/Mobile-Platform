<?php
namespace Mobile\Controller;

/*
 |--------------------------------------------------------------------------
 | Progressive Web App controller
 |--------------------------------------------------------------------------
 |
 | Progressive Web App related logic
 |
 */

class PwaController extends \BaseController {

  /**
   * sw.js - Service Worker
   */
  public function showServiceWorker($local_domain = NULL) {

    $local_domain = \Input::get('d', NULL);

    // Get app
    if ($local_domain == NULL) {
      // Check for custom user domain
      $url_parts = parse_url(\URL::current());
      $domain = str_replace('www.', '', $url_parts['host']);

      $app = \Mobile\Model\App::where('domain', '=', $domain)
        ->orWhere('domain', '=', 'www.' . $domain)
        ->first();
    } else {
      $app = \Mobile\Model\App::where('local_domain', '=', $local_domain)->first();
    }

    if(empty($app)) {
      return false;
    }

		$js = \View::make('user.app.js-service-worker', array(
			'app' => $app
		));

		// Remove first and last line
		$js = preg_replace('/^.*?\n|\S+\s*$/', '', $js);

		$response = \Response::make($js);
		$response->header('Content-Type', 'application/javascript');

		return $response;
  }

  /**
   * manifest.json
   */
  public function showManifest($local_domain = NULL) {
    // Get app
    if ($local_domain == NULL) {
      // Check for custom user domain
      $url_parts = parse_url(\URL::current());
      $domain = str_replace('www.', '', $url_parts['host']);

      $app = \Mobile\Model\App::where('domain', '=', $domain)
        ->orWhere('domain', '=', 'www.' . $domain)
        ->first();
    } else {
      $app = \Mobile\Model\App::where('local_domain', '=', $local_domain)->first();
    }

    if(empty($app)) {
      return false;
    }

    $slugify = new \Slugify();
    $app_name = $slugify->slugify($app->name);

    $app_icon = $app->icon(1024);
    $img_part = pathinfo($app_icon);

    $manifest = '{
  "short_name": "' . str_replace('"', '\"', $app->name) . '",
  "name": "' . str_replace('"', '\"', $app->name) . '",
  "icons": [
    {
      "src": "' . url(\App\Core\Thumb::nail($app_icon, '/uploads/user/' . \App\Core\Secure::staticHash($app->user_id) . '/.tmb/' . $app_name, 96, 96, 'crop')) . '",
      "sizes": "96x96",
      "type": "image/' . $img_part['extension'] . '"
    },
    {
      "src": "' . url(\App\Core\Thumb::nail($app_icon, '/uploads/user/' . \App\Core\Secure::staticHash($app->user_id) . '/.tmb/' . $app_name, 144, 144, 'crop')) . '",
      "sizes": "144x144",
      "type": "image/' . $img_part['extension'] . '"
    },
    {
      "src": "' . url(\App\Core\Thumb::nail($app_icon, '/uploads/user/' . \App\Core\Secure::staticHash($app->user_id) . '/.tmb/' . $app_name, 192, 192, 'crop')) . '",
      "sizes": "192x192",
      "type": "image/' . $img_part['extension'] . '"
    }
  ],
  "start_url": "' . $app->domain() . '?utm_source=web_app_manifest",
  "display": "standalone",
  "orientation": "portrait"
}';

    header("Content-Type: application/json;charset=utf-8");
    echo $manifest;
  }
}