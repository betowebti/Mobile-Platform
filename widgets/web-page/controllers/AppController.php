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
		$url = \Mobile\Controller\WidgetController::getData($page, 'url', '');
		$new_window = \Mobile\Controller\WidgetController::getData($page, 'new_window', '');
		$button_text = \Mobile\Controller\WidgetController::getData($page, 'button_text', '');

		if($button_text == '') $button_text = $url;

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'url' => $url,
			'new_window' => $new_window,
			'button_text' => $button_text
		]);
	}
}