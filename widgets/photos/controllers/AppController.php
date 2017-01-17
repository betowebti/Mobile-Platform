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
     * Get photos
     */
    public function getPhotos($app, $page)
    {
		$found = 0;
		$i = 0;
		$return = array();

		$images = \Mobile\Controller\WidgetController::getData($page, 'images', '');
		$images = json_decode($images);

		if($images != NULL)
		{
			foreach($images->image as $row)
			{
				$image = $images->image[$i];
				if($image != '')
				{
					$thumb = url('/api/v1/thumb/nail?w=150&h=150&img=' . $image);

					$return[] = [
						'src' => $image,
						'thumb' => $thumb,
						'sub' => ''
					];

					$i++;
				}
			}
		}

		$photos = [
			'found' => $i,
			'photos' => $return
		];

		return \Response::json($photos);
	}
}