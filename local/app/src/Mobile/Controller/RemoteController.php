<?php
namespace Mobile\Controller;

/*
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|- DEPRECATED - also exists in routes.php
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
| Remote controller
|--------------------------------------------------------------------------
|
| Remote app api controller
|
*/

class RemoteController extends \BaseController {

    /**
     * Get handshake from hybrid / native app from POST request
     */
	public function postHandshake()
	{
		$url = \Request::get('url');
		$url_parts = parse_url($url);

		// Check for custom user domain
		$domain = str_replace('www.', '', $url_parts['host']);

		$app = \Mobile\Model\App::where('domain', '=', $domain)
			->orWhere('domain', '=', 'www.' . $domain)
			->first();

		if(empty($app))
		{
			// Check if domain is local
			$local_domain = explode('/', $url_parts['path']);
			$local_domain = end($local_domain);

			$app = \Mobile\Model\App::where('local_domain', '=', $local_domain)->first();

			if(empty($app))
			{
				$response = array(
					'app' => [
						'title' => 'Not found'
					]
				);

				return \Response::json($response);
			}

		}

		//\Log::info('Log message', array('local_domain' => $local_domain));

		$response = \Mobile\Controller\RemoteController::getScenarioBoards($app->scenarioBoards, $app, $url);

		return \Response::json($response);
	}

    /**
     * Get handshake JSON from hybrid / native app from GET request for development purposes
     */
	public function getHandshake()
	{
		$sl = \Request::input('sl', '');

		if($sl != '')
		{
			if(\Auth::check())
			{
				$parent_user_id = (\Auth::user()->parent_id == NULL) ? \Auth::user()->id : \Auth::user()->parent_id;
			}
			else
			{
				die();
			}

			$qs = \App\Core\Secure::string2array($sl);
			$scenario_board = \Beacon\Model\ScenarioBoard::where('user_id', '=', $parent_user_id)->where('id', '=', $qs['scenario_board_id'])->get();
		
			$response = \Mobile\Controller\RemoteController::getScenarioBoards($scenario_board);

			//header('Content-Type: application/json');
			return \Response::json($response);
		}
	}

	public static function getScenarioBoards($scenarioBoards, $app = NULL, $url = NULL)
	{
		$found_geofences = [];
		$found_beacons = [];
		$count_i = 0;
		$board_info = [];
		$available_geofences = [];
		$available_beacons = [];
		$available_scenarios = [];

		foreach ($scenarioBoards as $scenarioBoard)
		{
			$scenarios = $scenarioBoard->scenarios;
			foreach ($scenarios as $scenario)
			{
				$scenario_beacons = [];
				$beacons = $scenario->beacons;

				foreach ($beacons as $beacon)
				{
					if ($beacon->active == 1 && ! in_array($beacon->id, $scenario_beacons))
					{
						array_push($scenario_beacons, $beacon->id);
					}

					if ($beacon->active == 1 && ! in_array($beacon->uuid, $found_beacons))
					{
						$available_beacons[$count_i] = array(
							'id' => $beacon->id,
							'identifier' => $beacon->name,
							'uuid' => $beacon->uuid,
							'major' => $beacon->major,
							'minor' => $beacon->minor
						);
						array_push($found_beacons, $beacon->uuid);
						$count_i++;
					}
				}

				$scenario_geofences = [];
				$geofences = $scenario->geofences;

				foreach ($geofences as $geofence)
				{
					if ($geofence->active == 1 && ! in_array($geofence->id, $scenario_geofences))
					{
						array_push($scenario_geofences, $geofence->id);
					}

					if ($geofence->active == 1 && ! in_array($geofence->uuid, $found_geofences))
					{
						$available_geofences[$count_i] = array(
							'id' => $geofence->id,
							'identifier' => $geofence->name,
							'lat' => $geofence->lat,
							'lng' => $geofence->lng,
							'radius' => $geofence->radius
						);
						array_push($found_geofences, $geofence->uuid);
						$count_i++;
					}
				}

				// Check if scenario has (valid) output
				$scenario_has_output = true;

				switch ($scenario->scenario_then_id)
				{
					// show_image
					case 2: if ($scenario->show_image == '') $scenario_has_output = false; break;
					// show_template
					case 3: if ($scenario->template == NULL) $scenario_has_output = false; break;
					// open_url
					case 4: if ($scenario->open_url == '') $scenario_has_output = false; break;
					// play_video
					case 5: if ($scenario->play_video == '') $scenario_has_output = false; break;
					// play_sound
					case 6: if ($scenario->play_sound == '') $scenario_has_output = false; break;
					// reward_points
					case 10: if ($scenario->add_points == '') $scenario_has_output = false; break;
					// withdraw_points
					case 11: if ($scenario->substract_points == '') $scenario_has_output = false; break;
				}

				if ($scenario_has_output && $scenario->active == 1 && $scenario->scenario_then_id != NULL && (! empty($scenario_beacons) || ! empty($scenario_geofences)))
				{
					$template = ($scenario->template != NULL) ? url('/api/v1/app-remote/template/' . \App\Core\Secure::array2string(array('scenario_id' => $scenario->id))) : NULL;

					$available_scenarios[] = array(
						'id' => $scenario->id,
						'scenario_if_id' => $scenario->scenario_if_id,
						'scenario_then_id' => $scenario->scenario_then_id,
						'scenario_day_id' => $scenario->scenario_day_id,
						'scenario_time_id' => $scenario->scenario_time_id,
						'time_start' => $scenario->time_start,
						'time_end' => $scenario->time_end,
						'date_start' => $scenario->date_start,
						'date_end' => $scenario->date_end,
						'frequency' => $scenario->frequency,
						'delay' => $scenario->delay,
						'notification' => $scenario->notification,
						'show_image' => $scenario->show_image,
						'template' => $template,
						'open_url' => $scenario->open_url,
						'play_sound' => $scenario->play_sound,
						'play_video' => $scenario->play_video,
						'add_points' => $scenario->add_points,
						'substract_points' => $scenario->substract_points,
						'settings' => $scenario->settings,
						'geofences' => $scenario_geofences,
						'beacons' => $scenario_beacons
					);
				}
			}

			/* Board info
			 * If there're multiple boards attached to an app, those boards can
			 * have different timezones. However, one location can't be in more 
			 * than one timezone. So, only the timezone of the last board is used.
			 */

			$board['timezone'] = $scenarioBoard->timezone;
		}

		$response = array(
			'board' => $board,
			'geofences' => $available_geofences,
			'beacons' => $available_beacons,
			'scenarios' => $available_scenarios
		);

		if ($app !== NULL)
		{
			$response['app'] = [
				'name' => $app->name,
				'url' => $url
			];
		}

		return $response;
	}
}