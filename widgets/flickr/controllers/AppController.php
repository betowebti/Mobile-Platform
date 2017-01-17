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
		$type = \Mobile\Controller\WidgetController::getData($page, 'type', 'tags');
		$tag = \Mobile\Controller\WidgetController::getData($page, 'tag', '');

		$ngInit = ($tag != '') ? "show('" . str_replace("'", "\'", $tag) . "', '" . $type . "')" : '';

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'ngInit' => $ngInit
		]);
	}
}