<?php
namespace Widget\Controller;

/*
|--------------------------------------------------------------------------
| Widget app controller
|--------------------------------------------------------------------------
|
| App related logic
|
*/

class AppController extends \BaseController {

    /**
	 * Construct
     */
    public function __construct()
    {
    }

    /**
     * Main view
     */
    public function getIndex($app, $page)
    {
        $access_token = \Config::get('widget::app.access_token');
		$facebook_id = \Mobile\Controller\WidgetController::getData($page, 'facebook_id', '');
		$show_title = \Mobile\Controller\WidgetController::getData($page, 'show_title', '1');
		$limit = \Mobile\Controller\WidgetController::getData($page, 'limit', 10);

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'access_token' => $access_token,
			'facebook_id' => $facebook_id,
			'show_title' => $show_title,
			'limit' => $limit
		]);
	}
}