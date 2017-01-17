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

    $coupons = \Mobile\Controller\WidgetController::getData($page, 'coupons[]', NULL);
    $coupons = (count($coupons) == 0) ? false : true;

    echo \View::make('widget::app.index')->with([
      'app' => $app,
      'page' => $page,
      'sl' => $sl,
      'coupons' => $coupons,
      'hashPrefix' => $hashPrefix
    ]);
  }

  /**
   * Edit coupon
   */
  public function editCoupon($app, $page)
  {
    $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));

    $all_currencies = trans('currencies');
    $currencies = array();
    foreach($all_currencies as $currency => $currency_symbol)
    {
      $currencies[$currency] = $currency_symbol[0] . ' (' . $currency_symbol[1] . ')';
    }

    return \View::make('admin.edit-coupon')->with([
      'app' => $app,
      'page' => $page,
      'currencies' => $currencies,
      'sl' => $sl
    ]);
  }

  /**
   * Check if coupon is redeemed with fingerprint
   */
  public static function isRedeemed($page_id, $fingerprint, $code)
  {
    if($fingerprint == '') return 1;

    $check = \Mobile\Model\AppUserData::where('app_page_id', $page_id)->where('name', $code . ';' . $fingerprint . '')->first();

    return (empty($check)) ? 0 : 1;
  }

  /**
   * Currency formatter
   */
  public static function formatCurrency($amount)
  {
    $num_decimals = (intval($amount) == $amount) ? 0 :2;
    return number_format($amount, $num_decimals);
  }

  /**
   * Get coupon
   */
  public function getCoupon($app, $page, $id)
  {
    $hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';
    $found = false;
    $back = '#' . $hashPrefix . '/nav/' . $page->slug;
    $count = 0;

    // id to app_widget_date id
    $widget_data = \Mobile\Model\AppWidgetData::where('app_page_id', $page->id)->where('name', 'LIKE', 'coupons%')->get();
    foreach ($widget_data as $widget_data_row)
    {
      $coupon = (! empty($widget_data_row->value)) ? $widget_data_row->value : '';
      $widget_data_row_value = json_decode($coupon);

      if ($widget_data_row_value->i == '') $widget_data_row_value->i = $count;

      if ($widget_data_row_value->i == $id)
      {
        $widget_data = $widget_data_row;
        break;
      }
      $count++;
    }

    $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id, 'item_id' => $widget_data->id));
    $social_share = (boolean) \Mobile\Controller\WidgetController::getData($page, 'social_share', 1);
    $logged_in = \App\Controller\AccountController::publicAuth($app->id);

    if($coupon != NULL)
    {
      $coupon = json_decode($coupon);
      $now = \Carbon::now();
      $valid_start = \Carbon::parse($coupon->valid_start)->timezone($app->timezone)->format('Y-m-d H:i:s');
      $valid_end = ($coupon->valid_end != '') ? \Carbon::parse($coupon->valid_end)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59' : \Carbon::parse($coupon->valid_start)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59';

      if($valid_start <= $now && $valid_end >= $now)
      {
        $coupon->id = $id;
        $coupon->brief_description = str_replace(PHP_EOL, '<br>', $coupon->brief_description);
        $found = true;
      }
    }

    return \View::make('app.coupon')->with([
      'app' => $app,
      'page' => $page,
      'sl' => $sl,
      'found' => $found,
      'back' => $back,
      'coupon' => $coupon,
      'social_share' => $social_share
    ]);
  }

  /**
   * Get coupons
   */
  public function getCoupons($app, $page)
  {
    $found = 0;
    $return_tmp = array();
    $now = \Carbon::now();
    $coupons = \Mobile\Controller\WidgetController::getData($page, 'coupons[]', NULL);
    $logged_in = \App\Controller\AccountController::publicAuth($app->id);

    if($coupons != NULL)
    {
      foreach($coupons as $key => $coupon)
      {
        $valid_start = \Carbon::parse($coupon->valid_start)->timezone($app->timezone)->format('Y-m-d H:i:s');
        $valid_end = ($coupon->valid_end != '') ? \Carbon::parse($coupon->valid_end)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59' : \Carbon::parse($coupon->valid_start)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59';
  
        if($valid_start <= $now && $valid_end >= $now)
        {
          // Redeem check
          if ($logged_in) 
          {
            // id to app_widget_date id
            $widget_data = \Mobile\Model\AppWidgetData::where('app_page_id', $page->id)->where('name', 'coupons[' . $coupon->widget_id . ']')->first();

            $user_data = \Mobile\Model\AppUserData::where('app_page_id', $page->id)->where('name', 'coupon[' . \App\Controller\AccountController::publicUser($app->id)->id . '][' . $coupon->widget_id . ']')->first();
            $user_data_value_string = (! empty($user_data)) ? $user_data->value : '';
            $user_data_value = json_decode($user_data_value_string);

            $redeemed = (isset($user_data_value->redeemed)) ? (boolean) $user_data_value->redeemed : false;

            $coupon->redeemed = $redeemed;
          }
          else
          {
            $coupon->redeemed = false;
          }

          $coupon->id = $key;
          $coupon->image = ($coupon->image != '') ? url($coupon->image) : url('widgets/coupons/assets/img/coupon.png');

          $return_tmp[] = $coupon;
          $found++;
        }
      }
      $return['items'] = $return_tmp;
    }

    $return['logged_in'] = $logged_in;

    if($found == 0) $return = array('found' => 0, 'logged_in' => $logged_in);

    return \Response::json($return);
  }

  /**
   * Check if redeemed
   */
  public function checkRedeemed($app, $page)
  {
    $sl = \Request::get('sl');
    $qs = \App\Core\Secure::string2array($sl);
    $logged_in = \App\Controller\AccountController::publicAuth($app->id);
    $redeemed = false;
    $coupon = NULL;

    if ($logged_in)
    {
      $widget_data = \Mobile\Model\AppWidgetData::where('app_page_id', $page->id)->where('name', 'LIKE', 'coupons%')->get();
      foreach ($widget_data as $widget_data_row)
      {
        $coupon = (! empty($widget_data_row->value)) ? $widget_data_row->value : '';
        $widget_data_row_value = json_decode($coupon);
  
        if ($widget_data_row->id == $qs['item_id'])
        {
          $widget_data = $widget_data_row;
          break;
        }
      }

      $user_data = \Mobile\Model\AppUserData::where('app_page_id', $page->id)->where('name', 'coupon[' . \App\Controller\AccountController::publicUser($app->id)->id . '][' . $qs['item_id'] . ']')->first();
      $user_data_value_string = (! empty($user_data)) ? $user_data->value : '';
      $user_data_value = json_decode($user_data_value_string);

      $redeemed = (isset($user_data_value->redeemed)) ? (boolean) $user_data_value->redeemed : false;
    }

    $return = array(
      'logged_in' => $logged_in,
      'redeemed' => $redeemed
    );

    return \Response::json($return);
  }

  /**
   * Redeem coupon
   */
  public function redeemCoupon($app, $page)
  {
    $sl = \Request::get('sl');
    $qs = \App\Core\Secure::string2array($sl);
    $logged_in = \App\Controller\AccountController::publicAuth($app->id);
    $code = \Input::get('code', '');

    if($code == '') die('No code');

    if ($logged_in)
    {
      $public_user_id = \App\Controller\AccountController::publicUser($app->id)->id;
      $coupons = \Mobile\Controller\WidgetController::getData($page, 'coupons[]', NULL);

      if($coupons == NULL) die('Coupon not found');

      $found = false;
      foreach($coupons as $coupon)
      {
        if($coupon->code == $code)
        {
          $found = true;
          break;
        }
      }

      if($found === false) die('Code not found');

      // Add Stat
      $user_data_stat = new \Mobile\Model\AppUserData;
      $user_data_stat->app_id = $app->id;
      $user_data_stat->app_page_id = $page->id;
      $user_data_stat->name = 'coupon[' . $public_user_id . '][' . $qs['item_id'] . ']';
      $user_data_stat->value = \App\Core\Settings::json(array('public_user_id' => $public_user_id, 'coupon' => $qs['item_id'], 'redeemed' => true));
      $user_data_stat->save();

      $return = array(
        'redeemed' => true
      );

      return \Response::json($return);
    }
    else
    {
      die('Not logged in');
    }
  }
}