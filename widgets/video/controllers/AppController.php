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
		$video = \Mobile\Controller\WidgetController::getData($page, 'video');
		$video = json_decode($video);
		$videos = array();

		if($video != NULL)
		{
			$i = 0;

			foreach($video->url as $row)
			{
				$url = $video->url[$i];

				$media = \Cache::remember('oembed' . md5($url), (60*24*7*4), function() use($url)
				{
					return \Embed\Embed::create($url);
				});

				if($media)
				{
					$videos[] = $media;
				}
				$i++;
			}
		}

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'videos' => $videos
		]);
	}
}