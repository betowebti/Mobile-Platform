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
		$color = \Mobile\Controller\WidgetController::getData($page, 'color', 'light');
		$columns = \Mobile\Controller\WidgetController::getData($page, 'columns', 2);
		$shadow = \Mobile\Controller\WidgetController::getData($page, 'shadow', 'dark');
		$icon_size = \Mobile\Controller\WidgetController::getData($page, 'icon_size', 'l');
		$bg_color = \Mobile\Controller\WidgetController::getData($page, 'bg_color', 'dark');

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'image' => $image,
			'color' => $color,
			'columns' => $columns,
			'shadow' => $shadow,
			'icon_size' => $icon_size,
			'bg_color' => $bg_color
		]);
	}
}