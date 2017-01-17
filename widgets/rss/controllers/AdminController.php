<?php
namespace Widget\Controller;

/*
|--------------------------------------------------------------------------
| Widget admin controller
|--------------------------------------------------------------------------
|
| Admin editor related logic
|
*/

class AdminController extends \BaseController {

    /**
	 * Construct
     */
    public function __construct()
    {
    }

    /**
     * Content tab
     */
    public function getContent($app, $page)
    {
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));

        return \View::make('admin.content')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl
		]);
	}

    /**
     * Parse url
     */
    public function parseUrl($app, $page)
    {
		$valid = 0;
		$url = \Input::get('url');
		$msg = trans('widget::global.no_feed_found');

		$feed = new \SimplePie();
		$feed->set_cache_location(app_path() . '/storage/cache');
		$feed->set_feed_url($url);
		$feed->enable_cache(true);
		$feed->init();
		$feed->handle_content_type();

		if($feed->data) {
			$valid = 1;
			$url = $feed->subscribe_url();
			$msg = trans('widget::global.feed_found');
		}

		return \Response::json(array('url' => $url, 'valid' => $valid, 'msg' => $msg));
	}
}