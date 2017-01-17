<?php
namespace Mobile\Controller;

use View;

/*
|--------------------------------------------------------------------------
| AppEdit controller
|--------------------------------------------------------------------------
|
| App editor related logic
|
*/

class AppEditController extends \BaseController {

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
   * Update app title
   */
  public function postAppTitle()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('pk', '');
    $qs = \App\Core\Secure::string2array($sl);

    $name = \Input::get('value', '');

    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    if(! empty($app))
    {
      $app->name = $name;
      $app->save();
    }

    return \Response::json(array('status' => 'success'));
  }

  /**
   * Update app campaign
   */
  public function postAppCampaign()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('pk', '');
    $qs = \App\Core\Secure::string2array($sl);

    $campaign_id = \Input::get('value', '');

    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    if(! empty($app))
    {
      $app->campaign_id = $campaign_id;
      $app->save();
    }

    return \Response::json(array('status' => 'success'));
  }

  /**
   * Save widget content
   */
  public function postSaveWidget()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $save_as_row = \Input::get('save_as_row', false);
    $widget = \Input::get('widget', '');
    $qs = \App\Core\Secure::string2array($sl);
    $data = \Input::except('sl', 'widget', '_token', 'global_values', 'save_as_row');

    // Get widget globals
    $widget_config = \Mobile\Controller\WidgetController::loadWidgetConfig($widget);
    $global_values = (isset($widget_config['global'])) ? $widget_config['global'] : array();

    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    $page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

    $pages = $app->appPages()->where('widget', $widget)->where('id', '<>', $page->id)->get();

    if($save_as_row !== false)
    {
      $row_nr = 0;
      if(is_array(\Input::get($save_as_row)))
      {
        foreach(\Input::get($save_as_row) as $row)
        {
          $data[$save_as_row . '[' . $row_nr . ']'] = $row;
          $row_nr++;
        }
      }

      // Delete removed rows
      $row_nr_check = 0;
      $app_widget_data = \Mobile\Model\AppWidgetData::where('app_page_id', $page->id)->get();
      foreach($app_widget_data as $widget_data)
      {
        if(starts_with($widget_data->name, $save_as_row . '['))
        {
          if($row_nr_check >= $row_nr)
          {
            $widget_data->forceDelete();
          }
          $row_nr_check++;
        }
      }
    }

    if(! empty($page))
    {
      foreach($data as $key => $value)
      {
        if($key != $save_as_row)
        {
          \Mobile\Controller\WidgetController::setData($page, $key, $value);

          if(in_array($key, $global_values))
          {
            if(count($pages) > 0)
            {
              foreach($pages as $widget)
              {
                \Mobile\Controller\WidgetController::setData($widget, $key, $value);
              }
            }
          }

          // Parse data for `page` table
          if(strpos($key, ':') !== false)
          {
            $column = explode(':', $key);
            $page->{$column[0]} = $value;
            $page->save();
          }
        }
      }
    }

    return \Response::json(array(
      'result' => 'success'
    ));
  }

  /**
   * Update page name
   */
  public function postPageName()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $name = \Input::get('name', '');

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    $page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

    if($name != '' && ! empty($page))
    {
      $page->name = $name;
      $page->slug = \App\Core\Slug::app($name, $app->id, $page->id);
      $page->save();
    }

    return \Response::json(array('status' => 'success', 'slug' => $page->slug));
  }

  /**
   * Update app background image
   */
  public function postBgAppImage()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $image = \Input::get('image', '');

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    if($image != '' && ! empty($app))
    {
      // Using full path like `public_path() . $image;` will remove the original
      $app->background_smarthpones = url($image);
      $app->save();
    }

    return \Response::json(array('status' => 'success'));
  }

  /**
   * Remove app background image
   */
  public function postBgAppImageRemove()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $none = (boolean) \Input::get('none', 0);
    $qs = \App\Core\Secure::string2array($sl);

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    if(! empty($app))
    {
      $bg = ($none) ? url('assets/images/interface/1x1.gif') : STAPLER_NULL;
      $app->background_smarthpones = $bg;
      $app->save();
    }

    return \Response::json(array('status' => 'success'));
  }

  /**
   * Custom app icon
   */
  public function postCustomAppIcon()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $image = \Input::get('image', '');

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    if($image != '' && ! empty($app))
    {
      // Using full path like `public_path() . $image;` will remove the original
      $app->header = url($image);
      $app->save();
    }

    return \Response::json(array('status' => 'success', 'icon40' => $app->header->url('icon40')));
  }

  /**
   * Remove custom app icon
   */
  public function postCustomAppIconRemove()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    if(! empty($app))
    {
      $app->header = STAPLER_NULL;
      $app->save();
    }

    return \Response::json(array('status' => 'success'));
  }

  /**
   * Change app icon
   */
  public function postAppIcon()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $icon = \Input::get('icon', '');

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    if($icon != '' && ! empty($app))
    {
      $app->icon = $icon;
      $app->save();
    }

    return \Response::json(array('status' => 'success'));
  }

  /**
   * Update page background image
   */
  public function postBgPageImage()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $image = \Input::get('image', '');

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    $page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

    if($image != '' && ! empty($page))
    {
      // Using full path like `public_path() . $image;` will remove the original
      $page->background_smarthpones = url($image);
      $page->save();
    }

    return \Response::json(array('status' => 'success'));
  }

  /**
   * Remove page background image
   */
  public function postBgPageImageRemove()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    $page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

    if(! empty($page))
    {
      $page->background_smarthpones = STAPLER_NULL;
      $page->save();
    }

    return \Response::json(array('status' => 'success'));
  }

  /**
   * Update app layout
   */
  public function postLayout()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $layout = \Input::get('layout', '');

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    if($layout != '' && ! empty($app))
    {
      $app->layout = $layout;
      $app->save();
    }

    return \Response::json(array('status' => 'success'));
  }

  /**
   * Update app theme
   */
  public function postTheme()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $theme = \Input::get('theme', '');

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    if($theme != '' && ! empty($app))
    {
      $app->theme = $theme;
      $app->save();
    }

    return \Response::json(array('status' => 'success'));
  }

  /**
   * New page
   */
  public function postPage()
  {
    // Get security link with site_id, page_id
    $sl = \Input::get('sl', '');
    $widget = \Input::get('widget', '');
    $qs = \App\Core\Secure::string2array($sl);

    // Get widget info
    $widget_config = \Mobile\Controller\WidgetController::loadWidgetConfig($widget);

    // Get app
    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])
      ->where('user_id', '=', $this->parent_user_id)->first();

    // Check if multiple instances are allowed
    $count_widget = $app->appPages()->where('widget', $widget)->count();

    if($widget_config['allow_multiple'] == false && $count_widget >= 1)
    {
      return \Response::json(array(
        'result' => 'error',
        'msg' => trans('global.one_instance_allowed')
      ));
    }

    // Last sibling
    $root = $app->appPages->first();
    if(! empty($root))
    { 
      $root = $app->appPages->first()->orderBy('lft', 'DESC')->first();
    }
    else
    {
      // Check if type can be homepage
      if($widget_config['can_be_home'] == false)
      {
        return \Response::json(array(
          'result' => 'error',
          'msg' => trans('global.cant_be_homepage')
        ));
      }
    }

    // Create page
    $page = new \Mobile\Model\AppPage;
    $name = ($widget_config['default_name'] != NULL) ? $widget_config['default_name'] : $widget_config['name'];

    $page->app_id = $qs['app_id'];
    $page->widget = $widget;
    $page->name = $name;
    $page->slug = \App\Core\Slug::app($name, $app->id);
    $page->save();

    if($root == NULL)
    {
      $page->makeRoot();
    }
    else
    {
      $page->makeSiblingOf($root);
    }

    // Set globals
    if(isset($widget_config['global']))
    {
      // Load other page from app
      $other_page = $app->appPages()->where('widget', $widget)->where('id', '<>', $page->id)->first();

      if(! empty($other_page))
      {
        foreach($widget_config['global'] as $global)
        {
          $val = \Mobile\Controller\WidgetController::getData($other_page, $global);
          \Mobile\Controller\WidgetController::setData($page, $global, $val);  
        }
      }
    }

    $sl_app_page = \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));

    return \Response::json(array(
      'result' => 'success',
      'sl' => $sl_app_page,
      'id' => $page->id,
      'slug' => $page->slug,
      'icon' => $widget_config['icon'],
      'color' => $widget_config['color'],
      'name' => $name
    ));
  }

  /**
   * Get app page ajax
   */
  public function getPage()
  {
    // Get security link
    $id = \Input::get('id', '');
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $app_id = $qs['app_id'];
    $page_id = $qs['page_id'];

    $app = \Mobile\Model\App::where('id', '=', $app_id)
      ->where('user_id', '=', $this->parent_user_id)->first();

    $page = $app->appPages()->where('id', '=', $page_id)->first();

    $sl_app = \App\Core\Secure::array2string(array('app_id' => $app->id));

    $hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';

    if(! empty($page))
    {
      if($id == 'page-general-tab')
      {
        $widget = \Mobile\Controller\WidgetController::loadWidgetConfig($page->widget);
        $icon = ($page->icon != '') ? $page->icon : $widget['default_icon'];

        return View::make('app.mobile.page.general', array(
          'app' => $app,
          'page' => $page,
          'widget' => $widget,
          'icon' => $icon,
          'sl' => $sl,
          'sl_app' => $sl_app,
          'hashPrefix' => $hashPrefix
        ));
      }
      elseif($id == 'page-content-tab')
      {
        return View::make('app.mobile.page.content', array(
          'app' => $app,
          'page' => $page,
          'sl' => $sl,
          'sl_app' => $sl_app
        ));
      }
      elseif($id == 'page-design-tab')
      {
        return View::make('app.mobile.page.design', array(
          'app' => $app,
          'page' => $page,
          'sl' => $sl,
          'sl_app' => $sl_app
        ));
      }
    }
  }

  /**
   * Delete app page
   */
  public function getPageDelete()
  {
    // Get security link
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $app_id = $qs['app_id'];
    $page_id = $qs['page_id'];

    $app = \Mobile\Model\App::where('id', '=', $app_id)
      ->where('user_id', '=', $this->parent_user_id)->first();

    $page = $app->appPages()->where('id', '=', $page_id)->first();

    if(! empty($page))
    {
      // Delete
      $page->forceDelete();
    }
    return \Response::json(array('status' => 'success'));
  }

  /**
   * Toggle app page visibility
   */
  public function getPageToggle()
  {
    // Get security link
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $app_id = $qs['app_id'];
    $page_id = $qs['page_id'];

    $app = \Mobile\Model\App::where('id', '=', $app_id)
      ->where('user_id', '=', $this->parent_user_id)->first();

    $page = $app->appPages()->where('id', '=', $page_id)->first();

    if(! empty($page))
    {
      if($page->hidden == 0)
      {
        $page->hidden = 1;
      }
      else
      {
        $page->hidden = 0;        
      }
      $page->save();
    }
    return \Response::json(array('status' => 'success'));
  }

  /**
   * Sort app pages
   */
  public function postPageSort()
  {
    // Get security link
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    // Get nodes
    $node = \Input::get('node', '');
    $node_prev = \Input::get('node_prev', '');
    $node_next = \Input::get('node_next', '');

    $app_id = $qs['app_id'];

    $app = \Mobile\Model\App::where('id', '=', $app_id)
      ->where('user_id', '=', $this->parent_user_id)->first();

    $page = $app->appPages()->where('id', '=', $node)->first();

    if(! empty($page))
    {
      // Reorder
      if(is_numeric($node_prev))
      {
        $node_side = $app->appPages()->where('id', '=', $node_prev)->first();
        $page->moveToRightOf($node_side);
      }
      elseif(is_numeric($node_next))
      {
        $node_side = $app->appPages()->where('id', '=', $node_next)->first();
        $page->moveToLeftOf($node_side);
      }
    }
    return \Response::json(array('status' => 'success'));
  }

  /**
   * Get icon js for http://victor-valencia.github.io/bootstrap-iconpicker/
   */
  public function getIconJs()
  {
    $icons = \Config::get('icons');
    $js = '';

    $js .= ";(function($){
  $.iconset_ionicon = {
    iconClass: '',
    iconClassFix: 'ion-',
    icons: [
      'ionic',";

    foreach($icons as $class => $name)
    {
      $js .= "'" . substr($class, 4, strlen($class) - 4) . "',";

    }

    $js = rtrim($js, ',');

    $js .= "]};
  
})(jQuery);";

    $response = \Response::make($js);
    $response->header('Content-Type', 'application/javascript');

    return $response;
  }

  /**
   * Save icon
   */
  public function postIconPicker()
  {
    $icon = \Input::get('icon', '');
    // Get security link
    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $app_id = $qs['app_id'];
    $page_id = $qs['page_id'];

    $app = \Mobile\Model\App::where('id', '=', $app_id)
      ->where('user_id', '=', $this->parent_user_id)->first();

    $page = $app->appPages()->where('id', '=', $page_id)->first();

    if(! empty($page))
    {
      $page->icon = $icon;
      $page->save();
    }
    return \Response::json(array('status' => 'success'));
  }
}
