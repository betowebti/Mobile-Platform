<?php
namespace Mobile\Controller;

/*
|--------------------------------------------------------------------------
| Widget controller
|--------------------------------------------------------------------------
|
| Widget related logic
|
*/

class WidgetController extends \BaseController {

    /**
	 * Construct
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
     * Get data
     */
    public static function getData($page, $key, $default = NULL)
    {
        if(ends_with($key, '[]'))
        {
            $return = array();
            $key = substr($key, 0, strlen($key) - 2);

            $app_widget_data = \Mobile\Model\AppWidgetData::where('app_page_id', $page->id)->get();
            foreach($app_widget_data as $widget_data)
            {
                if(starts_with($widget_data->name, $key . '['))
                {
					$json = json_decode($widget_data->value);
					$json->widget_id = $widget_data->id;
                    $return[] = $json;
                }
            }
            return (count($return) == 0) ? $default : $return;
        }
        else
        {
            $app_widget_data = \Mobile\Model\AppWidgetData::where('app_page_id', $page->id)->where('name', $key)->first();
            if(count($app_widget_data) > 0)
            {
				$app_widget_data->widget_id = $app_widget_data->id;
                return $app_widget_data->value;
            }
            else
            {
                return $default;
            }
        }
    }

    /**
     * Set data
     */
    public static function setData($page, $key, $value)
    {
        $return = false;
        $app_widget_data = \Mobile\Model\AppWidgetData::where('app_page_id', $page->id)->where('name', $key)->first();
		if(is_array($value)) $value = json_encode($value);

        if(count($app_widget_data) > 0)
        {
            $return = 'updated';
            $app_widget_data->value = $value;
        }
        else
        {
            $return = 'inserted';
            $app_widget_data = new \Mobile\Model\AppWidgetData;

            $app_widget_data->app_page_id = $page->id;
            $app_widget_data->name = $key;
            $app_widget_data->value = $value;
            
        }
        $app_widget_data->save();

        return $return;
    }

    /**
     * Load all widget config
     */
    public static function loadAllWidgetConfig()
    {
		$widgets_dir = public_path() . '/widgets/';
		$widgets = \File::directories($widgets_dir);

		$widget_config = array();

		foreach($widgets as $widget)
		{
			$widget_config_file = $widget . '/config/widget.php';
			$widget_lang_file = $widget . '/lang/' . \App::getLocale() . '/global.php';

			if (! \File::exists($widget_lang_file)) $widget_lang_file = $widget . '/lang/en/global.php';

			if(\File::exists($widget_config_file) && \File::exists($widget_lang_file))
			{
				$config = \File::getRequire($widget_config_file);

				if($config['active'])
				{
					$lang = \File::getRequire($widget_lang_file);
					$config['dir'] = basename($widget);
					$widget_config[$lang['name']] = $config;
				}
			}
		}

		ksort($widget_config, SORT_STRING | SORT_FLAG_CASE);

        return $widget_config;
    }

    /**
     * Load widget config
     */
    public static function loadWidgetConfig($widget)
    {
		$widget_config_file = public_path() . '/widgets/' . $widget . '/config/widget.php';
		$widget_lang_file = public_path() . '/widgets/' . $widget . '/lang/' . \App::getLocale() . '/global.php';

		if (! \File::exists($widget_lang_file)) $widget_lang_file = $widget . '/lang/en/global.php';

		$config = false;

		if(\File::exists($widget_config_file) && \File::exists($widget_lang_file))
		{
			$config = \File::getRequire($widget_config_file);
			$lang = \File::getRequire($widget_lang_file);
			$config['dir'] = $widget;
			$config['name'] = $lang['name'];
			$config['default_name'] = (isset($lang['default_name'])) ? $lang['default_name'] : NULL;
		}

        return $config;
    }

    /**
     * Route widget post
     */
    public static function postPost($widget, $function)
    {
        $sl = \Input::get('sl', '');
        if($sl != '')
        {
            $qs = \App\Core\Secure::string2array($sl);
       		$app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->first();
       		$page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

	        $app_language = \App\Controller\AccountController::siteLanguage($app);
			\App::setLocale($app_language);
        }
        else
        {
            $app = NULL;
            $page = NULL;
        }

        // Load widget, namespace views, translation and config
        $widget_dir = public_path() . '/widgets/' . $widget;
        \View::addLocation($widget_dir . '/views');
        \View::addNamespace('widget', $widget_dir . '/views');
        \Lang::addNamespace('widget', $widget_dir . '/lang');
        \Config::addNamespace('widget', $widget_dir . '/config');

        require public_path() . '/widgets/' . $widget . '/controllers/AppController.php';

        return \App::make('\Widget\Controller\AppController')->callAction($function, ['app' => $app, 'page' => $page]);
    }

    /**
     * Route widget get
     */
    public static function getGet($widget, $function)
    {
        $sl = \Input::get('sl', '');
        if($sl != '')
        {
            $qs = \App\Core\Secure::string2array($sl);
       		$app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->first();
       		$page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

	        $app_language = \App\Controller\AccountController::siteLanguage($app);
			\App::setLocale($app_language);
        }
        else
        {
            $app = NULL;
            $page = NULL;
        }

        // Load widget, namespace views, translation and config
        $widget_dir = public_path() . '/widgets/' . $widget;
        \View::addLocation($widget_dir . '/views');
        \View::addNamespace('widget', $widget_dir . '/views');
        \Lang::addNamespace('widget', $widget_dir . '/lang');
        \Config::addNamespace('widget', $widget_dir . '/config');

        require public_path() . '/widgets/' . $widget . '/controllers/AppController.php';

        return \App::make('\Widget\Controller\AppController')->callAction($function, ['app' => $app, 'page' => $page]);
    }

    /**
     * Widget route
     */
    public static function getRoute($widget, $function, $sl, $id = NULL, $extra1 = NULL)
    {
        if($sl != '')
        {
            $qs = \App\Core\Secure::string2array($sl);
       		$app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->first();
       		$page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

	        $app_language = \App\Controller\AccountController::siteLanguage($app);
			\App::setLocale($app_language);
        }
        else
        {
            $app = NULL;
            $page = NULL;
        }

        // Load widget, namespace views, translation and config
        $widget_dir = public_path() . '/widgets/' . $widget;
        \View::addLocation($widget_dir . '/views');
        \View::addNamespace('widget', $widget_dir . '/views');
        \Lang::addNamespace('widget', $widget_dir . '/lang');
        \Config::addNamespace('widget', $widget_dir . '/config');

        require public_path() . '/widgets/' . $widget . '/controllers/AppController.php';

        return \App::make('\Widget\Controller\AppController')->callAction($function, ['app' => $app, 'page' => $page, 'id' => $id, 'extra1' => $extra1]);
    }

    /**
     * Route widget admin post
     */
    public static function postAdminPost($widget, $function)
    {
        $sl = \Input::get('sl', '');
        if($sl != '')
        {
            $qs = \App\Core\Secure::string2array($sl);
       		$app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->first();
       		$page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

	        $app_language = \App\Controller\AccountController::siteLanguage($app);
			\App::setLocale($app_language);
        }
        else
        {
            $app = NULL;
            $page = NULL;
        }

        // Load widget, namespace views, translation and config
        $widget_dir = public_path() . '/widgets/' . $widget;
        \View::addLocation($widget_dir . '/views');
        \View::addNamespace('widget', $widget_dir . '/views');
        \Lang::addNamespace('widget', $widget_dir . '/lang');
        \Config::addNamespace('widget', $widget_dir . '/config');

        require public_path() . '/widgets/' . $widget . '/controllers/AdminController.php';

        return \App::make('\Widget\Controller\AdminController')->callAction($function, ['app' => $app, 'page' => $page]);
    }

    /**
     * View user data modal
     */
    public function getViewDataModal()
    {
		$sl = \Input::get('sl', '');
        if($sl != '')
        {
            $qs = \App\Core\Secure::string2array($sl);
			$data = \Mobile\Model\AppUserData::find($qs['app_user_data_id']);

			$created_at = $data->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s');
			$data = $data->toArray();

        	return \View::make('app.analytics.modal.widget-data-view', compact('data', 'created_at'));
        }
    }

    /**
     * Delete user data
     */
    public static function postDeleteData()
    {
        $sl = \Input::get('sl', '');
        if($sl != '')
        {
            $qs = \App\Core\Secure::string2array($sl);
			$data = \Mobile\Model\AppUserData::find($qs['app_user_data_id']);
			$data->forceDelete();
        }

		return \Response::json(array('result' => 'success'));
	}

    /**
     * Get widget data for table
     */
    public function getWidgetTableData()
    {
		$sl = \Input::get('sl', '');
        if($sl != '')
        {
            $qs = \App\Core\Secure::string2array($sl);
			if(isset($qs['campaign_id']))
			{
				$apps = \Mobile\Model\App::where('apps.campaign_id', '=', $qs['campaign_id'])
					->select(array('id'))
					->get()
					->toArray();
				$apps = array_flatten($apps);
			}
			elseif(isset($qs['app_id']))
			{
				$apps = array($qs['app_id']);
			}
        }
		else
		{
        	// All apps
			if($this->parent_user_id != NULL && \Auth::user()->getRoleId() == 4)
			{
				$user_settings = json_decode(\Auth::user()->settings);
				$apps = (isset($user_settings->app_permissions)) ? $user_settings->app_permissions : array();
			}
			else
			{
				$apps = \Mobile\Model\App::where('apps.user_id', '=', $this->parent_user_id)
					->select(array('id'))
					->get()
					->toArray();
				$apps = array_flatten($apps);
			}
		}

		$order_by = \Input::get('order.0.column', 0);
		$order = \Input::get('order.0.dir', 'asc');
		$search = \Input::get('search.regex', '');
		$q = \Input::get('search.value', '');
		$start = \Input::get('start', 0);
		$draw = \Input::get('draw', 1);
		$length = \Input::get('length', 10);
        if($length == -1) $length = 100000;
		$data = array();

		$aColumn = array('name', 'created_at', 'id');

		if($q != '')
		{
			$count = \Mobile\Model\AppUserData::orderBy($aColumn[$order_by], $order)
				->whereIn('app_id', $apps)
				->where(function ($query) use($q) {
					$query->orWhere('name', 'like', '%' . $q . '%');
					$query->orWhere('value', 'like', '%' . $q . '%');
				})
				->count();

			$oData = \Mobile\Model\AppUserData::orderBy($aColumn[$order_by], $order)
				->whereIn('app_id', $apps)
				->where(function ($query) use($q) {
					$query->orWhere('name', 'like', '%' . $q . '%');
					$query->orWhere('value', 'like', '%' . $q . '%');
				})
				->take($length)->skip($start)->get();
		}
		else
		{
			$count = \Mobile\Model\AppUserData::whereIn('app_id', $apps)->orderBy($aColumn[$order_by], $order)->count();
			$oData = \Mobile\Model\AppUserData::whereIn('app_id', $apps)->orderBy($aColumn[$order_by], $order)->take($length)->skip($start)->get();
		}

		if($length == -1) $length = $count;

		$recordsTotal = $count;
		$recordsFiltered = $count;

		foreach($oData as $row)
		{
			if (isset($row->appPage->widget) && $row->appPage->widget == 'forms')
			{
				$name = (isset($row->appPage->name)) ? $row->app->name . ' / ' . $row->appPage->name : $row->app->name;
				$data[] = array(
					'DT_RowId' => 'row_' . $row->id,
					'name' => $name,
					'created_at' => $row->created_at->timezone(\Auth::user()->timezone)->format('Y-m-d H:i:s'),
					'sl' => \App\Core\Secure::array2string(array('app_user_data_id' => $row->id))
				);
			}
		}

		$response = array(
			'draw' => $draw,
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered,
			'data' => $data
		);

		return \Response::json($response);
    }
}