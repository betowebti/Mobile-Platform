<?php
namespace App\Controller;

use View, Config, Cache, App;

/*
|--------------------------------------------------------------------------
| Dashboard controller
|--------------------------------------------------------------------------
|
| Dashboard related logic
|
*/

class DashboardController extends \BaseController {

    /**
     * The layout that should be used for responses.
     */
    protected $layout = 'app.layouts.backend';

    /**
     * Instantiate a new instance.
     */
    public function __construct()
    {
		if(\Auth::check())
		{
			$this->parent_user_id = (\Auth::user()->parent_id == NULL) ? \Auth::user()->id : \Auth::user()->parent_id;
		}
		else
		{
			$this->parent_user_id = NULL;
		}
    }

    /**
     * Show main dashboard
     */
    public function getMainDashboard()
    {
		$hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';
		$cms_title = \App\Core\Settings::get('cms_title', trans('global.app_title'));
		$cms_logo = \App\Core\Settings::get('cms_logo', url('assets/images/interface/logo/icon.png'));
        $username = (\Auth::user()->first_name != '' || \Auth::user()->last_name != '') ? \Auth::user()->first_name . ' ' . \Auth::user()->last_name : \Auth::user()->username;

		if($this->parent_user_id != NULL && \Auth::user()->getRoleId() == 4)
		{
			$user_settings = json_decode(\Auth::user()->settings);
			$app_permissions = (isset($user_settings->app_permissions)) ? $user_settings->app_permissions : array();
			$count_apps = count($app_permissions);
		}
		else
		{
			$count_apps = \Mobile\Model\App::where('user_id', '=', $this->parent_user_id)->count();
		}

        View::share('username', $username);
        View::share('count_apps', $count_apps);
        View::share('hashPrefix', $hashPrefix);
        View::share('cms_title', $cms_title);
        View::share('cms_logo', $cms_logo);

        $this->layout->content = View::make('app.loader');
    }

    /**
     * Show dashboard partial
     */
    public function getDashboard()
    {
        $username = (\Auth::user()->first_name != '' || \Auth::user()->last_name != '') ? \Auth::user()->first_name . ' ' . \Auth::user()->last_name : \Auth::user()->username;

		// Security link
		$sl = \Input::get('sl', '');

		// Range
		$date_start = \Input::get('start', date('Y-m-d', strtotime(' - 30 day')));
		$date_end = \Input::get('end', date('Y-m-d'));

		// All apps, joined with campaigns
		if($this->parent_user_id != NULL && \Auth::user()->getRoleId() == 4)
		{
			$user_settings = json_decode(\Auth::user()->settings);
			$app_permissions = (isset($user_settings->app_permissions)) ? $user_settings->app_permissions : array();

			$apps = \Mobile\Model\App::where('apps.user_id', '=', $this->parent_user_id)
				->leftJoin('campaigns as c', 'apps.campaign_id', '=', 'c.id')
				->select(array('apps.*', 'c.name as campaign_name'))
				->whereIn('apps.id', $app_permissions)
				->orderBy('campaign_name', 'asc')
				->orderBy('apps.name', 'asc')
				->get();
		}
		else
		{
			$apps = \Mobile\Model\App::where('apps.user_id', '=', $this->parent_user_id)
				->leftJoin('campaigns as c', 'apps.campaign_id', '=', 'c.id')
				->select(array('apps.*', 'c.name as campaign_name'))
				->orderBy('campaign_name', 'asc')
				->orderBy('apps.name', 'asc')
				->get();
		}

		$apps_array = array();
		$apps_array_ids = array();
		$app_pages_array = array();
		foreach($apps as $app)
		{
			$apps_array[] = $app;
			$apps_array_ids[] = $app->id;
			$app_pages_array = array_merge($app_pages_array, $app->appPages->toArray());
		}

		$first_created = false;
		$campaigns = array();

		if(count($apps) > 0)
		{
			foreach($apps as $app)
			{
				$campaigns[$app->campaign_name][$app->name] = $app;
				$app_created = $app->created_at->timezone(\Auth::user()->timezone)->format("Y-m-d");
				if($app_created < $first_created || $first_created == false) $first_created = $app_created;
			}
		}

		// Selected app
		$app_created = false;
		$campaign = false;
		$campaign_apps = false;
		$app = false;

		if($sl != '')
		{
			$first_created = false;
			$apps_array = array();
			$apps_array_ids = array();
			$app_pages_array = array();

			$qs = \App\Core\Secure::string2array($sl);

			if(isset($qs['campaign_id']))
			{
				$campaign = \Campaign\Model\Campaign::where('id', '=', $qs['campaign_id'])
					->where('user_id', '=', $this->parent_user_id)
					->first();

				if($this->parent_user_id != NULL && \Auth::user()->getRoleId() == 4)
				{
					$campaign_apps = \Mobile\Model\App::where('campaign_id', '=', $qs['campaign_id'])
						->where('user_id', '=', $this->parent_user_id)
						->whereIn('id', $app_permissions)
						->get();
				}
				else
				{
					$campaign_apps = \Mobile\Model\App::where('campaign_id', '=', $qs['campaign_id'])
						->where('user_id', '=', $this->parent_user_id)
						->get();
				}

				foreach($campaign_apps as $campaign_app)
				{
					$campaign_app_created = $campaign_app->created_at->timezone(\Auth::user()->timezone)->format("Y-m-d");
					if($campaign_app_created < $first_created || $first_created == false) $first_created = $campaign_app_created;
					$apps_array[] = $campaign_app;
					$apps_array_ids[] = $campaign_app->id;
					$app_pages_array = array_merge($app_pages_array, $campaign_app->appPages->toArray());
				}
			}
			elseif($qs['app_id'])
			{
				$app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();
				$app_created = $app->created_at->timezone(\Auth::user()->timezone)->format("Y-m-d");
				if($app_created < $first_created || $first_created == false) $first_created = $app_created;

				$campaign = \Campaign\Model\Campaign::where('id', '=', $app->campaign_id)
					->where('user_id', '=', $this->parent_user_id)
					->first();

				$apps_array[] = $app;
				$apps_array_ids[] = $app->id;
				$app_pages_array = array_merge($app_pages_array, $app->appPages->toArray());
			}
		}

		// Load widgets to check permissions
		$plan_id = (\Auth::user()->parent_id == NULL) ? \Auth::user()->plan_id : \User::find(\Auth::user()->parent_id)->plan_id;
		$plan = \App\Model\Plan::where('id', $plan_id)->first();
		$plan_settings = json_decode($plan->settings);
		$plan_widgets = (isset($plan_settings->widgets)) ? $plan_settings->widgets : [];

		$widget = \Mobile\Controller\WidgetController::loadWidgetConfig('forms');
		$widget_allow['forms'] = (($widget['active'] && in_array($widget['dir'], $plan_widgets)) || ! isset($plan_settings->widgets)) ? true : false;

		$widget = \Mobile\Controller\WidgetController::loadWidgetConfig('coupons');
		$widget_allow['coupons'] = (($widget['active'] && in_array($widget['dir'], $plan_widgets)) || ! isset($plan_settings->widgets)) ? true : false;

		// Get app stats for given period
		$app_stats = \Analytics\Controller\AppTrackController::getVisits($apps_array, $date_start, $date_end);

		//$stats_found = (count($widgets_overview) == 0) ? false : true;
		$stats_found = true;

		return View::make('app.dashboard.dashboard', array(
			'sl' => $sl,
			'username' => $username,
			'date_start' => $date_start,
			'date_end' => $date_end,
			'apps' => $apps,
			'apps_array' => $apps_array,
			'apps_array_ids' => $apps_array_ids,
			'campaigns' => $campaigns,
			'campaign' => $campaign,
			'campaign_apps' => $campaign_apps,
			'app' => $app,
			'first_created' => $first_created,
			'stats_found' => $stats_found,
			'widget_allow' => $widget_allow,
			'app_stats' => $app_stats
		));
    }

    /**
     * App JavaScript
     */
    public function getAppJs()
    {
        $translation = \Lang::get('javascript');

		$js = '_lang=[];';
		foreach($translation as $key => $val)
		{
			$js .= '_lang["' . $key . '"]="' . $val . '";';
		}

		$response = \Response::make($js);
		$response->header('Content-Type', 'application/javascript');

		return $response;
    }
}
