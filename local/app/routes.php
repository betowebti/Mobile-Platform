<?php
/*
 |--------------------------------------------------------------------------
 | Installation check (database, permissions)
 |--------------------------------------------------------------------------
 */

\App\Controller\InstallationController::check();

/*
 |--------------------------------------------------------------------------
 | CORS
 |--------------------------------------------------------------------------
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

/*
 |--------------------------------------------------------------------------
 | Language
 |--------------------------------------------------------------------------
 */

$app_language = \App\Controller\AccountController::appLanguage();
App::setLocale($app_language);

/*
 |--------------------------------------------------------------------------
 | Globals
 |--------------------------------------------------------------------------
 */

$url_parts = parse_url(URL::current());

/*
 |--------------------------------------------------------------------------
 | Check for reseller or custom domain
 |--------------------------------------------------------------------------
 */

$reseller = \App\Controller\ResellerController::get();

if ($reseller !== false)
{
  $domain = str_replace('www.', '', $url_parts['host']);

  $custom_app = \Mobile\Model\App::where('domain', $domain)
    ->orWhere('domain', 'www.' . $domain)
    ->first();
}
else
{
  $custom_app = array();
}

/*
 |--------------------------------------------------------------------------
 | Front end website
 |--------------------------------------------------------------------------
 */

Route::get('/', function() use($url_parts, $custom_app, $reseller)
{
  if ($reseller === false && count($custom_app) == 0)
  {
    return \Response::view('app.errors.reseller-404', [], 404);
  }
  elseif (count($custom_app) > 0)
  {
    // Naked or www domain?
    if (substr($custom_app->domain, 0, 4) == 'www.' && substr($url_parts['host'], 0, 4) != 'www.')
    {
      return \Redirect::to($url_parts['scheme'] . '://' . $custom_app->domain, 301);
    } 
    elseif (substr($custom_app->domain, 0, 4) != 'www.' && substr($url_parts['host'], 0, 4) == 'www.')
    {
      return \Redirect::to($url_parts['scheme'] . '://' . $custom_app->domain, 301);
    }

    // App
    $language = \App\Controller\AccountController::siteLanguage($custom_app);
    App::setLocale($language);

    return App::make('\Mobile\Controller\MobileController')->showApp($custom_app->local_domain);
  }
  else
  {
    // Public facing website
    if (\Config::get('system.show_homepage')) 
    {
      return App::make('\App\Controller\WebsiteController')->showWebsite($url_parts);
    }
    else
    {
      return \Redirect::to('platform');
    }
  }
});

/*
 |--------------------------------------------------------------------------
 | API
 |--------------------------------------------------------------------------
 */

Route::group(array('prefix' => 'api/v1'), function()
{
  Route::controller('admin',                          'App\Controller\AdminController');
  Route::controller('account',                        'App\Controller\AccountController');
  Route::controller('remote',                         'App\Controller\RemoteController');
  Route::controller('campaign',                       'Campaign\Controller\CampaignController');
  Route::controller('mobile',                         'Mobile\Controller\MobileController');
  Route::controller('app',                            'Mobile\Controller\AppController');
  Route::controller('app-edit',                       'Mobile\Controller\AppEditController');
  Route::controller('app-export',                     'Mobile\Controller\ExportController');
  Route::controller('app-asset',                      'Mobile\Controller\AssetController');
  Route::controller('app-theme',                      'Mobile\Controller\ThemeController');
  Route::controller('app-remote',                     'Mobile\Controller\RemoteController');
  Route::controller('app-analytics',                  'Analytics\Controller\AppAnalyticsController');
  Route::controller('app-track',                      'Analytics\Controller\AppTrackController');
  Route::controller('help',                           'App\Core\Help');
  Route::controller('widget',                         'Mobile\Controller\WidgetController');
  Route::controller('thumb',                          'App\Core\Thumb');
  Route::controller('oauth',                          'App\Controller\oAuthController');
  Route::controller('website',                        'App\Controller\WebsiteController');
  Route::controller('translation',                    'App\Controller\TranslationController');
  Route::controller('hook',                           'App\Controller\HookController');
});

/*
 |--------------------------------------------------------------------------
 | App
 |--------------------------------------------------------------------------
 */

// Dashboard
Route::get( '/platform',                              'App\Controller\DashboardController@getMainDashboard');
Route::get( '/app/dashboard',                         'App\Controller\DashboardController@getDashboard');
Route::get( '/app/javascript',                        'App\Controller\DashboardController@getAppJs');

// Apps
Route::get( '/app/mobile',                            'Mobile\Controller\AppController@getApps');
Route::get( '/app/app',                               'Mobile\Controller\AppController@getApp');
Route::get( '/app/modal/mobile/qr',                   'Mobile\Controller\AppController@getQrModal');
Route::get( '/app/modal/mobile/app-redirect',         'Mobile\Controller\AppController@getAppRedirectModal');
Route::get( '/app/modal/mobile/app-settings',         'Mobile\Controller\AppController@getAppSettingsModal');
Route::get( '/app/modal/mobile/app-export',           'Mobile\Controller\AppController@getAppExportModal');
Route::get( '/app/modal/mobile/app-export/keys',      'Mobile\Controller\AppController@getAppExportKeysModal');

// App front end
Route::get( '/mobile',                                'Mobile\Controller\AppController@newApp');
Route::get( '/mobile/{local_domain}',                 'Mobile\Controller\MobileController@showApp');
Route::get( '/sitemap.xml',                           'Mobile\Controller\SitemapController@showSitemap');
Route::get( '/mobile/{local_domain}/sitemap.xml',     'Mobile\Controller\SitemapController@showSitemap');
Route::get( '/system.html',                           'Mobile\Controller\MobileController@showSystemTemplates');
Route::any( '/mobile/reset_password/{token}',         'Mobile\Controller\MobileController@showResetPass');
Route::get( '/mobile/{local_domain}/manifest.json',   'Mobile\Controller\PwaController@showManifest');
Route::get( '/sw.js',                                 'Mobile\Controller\PwaController@showServiceWorker');

// App Analytics
Route::get( '/app/app/analytics',                     'Analytics\Controller\AppAnalyticsController@getStats');
Route::get( '/app/app/widget-data',                   'Analytics\Controller\AppAnalyticsController@getData');
Route::get( '/app/app/public-users',                  'Analytics\Controller\AppAnalyticsController@getUsers');

// Media
Route::get( '/app/media',                             'Media\Controller\MediaController@getBrowser');
Route::get( '/app/browser',                           'Media\Controller\MediaController@elFinder');
Route::get( '/app/editor',                            'Media\Controller\EditorController@getEditor');
Route::get( '/app/editor/templates',                  'Media\Controller\EditorController@getTemplates');
Route::get( '/app/editor/template/{tpl}',             'Media\Controller\EditorController@getTemplate');

// Profile, team and subscription
Route::get( '/app/profile',                           'App\Controller\AccountController@getProfile');
Route::post('/app/profile',                           'App\Controller\AccountController@postProfile');
Route::get( '/app/modal/avatar',                      'App\Controller\AccountController@getAvatarModal');
Route::get( '/app/users',                             'App\Controller\AccountController@getUsers');
Route::get( '/app/user',                              'App\Controller\AccountController@getUser');
Route::get( '/app/upgrade',                           'App\Controller\AccountController@getUpgrade');
Route::get( '/app/account',                           'App\Controller\AccountController@getAccount');
Route::get( '/app/order-subscription',                'App\Controller\AccountController@getOrderSubscription');
Route::get( '/app/order-subscription-confirm',        'App\Controller\AccountController@getOrderSubscriptionConfirm');
Route::get( '/app/order-subscription-confirmed',      'App\Controller\AccountController@getOrderSubscriptionConfirmed');
Route::get( '/app/modal/account/invoice',             'App\Controller\AccountController@getInvoiceModal');

// Campaigns
Route::get( '/app/campaigns',                         'Campaign\Controller\CampaignController@getCampaigns');
Route::get( '/app/campaign',                          'Campaign\Controller\CampaignController@getCampaign');

// Messages
Route::get( '/app/messages',                          'App\Controller\MessageController@getInbox');
Route::get( '/app/message',                           'App\Controller\MessageController@getMessage');

// Help
Route::get( '/app/help/{item}',                       'App\Core\Help@getHelp');

// Admin
Route::get( '/app/admin/users',                       'App\Controller\AdminController@getUsers');
Route::get( '/app/admin/user',                        'App\Controller\AdminController@getUser');
Route::get( '/app/admin/plans',                       'App\Controller\AdminController@getPlans');
Route::get( '/app/admin/plan',                        'App\Controller\AdminController@getPlan');
Route::get( '/app/admin/website',                     'App\Controller\AdminController@getWebsite');
Route::get( '/app/admin/modal/website-settings',      'App\Controller\AdminController@getWebsiteSettingsModal');
Route::get( '/app/admin/purchases',                   'App\Controller\AdminController@getPurchases');
Route::get( '/app/admin/cms',                         'App\Controller\AdminController@getCms');

// Demo
Route::get( '/reset/{key}',                           'App\Controller\InstallationController@reset');

// Update
Route::get( '/update',                                'App\Controller\InstallationController@update');
Route::get( '/update/now',                            'App\Controller\InstallationController@doUpdate');

/*
 |--------------------------------------------------------------------------
 | Confide routes / authorization
 |--------------------------------------------------------------------------
 */

if (\Config::get('system.allow_registration')) 
{
  Route::get( 'signup',                                'UsersController@create');
  Route::get( 'confirm/{code}',                        'UsersController@confirm');

  Route::group(array('before' => 'csrf'), function()
  {
    Route::post('signup',                                'UsersController@store');
  });
}

Route::get( 'login',                                   'UsersController@login');
Route::get( 'forgot_password',                         'UsersController@forgotPassword');
Route::get( 'reset_password/{token}',                  'UsersController@resetPassword');
Route::get( 'logout',                                  'UsersController@logout');

Route::group(array('before' => 'csrf'), function()
{
  Route::post('login',                                 'UsersController@doLogin');
  Route::post('forgot_password',                       'UsersController@doForgotPassword');
  Route::post('reset_password',                        'UsersController@doResetPassword');
});

/*
 |--------------------------------------------------------------------------
 | ElFinder File browser
 |--------------------------------------------------------------------------
 */

if(isset($url_parts['path']) && strpos($url_parts['path'], '/elfinder') !== false)
{
  Route::group(array('before' => 'auth'), function()
  {
    if(Auth::check())
    {
      // Set Root dir
      if(Auth::user()->parent_id == NULL)
      {
        $root_dir = \App\Core\Secure::staticHash(Auth::user()->id);
      }
      else
      {
        // Check if user has admin access to media
        if(\Auth::user()->can('user_management'))
        {
          $root_dir = \App\Core\Secure::staticHash(Auth::user()->parent_id);
        }
        else
        {
          $Punycode = new Punycode();
          $user_dir = $Punycode->encode(Auth::user()->username);
          $root_dir = \App\Core\Secure::staticHash(Auth::user()->parent_id) . '/' . $user_dir;
        }
      }

      $root_dir_full = public_path() . '/uploads/user/' . $root_dir;

      $root = substr(url('/'), strpos(url('/'), \Request::server('HTTP_HOST')));
      $abs_path_prefix = str_replace(\Request::server('HTTP_HOST'), '', $root);

      if(! File::isDirectory($root_dir_full))
      {
        File::makeDirectory($root_dir_full, 0775, true);
      }

      if (\Config::get('s3.active', false))
      {
        $client = Aws\S3\S3Client::factory([
          'key'  => \Config::get('s3.key'),
          'secret' => \Config::get('s3.secret'),
          'region' => \Config::get('s3.region'),
          'version' => 'latest',
          'ACL' => 'public-read',
          'http'  => [
            'verify' => base_path() . '/cacert.pem'
          ]
        ]);

        $adapter = new League\Flysystem\AwsS3v2\AwsS3Adapter($client, \Config::get('s3.media_root_bucket'), null, array('ACL' => 'public-read'));

        // Create root dir if not exists
        $filesystem = new \League\Flysystem\Filesystem($adapter);
        $filesystem->createDir($root_dir);
      }
      elseif (\Config::get('ftp.active', false))
      {
        $adapter = new \League\Flysystem\Adapter\Ftp(
          [
            'host' => \Config::get('ftp.host'),
            'username' => \Config::get('ftp.username'),
            'password' => \Config::get('ftp.password'),
            'root' => \Config::get('ftp.root'),
            'port' => \Config::get('ftp.port'),
            'mode' => \Config::get('ftp.mode')
          ]
        );
      }

      if (\Config::get('s3.active', false))
      {
        $roots = array(
          array(
            'driver'    => 'Flysystem',
            'path'      => $root_dir,
            'filesystem'  => new \League\Flysystem\Filesystem($adapter),
            'URL'       => \Config::get('s3.url') . '/' . \Config::get('s3.media_root_bucket') . '/' . $root_dir,
            'alias'     => trans('global.my_files'),
            'accessControl' => 'Barryvdh\Elfinder\Elfinder::checkAccess',
            'alias'     => trans('global.my_files'),
            'tmpPath'     => $root_dir_full,
            'tmbPath'     => $root_dir_full . '/.tmb',
            'tmbURL'    => url('/uploads/user/' . $root_dir . '/.tmb'),
            'tmbSize'     => '100',
            'tmbCrop'     => false,
            'icon'      => url('packages/elfinder/img/volume_icon_local.png')
          )
        );
      }
      elseif (\Config::get('ftp.active', false))
      {
        
      }
      else
      {
        $roots = array(
          array(
            'driver'    => 'LocalFileSystem',
            'path'      => public_path() . '/uploads/user/' . $root_dir,
            'URL'       => $abs_path_prefix . '/uploads/user/' . $root_dir,
            'accessControl' => 'access',
            'tmpPath'     => public_path() . '/uploads/user/' . $root_dir,
               'uploadMaxSize' => '4M',
            'tmbSize'     => '100',
            'tmbCrop'     => false,
            'icon'      => url('packages/elfinder/img/volume_icon_local.png'),
            'alias'     => trans('global.my_files'),
            'uploadDeny'  => array('text/x-php'),
            'attributes' => array(
              array(
                'pattern' => '/.tmb/',
                 'read' => false,
                 'write' => false,
                 'hidden' => true,
                 'locked' => false
              ),
              array(
                'pattern' => '/.quarantine/',
                 'read' => false,
                 'write' => false,
                 'hidden' => true,
                 'locked' => false
              ),
              array( // hide readmes
                'pattern' => '/\.(txt|html|php|py|pl|sh|xml)$/i',
                'read'   => false,
                'write'  => false,
                'locked' => true,
                'hidden' => true
              )
            )
          ),
          array(
          'driver'    => 'LocalFileSystem',
          'path'      => public_path() . '/stock',
          'URL'       => '/stock',
          'defaults'     => array('read' => false, 'write' => false),
          'alias'     => trans('global.stock'),
          'tmbSize'     => '100',
          'tmbCrop'     => false,
          'icon'      => '/packages/elfinder/img/volume_icon_image.png',
          'attributes' => array(
            array(
              'pattern' => '!^.!',
              'hidden'  => false,
              'read'  => true,
              'write'   => false,
              'locked'  => true
            ),
            array(
              'pattern' => '/.tmb/',
               'read' => false,
               'write' => false,
               'hidden' => true,
               'locked' => false
            ),
            array(
              'pattern' => '/.quarantine/',
               'read' => false,
               'write' => false,
               'hidden' => true,
               'locked' => false
            )
          )
        )
        );
      }

      \Config::set('laravel-elfinder::roots', $roots);

      \Route::get('elfinder/ckeditor4', '\Media\Controller\MediaController@ckEditor');
      \Route::get('elfinder/tinymce', 'Media\Controller\MediaController@showTinyMCE');
      \Route::get('elfinder/standalonepopup/{input_id}/{callback?}', '\Media\Controller\MediaController@popUp');
      \Route::any('elfinder/connector', 'Barryvdh\Elfinder\ElfinderController@showConnector');
    }
  });
}

/*
 |--------------------------------------------------------------------------
 | 404
 |--------------------------------------------------------------------------
 */

App::missing(function($exception) use($url_parts)
{

  /*
   |--------------------------------------------------------------------------
   | Public facing website, 404's are managed at the template controller
   |--------------------------------------------------------------------------
   */

  return App::make('\App\Controller\WebsiteController')->showWebsite($url_parts);
});