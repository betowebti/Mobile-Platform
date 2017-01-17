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
		$hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';

        $events = \Mobile\Controller\WidgetController::getData($page, 'events[]', NULL);
        $events = (count($events) == 0) ? false : true;

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl,
			'events' => $events,
			'hashPrefix' => $hashPrefix
		]);
	}

    /**
     * Edit event
     */
    public function editEvent($app, $page)
    {
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));

        return \View::make('admin.edit-event')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl
		]);
	}

    /**
     * Get event
     */
    public function getEvent($app, $page, $id)
    {
		$hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';
		$found = false;
		$back = '#' . $hashPrefix . '/nav/' . $page->slug;
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));
        $event = \Mobile\Controller\WidgetController::getData($page, 'events[' . $id . ']', NULL);
		$social_share = (boolean) \Mobile\Controller\WidgetController::getData($page, 'social_share', 1);

		if($event != NULL)
		{
			$event = json_decode($event);
			$now = \Carbon::now();
			$event_start = \Carbon::parse($event->event_start)->timezone($app->timezone)->format('Y-m-d H:i:s');
			$event_end = ($event->event_end != '') ? \Carbon::parse($event->event_end)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59' : \Carbon::parse($event->event_start)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59';

            if(
                ($event_start >= $now && ($event_end >= $now || $event_end == ''))
                || ($event_start <= $now && $event_end >= $now)
            )
			{
				$event->id = $id;
				$found = true;
			}
		}

        return \View::make('app.event')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl,
			'found' => $found,
			'back' => $back,
			'event' => $event,
			'social_share' => $social_share
		]);
	}

    /**
     * Get events
     */
    public function getEvents($app, $page)
    {
		$found = 0;
		$return = array();
		$now = \Carbon::now();
		$events = \Mobile\Controller\WidgetController::getData($page, 'events[]', NULL);

		if($events != NULL)
		{
			foreach($events as $key => $event)
			{
				$event_start = \Carbon::parse($event->event_start)->timezone($app->timezone)->format('Y-m-d H:i:s');
				$event_end = ($event->event_end != '') ? \Carbon::parse($event->event_end)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59' : \Carbon::parse($event->event_start)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59';

				if(
                    ($event_start >= $now && ($event_end >= $now || $event_end == ''))
                    || ($event_start <= $now && $event_end >= $now)
                )
				{
					$event->id = $key;
					if ($event->image != '') $event->image = url($event->image);
					$return[strtotime($event_start) + $key] = $event;
					$found++;
				}
			}

            // Sort by date
            ksort($return);
            $return = array_values($return);
		}

		if($found == 0) $return = array('found' => 0);

		return \Response::json($return);
	}

}