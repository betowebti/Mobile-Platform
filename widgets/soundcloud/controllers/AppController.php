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
		$soundcloud = \Mobile\Controller\WidgetController::getData($page, 'soundcloud');
		$soundcloud = json_decode($soundcloud);
		$soundclouds = array();

		if($soundcloud != NULL)
		{
			$i = 0;

			foreach($soundcloud->url as $row)
			{
				$url = $soundcloud->url[$i];

				$media = \Cache::remember('oembed' . md5($url), (60*24*7*4), function() use($url)
				{
					return \Embed\Embed::create($url);
				});

				if($media)
				{
					$soundclouds[] = $media;
				}
				$i++;
			}
		}

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'soundclouds' => $soundclouds
		]);
	}
}