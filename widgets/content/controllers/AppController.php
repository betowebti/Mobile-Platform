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
		$image = \Mobile\Controller\WidgetController::getData($page, 'image');
		$content = \Mobile\Controller\WidgetController::getData($page, 'content', trans('widget::global.default_content'));
		$social_share = (boolean) \Mobile\Controller\WidgetController::getData($page, 'social_share', 1);

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'image' => $image,
			'content' => $content,
			'social_share' => $social_share
		]);
	}
}