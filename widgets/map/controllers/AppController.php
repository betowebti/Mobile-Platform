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
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl
		]);
	}

    /**
     * Geocode address
     */
    public function getGeocode($app, $page)
    {
        $address = \Input::get('address', '');

		if($address != '')
		{
			$geo = \App\Core\Geo::geocode($address);

			if(isset($geo['error']))
			{
				$return = array('result' => 'error');
			}
			else
			{
				$return = array('result' => 'success', 'geo' => $geo->toArray());
			}
		}
		else
		{
			$return = array('result' => 'error');
		}

		return \Response::json($return);
	}

    /**
     * Get markers
     */
    public function getMarkers($app, $page)
    {
		$longitude = \Mobile\Controller\WidgetController::getData($page, 'longitude', '');
		$latitude = \Mobile\Controller\WidgetController::getData($page, 'latitude', '');
		$zoom = \Mobile\Controller\WidgetController::getData($page, 'zoom', '');
		$open_marker = \Mobile\Controller\WidgetController::getData($page, 'open_marker', 1);
		$marker = \Mobile\Controller\WidgetController::getData($page, 'marker', '');
        if($marker == '') $open_marker = 0;
        if($marker == '') $marker = NULL;
		$icon = \Mobile\Controller\WidgetController::getData($page, 'icon', '');
		$custom_icon = \Mobile\Controller\WidgetController::getData($page, 'custom_icon', '');
        if($custom_icon != '') $icon = url($custom_icon);

        if($icon != '')
        {
           list($icon_width, $icon_height) = getimagesize($icon);
        }
        else
        {
            $icon = NULL;
            $icon_width = 1;
            $icon_height = 1;
        }

		$markers = array(
			array(
				'longitude' => $longitude,
				'latitude' => $latitude,
				'zoom' => $zoom,
				'open_marker' => (boolean)$open_marker,
				'marker' => $marker,
				'iconUrl' => $icon,
				'iconSize' => array($icon_width, $icon_height),
				'iconAnchor' => array($icon_width / 2, $icon_height),
				'popupAnchor' => array($icon_width / 2, -$icon_height)
			)
		);

		return \Response::json($markers);
	}
}