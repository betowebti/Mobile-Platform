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
		$valid = \Mobile\Controller\WidgetController::getData($page, 'valid', 0);
		$error = ($valid == 0) ? trans('widget::global.no_feed_configured') : false;

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'error' => $error,
			'sl' => $sl
		]);
	}

    /**
     * Get feed
     */
    public function getFeed($app, $page)
    {
		$url = \Mobile\Controller\WidgetController::getData($page, 'rss_feed', '');
		$valid = \Mobile\Controller\WidgetController::getData($page, 'valid', 0);
		$feed_title = '';
		$feed_desc = '';
		$error = '';
		$items = array();
		$success = false;

		if($url != '' && $valid == 1)
		{
			// Must instantiate SimplePie object first to avoid this error when running PHPUnit tests:
			// "Use of undefined constant SIMPLEPIE_FILE_SOURCE_NONE - assumed 'SIMPLEPIE_FILE_SOURCE_NONE'"
			$feed = new \SimplePie();
			$feed->set_cache_location(app_path() . '/storage/cache');
			$file = new \SimplePie_File($url);
			$body = $file->body;

			//$feed->set_file($file);
			$feed->set_raw_data($body);

			$feed->force_feed(true);
			//$feed->set_feed_url($url);
			$feed->enable_cache(true);
			$feed->set_cache_location('cache');
			//$feed->handle_content_type();
			//$feed->set_input_encoding('UTF-8');
			$success = $feed->init();
			$feed->handle_content_type();

			if ($feed->error())
			{
				$error = htmlspecialchars($feed->error());
			}
			elseif ($success)
			{
				$feed_title = $feed->get_title();
				$feed_desc = $feed->get_description();

				$i=0;
				foreach($feed->get_items() as $item)
				{ 
					$image = '';
					if($enclosure = $item->get_enclosure())
					{
						if(($enclosure->get_link()) && (
								$enclosure->get_type() == 'image/gif' || 
								$enclosure->get_type() == 'image/png' || 
								$enclosure->get_type() == 'image/jpeg' || 
								$enclosure->get_type() == 'image/bmp'))
						{
							$image = $enclosure->get_link();
						}
					}
					$permalink = ($item->get_permalink()) ? $item->get_permalink() : '';

					$items[] = array(
						'permalink' => $permalink,
						'title' => html_entity_decode($item->get_title()),
						'content' => trim(html_entity_decode(strip_tags($item->get_content()))),
						'date' => $item->get_date(),
						'image' => $image
					);
				}
			}
		}

		$json = array(
			'success' => $success,
			'error' => $error,
			'title' => html_entity_decode($feed_title),
			'desc' => $feed_desc,
			'items' => $items
		);

		return \Response::json($json);
	}
}