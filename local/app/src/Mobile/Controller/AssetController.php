<?php
namespace Mobile\Controller;

/*
|--------------------------------------------------------------------------
| Asset controller
|--------------------------------------------------------------------------
|
| Asset related logic
|
*/

class AssetController extends \BaseController {

  /**
   * Construct
   */
  public function __construct()
  {
    if(\Auth::check())
    {
      $this->parent_user_id = (\Auth::user()->parent_id == NULL) ? \Auth::user()->id : \Auth::user()->parent_id;
    }
    else
    {
      $this->parent_user_id = NULL;
    }
  }

  /**
   * style.css
   */
  public function getStyle($local_domain)
  {
    $app = \Mobile\Model\App::where('local_domain', $local_domain)->first();

    $css = \View::make('user.app.css-style', array(
      'app' => $app
    ));

    // Remove first and last line
    $css = preg_replace('/^.*?\n|\S+\s*$/', '', $css);

    $response = \Response::make($css);
    $response->header('Content-Type', 'text/css');

    return $response;
  }

  /**
   * app.js
   */
  public function getApp($local_domain)
  {
    $app = \Mobile\Model\App::where('local_domain', $local_domain)->first();

    $js = \View::make('user.app.js-app', array(
      'app' => $app
    ));

    // Remove first and last line
    $js = preg_replace('/^.*?\n|\S+\s*$/', '', $js);

    $response = \Response::make($js);
    $response->header('Content-Type', 'application/javascript');

    return $response;
  }

  /**
   * controllers.js
   */
  public function getControllers($local_domain)
  {
    $app = \Mobile\Model\App::where('local_domain', $local_domain)->first();

    $js = \View::make('user.app.js-controllers', array(
      'app' => $app
    ));

    // Remove first and last line
    $js = preg_replace('/^.*?\n|\S+\s*$/', '', $js);

    $response = \Response::make($js);
    $response->header('Content-Type', 'application/javascript');

    return $response;
  }

  /**
   * services.js
   */
  public function getServices($local_domain)
  {
    $app = \Mobile\Model\App::where('local_domain', $local_domain)->first();

    $js = \View::make('user.app.js-services', array(
      'app' => $app
    ));

    // Remove first and last line
    $js = preg_replace('/^.*?\n|\S+\s*$/', '', $js);

    $response = \Response::make($js);
    $response->header('Content-Type', 'application/javascript');

    return $response;
  }
}