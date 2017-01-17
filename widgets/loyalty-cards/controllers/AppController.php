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
   * Main view
   */
  public function getIndex($app, $page)
  {
    $sl = \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));
    $hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';

    echo \View::make('widget::app.index')->with([
      'app' => $app,
      'page' => $page,
      'hashPrefix' => $hashPrefix,
      'sl' => $sl
    ]);
  }

  /**
   * Edit loyalty card admin
   */
  public function editCard($app, $page)
  {
    $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));

    return \View::make('admin.edit-card')->with([
      'app' => $app,
      'page' => $page,
      'sl' => $sl
    ]);
  }

  /**
   * Get loyalty card
   */
  public function getCard($app, $page, $id)
  {
    $hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';
    $found = false;
    $back = '#' . $hashPrefix . '/nav/' . $page->slug;
    $count = 0;

    // id to app_widget_date id
    $widget_data = \Mobile\Model\AppWidgetData::where('app_page_id', $page->id)->where('name', 'LIKE', 'loyalty_cards%')->get();
    foreach ($widget_data as $widget_data_row)
    {
      $card = (! empty($widget_data_row->value)) ? $widget_data_row->value : '';
      $widget_data_row_value = json_decode($card);

      if ($widget_data_row_value->i == '') $widget_data_row_value->i = $count;

      if ($widget_data_row_value->i == $id)
      {
        $widget_data = $widget_data_row;
        break;
      }
    }

    $sl = \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id, 'id' => $id, 'item_id' => $widget_data->id));
    $social_share = (boolean) \Mobile\Controller\WidgetController::getData($page, 'social_share', 1);

    if($card != NULL)
    {
      $card = json_decode($card);
      $now = \Carbon::now();
      $valid_start = \Carbon::parse($card->valid_start)->timezone($app->timezone)->format('Y-m-d H:i:s');
      $valid_end = ($card->valid_end != '') ? \Carbon::parse($card->valid_end)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59' : \Carbon::parse($card->valid_start)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59';

      if(
        ($valid_start >= $now && ($valid_end >= $now || $valid_end == ''))
        || ($valid_start <= $now && $valid_end >= $now)
      )
      {
        $card->id = $id;
        $card->brief_description = str_replace(PHP_EOL, '<br>', $card->brief_description);
        $found = true;
      }
    }

    return \View::make('app.card')->with([
      'app' => $app,
      'page' => $page,
      'sl' => $sl,
      'found' => $found,
      'back' => $back,
      'card' => $card,
      'social_share' => $social_share
    ]);
  }

  /**
   * Get loyalty cards
   */
  public function getCards($app, $page)
  {
    $found = 0;
    $return = array();
    $return_tmp = array();
    $now = \Carbon::now();
    $cards = \Mobile\Controller\WidgetController::getData($page, 'loyalty_cards[]', NULL);
    $logged_in = \App\Controller\AccountController::publicAuth($app->id);

    if ($logged_in) 
    {
      //$user_data = \Mobile\Model\AppUserData::where('app_page_id', $page->id)->where('name', '[Loyalty cards] User ' . \App\Controller\AccountController::publicUser($app->id)->id)->first();
      //$user_data = json_decode($user_data);
    }

    if($cards != NULL)
    {
      foreach($cards as $key => $card)
      {
        $valid_start = \Carbon::parse($card->valid_start)->timezone($app->timezone)->format('Y-m-d H:i:s');
        $valid_end = ($card->valid_end != '') ? \Carbon::parse($card->valid_end)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59' : \Carbon::parse($card->valid_start)->timezone($app->timezone)->format('Y-m-d') . ' 23:59:59';

        if(
          ($valid_start >= $now && ($valid_end >= $now || $valid_end == ''))
          || ($valid_start <= $now && $valid_end >= $now)
        )
        {
          $card->id = $card->i;

          if ($logged_in) 
          {
            // id to app_widget_date id
            $widget_data = \Mobile\Model\AppWidgetData::where('app_page_id', $page->id)->where('name', 'loyalty_cards[' . $card->id . ']')->first();

            $user_data = \Mobile\Model\AppUserData::where('app_page_id', $page->id)->where('name', 'loyalty_card[' . \App\Controller\AccountController::publicUser($app->id)->id . '][' . $card->widget_id . ']')->first();
            $user_data_value_string = (! empty($user_data)) ? $user_data->value : '';
            $user_data_value = json_decode($user_data_value_string);

            $stamps = (isset($user_data_value->stamps)) ? $user_data_value->stamps : 0;
            $redeemed = (isset($user_data_value->redeemed)) ? $user_data_value->redeemed : false;

            $card->stamped = $stamps;
            $card->redeemed = $redeemed;
          }
          else
          {
            $card->stamped = 0;
            $card->redeemed = false;
          }

          $card->brief_description = str_replace(PHP_EOL, '<br>', $card->brief_description);
          if ($card->image != '') $card->image = url($card->image);
          $return_tmp[strtotime($valid_start) + $key] = $card;
          $found++;
        }
      }

      // Sort by date
      //ksort($return_tmp);
      $return['items'] = array_values($return_tmp);
    }

    $return['logged_in'] = $logged_in;

    if($found == 0) $return = array('found' => 0, 'logged_in' => $logged_in);

    return \Response::json($return);
  }

  /**
   * Get card info
   */
  public function checkStatus($app, $page)
  {
    $code = \Request::get('code', '');
    $sl = \Request::get('sl');
    $qs = \App\Core\Secure::string2array($sl);
    $logged_in = \App\Controller\AccountController::publicAuth($app->id);
    $stamps = 0;
    $stamp_response = '';
    $redeemed = false;
    $card = NULL;

    if ($logged_in) 
    {
      $widget_data = \Mobile\Model\AppWidgetData::where('app_page_id', $page->id)->where('name', 'LIKE', 'loyalty_cards%')->get();
      foreach ($widget_data as $widget_data_row)
      {
        $card = (! empty($widget_data_row->value)) ? $widget_data_row->value : '';
        $widget_data_row_value = json_decode($card);
  
        if ($widget_data_row->id == $qs['item_id'])
        {
          $widget_data = $widget_data_row;
          break;
        }
      }

      $user_data = \Mobile\Model\AppUserData::where('app_page_id', $page->id)->where('name', 'loyalty_card[' . \App\Controller\AccountController::publicUser($app->id)->id . '][' . $qs['item_id'] . ']')->first();
      $user_data_value_string = (! empty($user_data)) ? $user_data->value : '';
      $user_data_value = json_decode($user_data_value_string);

      $stamps = (isset($user_data_value->stamps)) ? $user_data_value->stamps : 0;
      $redeemed = (isset($user_data_value->redeemed)) ? $user_data_value->redeemed : false;

      if ($code != '' && $card != NULL)
      {
        $card = json_decode($card);

        if ($code != $card->code)
        {
          $stamp_response = array(
            'msg' => trans('widget::global.incorrect_code') 
          );
        }
        else
        {
          $stamps++;

          $stamp_response = array(
            'msg' => trans('widget::global.correct_code') 
          );

          if (empty($user_data))
          {
            $user_data = new \Mobile\Model\AppUserData;
            $user_data->app_id = $app->id;
            $user_data->app_page_id = $page->id;
            $user_data->name = 'loyalty_card[' . \App\Controller\AccountController::publicUser($app->id)->id . '][' . $qs['item_id'] . ']';
          }

          // Check if card is full
          $redeemed_stats = false;
          if ($stamps > $card->stamps)
          {
            $redeemed_stats = true;
            if (! isset($card->multiple_use)) $card->multiple_use = false;

            if ($card->multiple_use)
            {
              $redeemed = false;
              $stamps = 0;
              $user_data->value = \App\Core\Settings::json(array('stamps' => 0, 'redeemed' => false), $user_data_value_string);
            }
            else
            {
              $redeemed = true;
              $user_data->value = \App\Core\Settings::json(array('redeemed' => true), $user_data_value_string);
            }

            $stamp_response = array(
              'msg' => trans('widget::global.redeemed_freebie', ['freebie' => $card->freebie]) 
            );
          }
          else
          {
            $user_data->value = \App\Core\Settings::json(array('stamps' => $stamps), $user_data_value_string);
          }

          $user_data->save();

          // Add Stat
          $user_data_stat = new \Mobile\Model\AppUserData;
          $user_data_stat->app_id = $app->id;
          $user_data_stat->app_page_id = $page->id;
          $user_data_stat->name = 'loyalty_card';
          $user_data_stat->value = \App\Core\Settings::json(array('public_user_id' => \App\Controller\AccountController::publicUser($app->id)->id, 'card' => $qs['item_id'], 'redeemed' => $redeemed_stats));
          $user_data_stat->save();
        }
      }
    }

    $return = array(
      'logged_in' => $logged_in,
      'stamps' => $stamps,
      'redeemed' => $redeemed,
      'response' => $stamp_response
    );

    return \Response::json($return);
  }
}