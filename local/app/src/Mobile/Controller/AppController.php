<?php
namespace Mobile\Controller;

use Auth, View, Input;

/*
|--------------------------------------------------------------------------
| App controller
|--------------------------------------------------------------------------
|
| CMS App related logic
|
*/

class AppController extends \BaseController {

  /**
   * Construct
   */
  public function __construct()
  {
    if(Auth::check())
    {
      $this->parent_user_id = (Auth::user()->parent_id == NULL) ? Auth::user()->id : Auth::user()->parent_id;
    }
    else
    {
      $this->parent_user_id = NULL;
    }
  }

  /**
   * New App
   */
  public function newApp()
  {
    return View::make('user.app.new');
  }

  /**
   * Show all apps partial
   */
  public function getApps()
  {
    if($this->parent_user_id != NULL && \Auth::user()->getRoleId() == 4)
    {
      $user_settings = json_decode(\Auth::user()->settings);
      $app_permissions = (isset($user_settings->app_permissions)) ? $user_settings->app_permissions : array();

      $apps = \Mobile\Model\App::where('user_id', '=', $this->parent_user_id)->whereIn('id', $app_permissions)->orderBy('name', 'asc')->get();
      $campaigns = \Campaign\Model\Campaign::where('user_id', '=', $this->parent_user_id)->whereIn('id', $apps->lists('campaign_id'))->orderBy('name', 'asc')->get();
    }
    else
    {
      $apps = \Mobile\Model\App::where('user_id', '=', $this->parent_user_id)->orderBy('name', 'asc')->get();
      $campaigns = \Campaign\Model\Campaign::where('user_id', '=', $this->parent_user_id)->orderBy('name', 'asc')->get();
    }

    return View::make('app.mobile.apps', array(
      'apps' => $apps,
      'campaigns' => $campaigns
    ));
  }

  /**
   * Show QR modal
   */
  public function getQrModal()
  {
    return View::make('app.mobile.modal.qr');
  }

  /**
   * Show app settings modal
   */
  public function getAppSettingsModal()
  {
    $sl = \Request::input('sl', '');
    $qs = \App\Core\Secure::string2array($sl);
       $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

    if (\Auth::user()->parent_id != '')
    {
      $parent_user = \User::where('id', '=', \Auth::user()->parent_id)->first();
      $plan_settings = $parent_user->plan->settings;
    }
    else
    {
      $plan_settings = \Auth::user()->plan->settings;
    }

    $plan_settings = json_decode($plan_settings);

    $domain = (isset($plan_settings->domain)) ? (boolean) $plan_settings->domain : true;

    return View::make('app.mobile.modal.app-settings', array(
      'app' => $app,
      'sl' => $sl,
      'domain' => $domain
    ));
  }

  /**
   * Show main app
   */
  public function getApp()
  {
    $sl = \Request::input('sl', '');

    if($sl != '')
    {
      $qs = \App\Core\Secure::string2array($sl);
      $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();
      $app_pages = $app->appPages->toHierarchy();
      $campaigns = \Campaign\Model\Campaign::where('user_id', '=', $this->parent_user_id)->orderBy('name', 'asc')->get();
      $widgets = \Mobile\Controller\WidgetController::loadAllWidgetConfig();

      if (\Auth::user()->parent_id != '')
      {
        $parent_user = \User::where('id', '=', \Auth::user()->parent_id)->first();
        $plan_settings = $parent_user->plan->settings;
      }
      else
      {
        $parent_user = \Auth::user();
        $plan_settings = \Auth::user()->plan->settings;
      }

      $plan_settings = json_decode($plan_settings);
      $plan_widgets = (isset($plan_settings->widgets)) ? $plan_settings->widgets : array();

      if (! isset($parent_user->plan->settings) || $parent_user->plan->settings == '') $plan_widgets = false;

      // App limit
      $plan_max_apps = (isset($plan_settings->max_apps)) ? $plan_settings->max_apps : 0;
      $apps = \Mobile\Model\App::where('user_id', '=', $this->parent_user_id)->count();

      $app_limit = ($plan_max_apps != 0 && $apps >= (int) $plan_max_apps) ? true : false;

      $download = (isset($plan_settings->download)) ? (boolean) $plan_settings->download : true;

      return View::make('app.mobile.app-edit', array(
        'sl' => $sl,
        'app' => $app,
        'app_pages' => $app_pages,
        'widgets' => $widgets,
        'campaigns' => $campaigns,
        'plan_widgets' => $plan_widgets,
        'app_limit' => $app_limit,
        'download' => $download
      ));
    }
    else
    {
      if(\Auth::user()->parent_id != NULL && \Auth::user()->getRoleId() == 4)
      {
        return View::make('app.auth.no-access');
      }

      // Max apps
      if (\Auth::user()->parent_id != '')
      {
        $parent_user = \User::where('id', '=', \Auth::user()->parent_id)->first();
        $plan_settings = $parent_user->plan->settings;
      }
      else
      {
        $plan_settings = \Auth::user()->plan->settings;
      }

      $plan_settings = json_decode($plan_settings);
      $plan_max_apps = (isset($plan_settings->max_apps)) ? $plan_settings->max_apps : 0;

      // Current apps
         $apps = \Mobile\Model\App::where('user_id', '=', $this->parent_user_id)->count();

      if($plan_max_apps != 0 && $apps >= (int) $plan_max_apps)
      {
        return View::make('app.auth.upgrade');
      }

      // App types
      $app_types = \Mobile\Model\AppType::orderBy('sort', 'asc')->remember(\Config::get('cache.ttl'), 'global_app_types')->get();
         $campaigns = \Campaign\Model\Campaign::where('user_id', '=', $this->parent_user_id)->orderBy('name', 'asc')->get();

      return View::make('app.mobile.app-new', array(
        'app_types' => $app_types,
        'campaigns' => $campaigns
      ));
    }
  }

  /**
   * Delete app
   */
  public function getDelete()
  {
    $sl = \Request::input('data', '');
    $qs = \App\Core\Secure::string2array($sl);
       $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

    if(! is_null($app))
    {
      $app->forceDelete();

      // Delete SQLite file(s) from userdata dir
      $userdata = storage_path() . '/userdata/app_' . $app->id . '_*';
      array_map('unlink', glob($userdata));

      // Delete export dir
      $export_dir = storage_path() . '/userdata/exports/app_' . $app->id;
      if (\File::isDirectory($export_dir)) \File::deleteDirectory($export_dir);
    }

    return \Response::json(array('result' => 'success'));
  }

  /**
   * Save new App
   */
  public function postNew()
  {
    $name = \Request::get('name');
    $campaign = \Request::get('campaign', NULL);
    $timezone = \Request::get('timezone', 'UTC');
    $language = \Request::get('language', 'en');
    $app_type_id = \Request::get('app_type_id');
    $app_theme = \Request::get('app_theme');

    $app = new \Mobile\Model\App;
    $app->user_id = $this->parent_user_id;
    $app->app_type_id = $app_type_id;
    $app->theme = $app_theme;
    $app->layout = 'tabs-bottom';
    $app->name = $name;
    $app->timezone = $timezone;
    $app->language = $language;

    // Generate App key
    $keyauth = new \App\Core\KeyAuth;
    $keyauth->key_unique = TRUE;
    $keyauth->key_store = TRUE;
    $keyauth->key_chunk = 4;
    $keyauth->key_part = 4;
    $keyauth->key_pre = "";

    $key = $keyauth->generate_key();

    $app->local_domain = $key;

    if($campaign != NULL)
    {
      $campaign = json_decode($campaign);
      if($campaign->id > 0)
      {
        // Campaign already exists, just set campaign_id
        $app->campaign_id = $campaign->id;
      }
      else
      {
        // Campaign doesn't exist yet, add it first
        $new_campaign = new \Campaign\Model\Campaign;
        $new_campaign->user_id = $this->parent_user_id;
        $new_campaign->name = $campaign->text;
        if($new_campaign->save())
        {
          $app->campaign_id = $new_campaign->id;
        }
      }
    }

    if($app->save())
    {
      $sl = \App\Core\Secure::array2string(array('app_id' => $app->id));

      $response = array(
        'result' => 'success',
        'sl' => $sl
      );
    }
    else
    {
      $response = array(
        'result' => 'error', 
        'result_msg' => $app->errors()->first()
      );
    }

    return \Response::json($response);
  }

  /**
   * Duplicate App
   */
  public function postDuplicate()
  {
    $sl = \Request::input('sl', '');
    $name = \Request::input('name', '');

    if($sl != '')
    {
      $qs = \App\Core\Secure::string2array($sl);
      $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

      $new_app = $app->replicate();

      // Generate App key
      $keyauth = new \App\Core\KeyAuth;
      $keyauth->key_unique = TRUE;
      $keyauth->key_store = TRUE;
      $keyauth->key_chunk = 4;
      $keyauth->key_part = 4;
      $keyauth->key_pre = "";

      $key = $keyauth->generate_key();

      $new_app->local_domain = $key;
      $new_app->name = $name;

      $new_app->settings = \App\Core\Settings::json(array(
        'pg_id' => ''
      ), $new_app->settings);

      $new_app->save();

      // Replicate pages
      $appPages = \Mobile\Model\AppPage::where('app_id', '=', $qs['app_id'])->orderBy('lft', 'asc')->get();

      foreach ($appPages as $appPage)
      {
        $newPage = $appPage->replicate();
        $newPage->app_id = $new_app->id;
        $newPage->save();

        // Replicate page data
        $widgetData = \Mobile\Model\AppWidgetData::where('app_page_id', '=', $appPage->id)->get();

        foreach ($widgetData as $data)
        {
          $newData = $data->replicate();
          $newData->app_page_id = $newPage->id;
          $newData->save();
        }
      }

      $sl = \App\Core\Secure::array2string(array('app_id' => $new_app->id));
    }

    return \Response::json(array(
        'result' => 'success',
        'sl' => $sl
    ));
  }

  /**
   * Save app settings modal
   */
  public function postAppSettings()
  {
    $sl = \Request::input('sl', '');
    $qs = \App\Core\Secure::string2array($sl);
       $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

    if(count($app) > 0)
    {
      $local_domain = \Request::get('local_domain', '');

      // Validate local domain
      $input = array(
        'local_domain' => $local_domain
      );

      $rules = array(
        'local_domain'  => 'required|min:3|max:42|regex:/^[a-zA-Z0-9_-]+$/|unique:apps,local_domain,' . $qs['app_id']
      );
  
      $validator = \Validator::make($input, $rules);

      if($validator->fails())
      {
        return \Response::json(array(
          'result' => 'error', 
          'msg' => $validator->messages()->first()
        ));
        die();
      }

      $name = \Request::get('name');
      $domain = \Request::get('domain', NULL);
      $timezone = \Request::get('timezone', 'UTC');
      $language = \Request::get('language', 'en');

      // Social
      $social = \Request::get('social', []);
      $social_size = \Request::get('social_size', 14);
      $social_icons_only = \Request::get('social_icons_only', 0);
      $social_show_count = \Request::get('social_show_count', 0);

      $head_tag = \Request::get('head_tag', '');
      $end_of_body_tag = \Request::get('end_of_body_tag', '');
      $css = \Request::get('css', '');
      $js = \Request::get('js', '');

      // Prevent same domain update
      $url_parts = parse_url(\URL::current());
      if($domain != $url_parts['host']) $app->domain = $domain;

      $app->name = $name;
      $app->local_domain = $local_domain;
      $app->timezone = $timezone;
      $app->language = $language;

      // Settings
      $app->settings = \App\Core\Settings::json(array(
        'social' => $social,
        'social_size' => $social_size,
        'social_icons_only' => $social_icons_only,
        'social_show_count' => $social_show_count,
        'head_tag' => $head_tag,
        'end_of_body_tag' => $end_of_body_tag,
        'css' => $css,
        'js' => $js
      ), $app->settings);

      $app->save();
    }

    return \Response::json(array('result' => 'success', 'url' => url('mobile/' . $local_domain)));
  }

  /**
   * Redirect mobile visitors modal
   */
  public function getAppRedirectModal()
  {
    $sl = \Request::input('sl', '');
    $qs = \App\Core\Secure::string2array($sl);
       $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

    return View::make('app.mobile.modal.app-redirect', array(
      'app' => $app,
      'sl' => $sl
    ));
  }

  /**
   * Export app modal
   */
  public function getAppExportModal()
  {
    $phonegap = (\Config::get('phonegap.username', '') != '') ? true : false;
    $sl = \Request::input('sl', '');
    $qs = \App\Core\Secure::string2array($sl);
       $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

    if ($phonegap)
    {
      // Get PhoneGap App ID
      $settings = $app->settings;
      if ($settings != '') $settings = json_decode($settings);

      // Key titles
      $key_android = (isset($settings->android->title)) ? '<i class="fa fa-check"></i> ' . $settings->android->title : '<i class="fa fa-times"></i> ' . trans('export.no_certificate');
      $key_ios = (isset($settings->ios->title)) ? '<i class="fa fa-check"></i> ' . $settings->ios->title : '<i class="fa fa-times"></i> ' . trans('export.no_certificate');
      $key_winphone = (isset($settings->winphone->title)) ? '<i class="fa fa-check"></i> ' . $settings->winphone->title : '<i class="fa fa-times"></i> ' . trans('export.no_publisher_id');

      // PhoneGap App ID
      $pg_id = (isset($settings->pg_id)) ? $settings->pg_id : 0;
      $pg_sl = \App\Core\Secure::array2string(array('pg_id' => $pg_id));

      if ($pg_id != 0)
      {
        $pg = new \Mobile\Controller\PhonegapController;
        $pg_app = $pg->getApp($pg_id);
  
        $last_build = new \Carbon\Carbon($pg_app->last_build);
        $last_build = $last_build->diffForHumans();
  
        $pg_app->last_build = $last_build;
      }
      else
      {
        $pg_app = new \stdClass();
        $pg_app->version = '-';
        $pg_app->build_count = 0;
        $pg_app->last_build = '';
  
        $pg_app->status = new \stdClass();
        $pg_app->status->android = '';
        $pg_app->status->ios = '';
        $pg_app->status->winphone = '';
  
        $pg_app->keys = new \stdClass();
        $pg_app->keys->android = 'No certificate';
        $pg_app->keys->ios = 'No certificate';
        $pg_app->keys->winphone = 'No certificate';
      }
  
      $btn_class['android'] = 'primary';
      if ($pg_app->status->android == 'complete') $btn_class['android'] = 'primary';
  
      $btn_class['ios'] = 'danger';
      if ($pg_app->status->ios == 'complete') $btn_class['ios'] = 'primary';
  
      $btn_class['winphone'] = 'primary';
      if ($pg_app->status->winphone == 'complete') $btn_class['winphone'] = 'primary';
  
      // Get keys
      $keys = \Mobile\Controller\PhonegapController::getUserKeys();
    }
    else
    {
      $pg_app = NULL;
      $pg_sl = NULL;
      $keys = NULL;
      $build_btn = NULL;
      $btn_class = NULL;
      $key_android = NULL;
      $key_ios = NULL;
      $key_winphone = NULL;
      $last_build = NULL;
    }

    $punycode = new \Punycode();
    $slugify = new \Slugify();

    $punycode_name = $punycode->encode($app->name);
    $filename = $slugify->slugify(urlencode($punycode_name));
    //$filename = $filename . '-' . date('Y-m-d');

    $export_dir = storage_path() . '/userdata/exports/app_' . $app->id;

    $html5 = (\File::isFile($export_dir . '/html5.zip')) ? true : false;
    $cordova = (\File::isFile($export_dir . '/cordova.zip')) ? true : false;

    $build_btn = ($html5 || $cordova) ? trans('export.rebuild') : trans('export.build');

    // sitemap.xml url
    $sitemap_url = (\File::isFile($export_dir . '/sitemap.txt')) ? \File::get($export_dir . '/sitemap.txt') : $app->domain();

    return View::make('app.mobile.modal.app-export', array(
      'app' => $app,
      'sl' => $sl,
      'filename' => $filename,
      'html5' => $html5,
      'cordova' => $cordova,
      'phonegap' => $phonegap,
      'pg_app' => $pg_app,
      'pg_sl' => $pg_sl,
      'keys' => $keys,
      'build_btn' => $build_btn,
      'btn_class' => $btn_class,
      'key_android' => $key_android,
      'key_ios' => $key_ios,
      'key_winphone' => $key_winphone,
      'sitemap_url' => $sitemap_url
    ));
  }

  /**
   * Export app keys modal
   */
  public function getAppExportKeysModal()
  {
    $platform = \Request::input('platform', '');
    $sl = \Request::input('sl', '');
    $qs = \App\Core\Secure::string2array($sl);
       $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

    // Get PhoneGap App ID
    $settings = $app->settings;
    if ($settings != '') $settings = json_decode($settings);
    $pg_id = (isset($settings->pg_id)) ? $settings->pg_id : 0;
    $pg_sl = \App\Core\Secure::array2string(array('pg_id' => $pg_id));

    // Keys
    $keys = \Mobile\Controller\PhonegapController::getUserKeys($platform);

    // Modal title
    switch ($platform)
    {
      case 'ios': $title = '<i class="fa fa-apple"></i> ' . trans('export.ios'); break;
      case 'android': $title = '<i class="fa fa-android"></i> ' . trans('export.android'); break;
      case 'winphone': $title = '<i class="fa fa-windows"></i> ' . trans('export.windows_phone'); break;
    }
    // Title prefix
    $title_prefix = 'u' . $this->parent_user_id . '-';

    return View::make('app.mobile.modal.app-export-keys', array(
      'app' => $app,
      'sl' => $sl,
      'pg_sl' => $pg_sl,
      'title' => $title,
      'platform' => $platform,
      'keys' => $keys,
      'title_prefix' => $title_prefix
    ));
  }

  /**
   * Get keys table
   */
  public function postAppExportKeysTable()
  {
    $platform = \Request::input('platform', '');
    $sl = \Request::input('sl', '');
    $qs = \App\Core\Secure::string2array($sl);
       $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

    // Get PhoneGap App ID
    $settings = $app->settings;
    if ($settings != '') $settings = json_decode($settings);
    $pg_id = (isset($settings->pg_id)) ? $settings->pg_id : 0;
    $pg_sl = \App\Core\Secure::array2string(array('pg_id' => $pg_id));

    $certificate['android'] = (isset($settings->android)) ? $settings->android : 0;
    $certificate['ios'] = (isset($settings->ios)) ? $settings->ios : 0;
    $certificate['winphone'] = (isset($settings->winphone)) ? $settings->winphone : 0;

    // Keys
    $keys = \Mobile\Controller\PhonegapController::getUserKeys($platform);

    // Title prefix
    $title_prefix = 'u' . $this->parent_user_id . '-';

    if (count($keys) == 0)
    {
      if ($platform == 'winphone')
      {
        echo '<div class="alert alert-warning">' . trans('export.no_publisher_ids') . '</div>';
      }
      else
      {
        echo '<div class="alert alert-warning">' . trans('export.no_certificates') . '</div>';
      }
    }
    else
    {
      echo '<table class="table table-condensed table-bordered table-hover table-striped">';

      foreach ($keys as $key)
      {
        $title = str_replace($title_prefix, '', $key->title);
        $pg_key = \App\Core\Secure::array2string(array('key_id' => $key->id));

        $active = (isset($certificate[$platform]->key) && $certificate[$platform]->key == $key->id) ? true : false;
        $tr_class = ($active) ? ' class="success"' : '';
        $check_class = ($active) ? ' disabled' : '';

        echo '<tr' . $tr_class . '>';
        echo '<td>' . $title . '</td>';
        echo '<td style="width:80px" class="text-center">';
        echo '<button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(\'' . $pg_key . '\')" style="padding:0px 9px 1px"><i class="fa fa-times"></i></button> ';
        echo '<button type="button" class="btn btn-success btn-sm' . $check_class . '" onclick="setRowActive(\'' . $pg_key . '\', \'' . str_replace('"', '&quot;', str_replace('\'', '&#39;', $title)) . '\')" style="padding:0px 9px 1px"><i class="fa fa-check"></i></button>';
        echo '</td>';
        echo '</tr>';
      }

      echo '</table>';
    }
  }

  /**
   * Get build status
   */
  public function postAppExportGetBuildStatus()
  {
    $sl = \Request::input('sl', '');
    $qs = \App\Core\Secure::string2array($sl);
       $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

    // Get PhoneGap App ID
    $settings = $app->settings;
    if ($settings != '') $settings = json_decode($settings);
    $pg_id = (isset($settings->pg_id)) ? $settings->pg_id : 0;

    $pg_sl = \App\Core\Secure::array2string(array('pg_id' => $pg_id, 'app_id' => $qs['app_id']));

    $built = false;
    $all_ready = false;

    $android_status_class = 'btn-default';
    $ios_status_class = 'btn-default';
    $winphone_status_class = 'btn-default';

    $android_download_class = 'disabled';
    $ios_download_class = 'disabled';
    $winphone_download_class = 'disabled';

    $android_download_class_href = 'disabled';
    $ios_download_class_href = 'disabled';
    $winphone_download_class_href = 'disabled';

    $android_pending_class = 'pending';
    $ios_pending_class = 'pending';
    $winphone_pending_class = 'pending';

    $android_download = 'javascript:void(0);';
    $ios_download = 'javascript:void(0);';
    $winphone_download = 'javascript:void(0);';

    if ($pg_id != 0)
    {
      $built = true;

      $pg = new \Mobile\Controller\PhonegapController;
      $response = $pg->getApp($pg_id);

      if (
        isset($response->status->winphone) && ($response->status->winphone == 'complete' || $response->status->winphone == 'error') && 
        isset($response->status->android) && ($response->status->android == 'complete' || $response->status->android == 'error') && 
        isset($response->status->ios) && ($response->status->ios == 'complete' || $response->status->ios == 'error')
      )
      {
        $all_ready = true;
      }

      if (isset($response->status->android) && $response->status->android == 'complete') $android_status_class = 'btn-primary';
      if (isset($response->status->ios) && $response->status->ios == 'complete') $ios_status_class = 'btn-primary';
      if (isset($response->status->winphone) && $response->status->winphone == 'complete') $winphone_status_class = 'btn-primary';

      if (isset($response->download->android)) 
      {
        $android_download = url('api/v1/app-export/download/android/' . $pg_sl);
        $android_download_class = '';
        $android_pending_class = '';
        $android_download_class_href = '';
      }
      elseif ($response->status->android == 'error')
      {
        $android_pending_class = '';
          $android_status_class = 'btn-danger';
        $android_download_class = '';
        $android_download_class_href = 'disabled';
      }

      if (isset($response->download->ios)) 
      {
        $ios_download = url('api/v1/app-export/download/ios/' . $pg_sl);
        $ios_download_class = '';
        $ios_pending_class = '';
        $ios_download_class_href = '';
      }
      elseif ($response->status->ios == 'error')
      {
        $ios_pending_class = '';
        $ios_status_class = 'btn-danger';
        $ios_download_class = '';
        $ios_download_class_href = 'disabled';
      }

      if (isset($response->download->winphone)) 
      {
        $winphone_download = url('api/v1/app-export/download/winphone/' . $pg_sl);
        $winphone_download_class = '';
        $winphone_pending_class = '';
        $winphone_download_class_href = '';
      }
      elseif ($response->status->winphone == 'error')
      {
        $winphone_pending_class = '';
        $winphone_status_class = 'btn-danger';
        $winphone_download_class = '';
        $winphone_download_class_href = 'disabled';
      }
    }

    return \Response::json(array(
      'built' => $built,
      'ready' => $all_ready,
      'android' => 
      [
        'status_class' => $android_status_class,
        'download_class' => $android_download_class,
        'download_class_href' => $android_download_class_href,
        'pending_class' => $android_pending_class,
        'download' => $android_download
      ],
      'ios' => 
      [
        'status_class' => $ios_status_class,
        'download_class' => $ios_download_class,
        'download_class_href' => $ios_download_class_href,
        'pending_class' => $ios_pending_class,
        'download' => $ios_download
      ],
      'winphone' => 
      [
        'status_class' => $winphone_status_class,
        'download_class' => $winphone_download_class,
        'download_class_href' => $winphone_download_class_href,
        'pending_class' => $winphone_pending_class,
        'download' => $winphone_download
      ]
    ));
  }

  /**
   * Delete key
   */
  public function postAppExportDeleteKeyTable()
  {
    $platform = \Request::input('platform', '');
    $sl = \Request::input('sl', '');
    $qs = \App\Core\Secure::string2array($sl);
       $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

    // Get PhoneGap App ID
    $settings = $app->settings;
    if ($settings != '') $settings = json_decode($settings);
    $pg_id = (isset($settings->pg_id)) ? $settings->pg_id : 0;

    // Key ID
    $id = \Request::input('id', '');
    $pg_key = \App\Core\Secure::string2array($id);

    $pg = new \Mobile\Controller\PhonegapController;
    $response = $pg->deleteKey($platform, $pg_key['key_id']);

    // Clear cache
    \Mobile\Controller\PhonegapController::forgetUserKeys($platform);
  }

  /**
   * Set active key
   */
  public function postAppExportActiveKeyTable()
  {
    $platform = \Request::input('platform', '');
    $title = \Request::input('title', '');
    $sl = \Request::input('sl', '');
    $qs = \App\Core\Secure::string2array($sl);
       $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->where('user_id', '=', $this->parent_user_id)->first();

    // Key ID
    $id = \Request::input('id', '');
    $pg_key = \App\Core\Secure::string2array($id);

    $app->settings = \App\Core\Settings::json(array(
      $platform => [
        'key' => $pg_key['key_id'],
        'title' => $title
      ]
    ), $app->settings);

    $app->save();
  }
}