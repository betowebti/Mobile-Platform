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
		$api_key = \Config::get('widget::api.api_key');
		$url = \Mobile\Controller\WidgetController::getData($page, 'url', url());
		$channel_url = \Mobile\Controller\WidgetController::getData($page, 'channel_url', trans('widget::global.default_channel'));
		$playlist_url = \Mobile\Controller\WidgetController::getData($page, 'playlist_url', trans('widget::global.default_playlist'));
		$tab = \Mobile\Controller\WidgetController::getData($page, 'tab', 'uploads');
		$columns = \Mobile\Controller\WidgetController::getData($page, 'columns', '1');
		$max_results = \Mobile\Controller\WidgetController::getData($page, 'max_results', '15');

        echo \View::make('widget::app.index')->with([
			'sl' => $sl,
			'app' => $app,
			'page' => $page,
			'api_key' => $api_key,
			'url' => $url,
			'channel_url' => $channel_url,
			'playlist_url' => $playlist_url,
			'tab' => $tab,
			'columns' => $columns,
			'max_results' => $max_results
		]);
	}

    /**
     * Get feed
     */
    public function getFeed($app, $page)
    {
		$cache_minutes = 1; //\Mobile\Controller\WidgetController::getData($page, 'cache', 1);
		$api_key = \Config::get('widget::api.api_key');
		$call = \Request::get('call');
/*
		$json = \Cache::remember('widget-youtube-' . $app->id . '-' . $page->id . '-' . $call, $cache_minutes, function() use($page, $call, $api_key)
		{
*/
			$url = '';

			switch ($call)
			{
				case 'profile':
					$channelId = \Request::get('channelId'); 
					$url = "https://www.googleapis.com/youtube/v3/channels?part=brandingSettings,snippet,statistics,contentDetails&id=" . $channelId . "&key=" . $api_key;
					break;
				case 'video-stat':
					$videoIdList = \Request::get('videoIdList'); 
					$url = "https://www.googleapis.com/youtube/v3/videos?part=statistics,contentDetails&id=" . $videoIdList . "&key=" . $api_key;
					break;
				case 'upload':
					$playlistId = \Request::get('playlistId'); 
					$maxResults = \Request::get('maxResults'); 
					$url = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=" . $playlistId . "&maxResults=" . $maxResults . "&key=" . $api_key;
					break;
				case 'ChannelPlaylists':
					$channelId = \Request::get('channelId'); 
					$maxResults = \Request::get('maxResults'); 
					$url = "https://www.googleapis.com/youtube/v3/playlists?part=contentDetails,snippet&channelId=" . $channelId . "&maxResults=" . $maxResults . "&key=" . $api_key;
					break;
				case 'id':
					$forUsername = \Request::get('forUsername'); 
					$url = "https://www.googleapis.com/youtube/v3/channels?part=id&forUsername=" . $forUsername . "&key=" . $api_key;
					break;
			}

			if ($url != '')
			{
				$curl_handle = curl_init();
				curl_setopt($curl_handle, CURLOPT_URL, $url);
				curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, false); //disable SSL check
				curl_setopt($curl_handle, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
				$response = curl_exec($curl_handle);
				curl_close($curl_handle);
	
				header('Content-Type: application/json');
				echo $response;
			}
//		});

		//return $json;
	}
}