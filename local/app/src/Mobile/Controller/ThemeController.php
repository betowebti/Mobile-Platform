<?php
namespace Mobile\Controller;

/*
|--------------------------------------------------------------------------
| Theme controller
|--------------------------------------------------------------------------
|
| Theme related logic
|
*/

class ThemeController extends \BaseController {

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
   * Load all theme config
   */
  public static function loadAllThemeConfig($first = NULL)
  {
    $first_element = NULL;
    $themes_dir = public_path() . '/themes/';
    $themes = \File::directories($themes_dir);

    $theme_config = array();

    foreach($themes as $theme)
    {
      $theme_config_file = $theme . '/config/theme.php';
      $theme_lang_file = $theme . '/lang/' . \App::getLocale() . '/global.php';

      if (! \File::exists($theme_lang_file)) $theme_lang_file = $theme . '/lang/en/global.php';

      if(\File::exists($theme_config_file) && \File::exists($theme_lang_file))
      {
        $config = \File::getRequire($theme_config_file);
        $lang = \File::getRequire($theme_lang_file);
        $config['dir'] = basename($theme);

        if($config['active'])
        {
          if($first == $config['dir'])
          {
            $first_element[$lang['name']] = $config;
          }
          else
          {
            $theme_config[$lang['name']] = $config;
          }
        }
      }
    }

    ksort($theme_config);

    if($first_element != NULL)
    {
      $theme_config = array_merge($first_element, $theme_config);
    }

    return $theme_config;
  }

  /**
   * Load theme config
   */
  public static function loadThemeConfig($theme)
  {
    $theme_config_file = public_path() . '/themes/' . $theme . '/config/theme.php';
    $theme_lang_file = public_path() . '/themes/' . $theme . '/lang/' . \App::getLocale() . '/global.php';

    if (! \File::exists($theme_lang_file)) $theme_lang_file = $theme . '/lang/en/global.php';

    if(\File::exists($theme_config_file) && \File::exists($theme_lang_file))
    {
      $config = \File::getRequire($theme_config_file);
      $lang = \File::getRequire($theme_lang_file);
      $config['dir'] = $theme;
      $config['name'] = $lang['name'];
    }

    return $config;
  }

  /**
   * Import themes
   */
  public static function getImport()
  {
    $theme_generate = '';
    $themes_dir = public_path() . '/themes/';
    $import_dir = public_path() . '/themes-generate/';
    $themes = \File::files($import_dir);

    foreach($themes as $theme)
    {
      $name = str_replace('.png', '', basename($theme));

      // Copy new dir
      \File::copyDirectory($themes_dir . '/birmingham', $themes_dir . '/' . $name);

      // Empty images dir
      \File::cleanDirectory($themes_dir . '/' . $name . '/assets/img/');

      // Copy image
      \File::copy($theme, $themes_dir . '/' . $name . '/assets/img/background-phone.png');

      // Update name
      $fullname = ucwords(str_replace('-', ' ', $name));
$content = '<?php

return array(

  /*
  |--------------------------------------------------------------------------
  | Theme translation
  |--------------------------------------------------------------------------
  */

  "name" => "' . $fullname . '"
);
';

      \File::put($themes_dir . '/' . $name . '/lang/en/global.php', $content);


      $theme_generate .= "'" . $name . "' => array('type' =>'light', 'dark_color' =>'#222222', 'light_color' =>'#efefef')," . PHP_EOL;
    }

    echo $theme_generate;
  }

  /**
   * Generate template
   */
  public static function getGenerate()
  {
    $screenshot = false;
    $screenshot_bg = false;
    $only_screenshot_bg = false;
    $preview = \Request::get('preview', false);
    $output_sass = false;

    $themes = array(
      'blog' => array('type' =>'light', 'dark_color' =>'#222', 'light_color' =>'#cacbd0', 'tab_active' =>'#243542'),
      'breakfast' => array('type' =>'light', 'dark_color' =>'#222', 'light_color' =>'#fff', 'text_color' =>'#222'),
      'business' => array('type' =>'dark', 'dark_color' =>'#78abc8', 'light_color' =>'#fff'),
      'concrete' => array('type' =>'dark', 'dark_color' =>'#222', 'light_color' =>'#fff', 'tab_active' =>'#888'),
      'education' => array('type' =>'light', 'dark_color' =>'#222', 'light_color' =>'#fff', 'text_color' =>'#222'),
      'events' => array('type' =>'light', 'dark_color' =>'#fc0000', 'light_color' =>'#f4e0da', 'tab_active' =>'#222'),
      'fireplace' => array('type' =>'dark', 'dark_color' =>'#222', 'light_color' =>'#fff', 'tab_active' =>'#888'),
      'music' => array('type' =>'dark', 'dark_color' =>'#555', 'light_color' =>'#fff'),
      'other' => array('type' =>'light', 'dark_color' =>'#222', 'light_color' =>'#57a0f2', 'text_color' =>'#222'),
      'photography' => array('type' =>'light', 'dark_color' =>'#222', 'light_color' =>'#fff', 'text_color' =>'#111'),
      'restaurants' => array('type' =>'dark', 'dark_color' =>'#222', 'light_color' =>'#fff', 'font_family' =>'serif', 'tab_active' =>'#fcdd91', 'text_color' =>'#efefef'),
      'sunset' => array('type' =>'light', 'dark_color' =>'#222', 'light_color' =>'#fff', 'text_color' =>'#222'),
      'water' => array('type' =>'dark', 'dark_color' =>'#555', 'light_color' =>'#fff', 'tab_active' =>'#888'),
      'wood' => array('type' =>'light', 'dark_color' =>'#222', 'light_color' =>'#fff', 'text_color' =>'#222')
    );

    $themes_pack = array(
      'galaxy' => array('type' =>'dark', 'dark_color' =>'#261136', 'light_color' =>'#f7f7f7', 'tab_active' =>'#ffc9ec', 'text_color' =>'#ffc9ec', 'link_color' =>'#ffc9ec'),
      'birmingham' => array('type' =>'dark', 'dark_color' =>'#261136', 'light_color' =>'#f7f7f7', 'tab_active' =>'#7d98ac', 'text_color' =>'#f6eee2', 'link_color' =>'#f6eee2'),
      'black-office' => array('type' =>'light', 'dark_color' =>'#0065d8', 'light_color' =>'#e3edff', 'opacity' => 0.8),
      'blue-planet' => array('type' =>'dark', 'dark_color' =>'#1268eb', 'light_color' =>'#a6e5ff'),
      'buffet' => array('type' =>'light', 'dark_color' =>'#f2ebe0', 'light_color' =>'#ea9c02'),
      'city-bokeh' => array('type' =>'light', 'dark_color' =>'#282222', 'light_color' =>'#edfaff', 'positive' =>'#3d4746'),
      'colored-buildings' => array('type' =>'light', 'dark_color' =>'#a1b303', 'light_color' =>'#f3f7ff', 'opacity' => 0.8, 'transparent_bg' => '#ffffff'),
      'folded-paper' => array('type' =>'light', 'dark_color' =>'#474747', 'light_color' =>'#cacaca'),
      'forest-fruits' => array('type' =>'light', 'dark_color' =>'#fd3538', 'light_color' =>'#e9f5fa', 'opacity' => 0.7, 'transparent_bg' => '#ffffff', 'stable' => '#dcf02c'),
      'galleria-milan' => array('type' =>'dark', 'dark_color' =>'#393748', 'light_color' =>'#a7cfff'),
      'gallery' => array('type' =>'light', 'dark_color' =>'#b9b9b9', 'light_color' =>'#fbfbfb'),
      'gold-foil' => array('type' =>'light', 'dark_color' =>'#3a2203', 'light_color' =>'#ffebaf', 'opacity' => 0.7, 'transparent_bg' => '#ffffff'),
      'hexagon' => array('type' =>'light', 'dark_color' =>'#2e2e2e', 'light_color' =>'#e8e8e8', 'opacity' => 0.7, 'transparent_bg' => '#ffffff'),
      'hofmann' => array('type' =>'dark', 'dark_color' =>'#533b3b', 'light_color' =>'#f6ed81', 'opacity' => 0.7),
      'lens' => array('type' =>'dark', 'dark_color' =>'#050505', 'light_color' =>'#bfffd5', 'opacity' => 0.7),
      'microphone' => array('type' =>'dark', 'dark_color' =>'#17263b', 'light_color' =>'#e1eff5', 'opacity' => 0.7),
      'old-paper' => array('type' =>'light', 'dark_color' =>'#00857f', 'light_color' =>'#fef7b0', 'opacity' => 0.7),
      'oranges' => array('type' =>'light', 'dark_color' =>'#6e0201', 'light_color' =>'#f5dbd6', 'opacity' => 0.7),
      'piano' => array('type' =>'light', 'dark_color' =>'#000000', 'light_color' =>'#5c5c5c', 'light' =>'#b5b5b5',  'transparent_bg' => '#0000000', 'opacity' => 0.7),
      'stadium' => array('type' =>'light', 'dark_color' =>'#312e02', 'light_color' =>'#f5ea79', 'opacity' => 0.7),
      'strawberry-martini' => array('type' =>'light', 'dark_color' =>'#7a010c', 'light_color' =>'#c7d8ec', 'transparent_bg' => '#ffffff', 'opacity' => 0.85),
      'striped' => array('type' =>'dark', 'dark_color' =>'#221616', 'light_color' =>'#ebe9ee', 'light' =>'#3b3434'),
      'sweet-dessert' => array('type' =>'light', 'dark_color' =>'#056a90', 'light_color' =>'#e8d9cf', 'transparent_bg' => '#ffffff', 'opacity' => 0.75),
      'tiger' => array('type' =>'dark', 'dark_color' =>'#232123', 'light_color' =>'#ecc08d', 'light' =>'#4e3019'),
      'water-splash' => array('type' =>'light', 'dark_color' =>'#1e291b', 'light_color' =>'#f5faf4', 'opacity' => 0.75),
      'white-flower' => array('type' =>'dark', 'dark_color' =>'#000000', 'light_color' =>'#e1e1e1', 'light' =>'#353535', 'opacity' => 0.75),
      'white-silk' => array('type' =>'light', 'dark_color' =>'#59565c', 'light_color' =>'#e4dede', 'opacity' => 0.75),
      'strawberries' => array('type' =>'light', 'dark_color' =>'#840102', 'light_color' =>'#f6bcc7', 'transparent_bg' => '#ffffff', 'opacity' => 0.80),
    );

    $themes = array_merge($themes, $themes_pack);

    $themes = array(
      'business' => array('type' =>'dark', 'dark_color' =>'#78abc8', 'light_color' =>'#fff'),
    );

    foreach($themes as $_theme => $_val)
    {
      $theme = $_theme;

      // Theme paths
      $target_css = public_path() . '/themes/' . $theme . '/assets/css/style.css';
      $bg_image = public_path() . '/themes/' . $theme . '/assets/img/background-phone.png';
      $preview_image = public_path() . '/themes/' . $theme . '/assets/img/preview.png';
      $preview_bg = public_path() . '/themes/' . $theme . '/assets/img/preview-bg.png';

      if ($screenshot_bg)
      {
        \Mobile\Controller\ThemeController::thumbnail($bg_image, $preview_bg, 1);
      }

      if (! $only_screenshot_bg)
      {

      // Extract theme bg image colors
      $color_extract = new \League\ColorExtractor\Client;
      $image = $color_extract->loadPng($bg_image);
      //$image->setMinColorRatio(0);
      $palette = $image->extract(7);

      if(! isset($palette[0])) $palette[0] = '#f8f8f8';
      if(! isset($palette[1])) $palette[1] = '#387ef5';
      if(! isset($palette[2])) $palette[2] = '#11c1f3';
      if(! isset($palette[3])) $palette[3] = '#33cd5f';
      if(! isset($palette[4])) $palette[4] = '#ffc900';
      if(! isset($palette[5])) $palette[5] = '#ef473a';
      if(! isset($palette[6])) $palette[6] = '#886aea';

      // Dark or light theme?
      $theme_type = $_val['type']; //'light';

      // Sort colors
      $palette = \Mobile\Controller\ThemeController::cf_sort_hex_colors($palette);

      if($theme_type == 'light')
      {
        $palette = array_reverse($palette);
      }

      // Ionic paths
      $ionic_scss_path = public_path() . '/build/vendor/ionic/scss/';
      $ionic_scss = \File::get($ionic_scss_path . 'ionic.scss');

      // Remove icons
      $ionic_scss = str_replace('"ionicons/ionicons.scss",', '', $ionic_scss);

      // Variables
      $stable = (isset($_val['stable'])) ? $_val['stable'] : $palette[0];
      $positive = (isset($_val['positive'])) ? $_val['positive'] : $palette[1];
      $calm = (isset($_val['calm'])) ? $_val['calm'] : $palette[2];
      $balanced = (isset($_val['balanced'])) ? $_val['balanced'] : $palette[3];
      $energized = (isset($_val['energized'])) ? $_val['energized'] : $palette[4];
      $assertive = (isset($_val['assertive'])) ? $_val['assertive'] : $palette[5];
      $royal = (isset($_val['royal'])) ? $_val['royal'] : $palette[6];

      $stable_contrast = \Mobile\Controller\ThemeController::getContrast($stable);
      $positive_contrast = \Mobile\Controller\ThemeController::getContrast($positive);
      $calm_contrast = \Mobile\Controller\ThemeController::getContrast($calm);
      $balanced_contrast = \Mobile\Controller\ThemeController::getContrast($balanced);
      $energized_contrast = \Mobile\Controller\ThemeController::getContrast($energized);
      $assertive_contrast = \Mobile\Controller\ThemeController::getContrast($assertive);
      $royal_contrast = \Mobile\Controller\ThemeController::getContrast($royal);
      if(isset($_val['tab_active'])) $tab_active_contrast = \Mobile\Controller\ThemeController::getContrast($_val['tab_active']);

      if($theme_type == 'light')
      {
        $light = $_val['light_color'];
        $dark = $_val['dark_color'];
        $base_color = '#222222';
        $link_color = $_val['dark_color'];
        $link_color_hover = $_val['dark_color'];
      }
      elseif($theme_type == 'dark')
      {
        $light = $_val['dark_color'];
        $dark = $_val['light_color'];
        $base_color = '#ffffff';
        $link_color = $_val['light_color'];
        $link_color_hover = $_val['light_color'];

        $stable = $stable_contrast; //$palette[0];
        $stable_contrast = $palette[0]; //$stable_contrast;
      }

      $opacity = (isset($_val['opacity'])) ? $_val['opacity'] : 0.5;
      $transparent_bg = (isset($_val['transparent_bg'])) ? $_val['transparent_bg'] : $palette[0];

      $light_contrast = \Mobile\Controller\ThemeController::getContrast($light);
      $dark_contrast = \Mobile\Controller\ThemeController::getContrast($dark);

      if ($light_contrast == $stable_contrast && $theme_type == 'light') $stable_contrast = '#222';
      if ($dark_contrast == $stable_contrast && $theme_type == 'dark') $stable_contrast = '#fff';

      $light = (isset($_val['light'])) ? $_val['light'] : $light;

      $text_color = (isset($_val['text_color'])) ? $_val['text_color']: $light_contrast;

      if(isset($_val['font_family'])) 
      {
        $font_family = $_val['font_family'];
        $font_family_light = $_val['font_family'];
        $font_family_serif = $_val['font_family'];
      }
      else
      {
        $font_family = '"Helvetica Neue", "Roboto", sans-serif';
        $font_family_light = '"HelveticaNeue-Light", "Roboto-Light", sans-serif-light';
        $font_family_serif = 'serif';
      }

      $link_color = (isset($_val['link_color'])) ? $_val['link_color'] : $link_color;

      // Transparent background for text
      $rgb = \Mobile\Controller\ThemeController::hex2rgb($transparent_bg);

      $all_colors = compact('stable', 'stable_contrast', 'positive', 'positive', 'calm', 'calm', 'balanced', 'balanced', 'energized', 'energized', 'assertive', 'assertive', 'royal', 'royal', 'light', 'dark', 'light_contrast', 'dark_contrast', 'text_color', 'base_color', 'link_color', 'link_color_hover');

      foreach ($all_colors as $name => $theme_color)
      {
        echo '<span style="float:left;width:22px;height:22px;background-color:' . $theme_color . '"></span> ' . $name . ': ' . $theme_color . '<br><br>';
      }

      $ionic_vars = '
$light:               ' . $light . ' !default;
$stable:              ' . $stable . ' !default;
$positive:            ' . $positive . ' !default;
$calm:              ' . $calm . ' !default;
$balanced:            ' . $balanced . ' !default;
$energized:             ' . $energized . ' !default;
$assertive:             ' . $assertive . ' !default;
$royal:               ' . $royal . ' !default;
$dark:              ' . $dark . ' !default;

// Base
// -------------------------------

$font-family-sans-serif:      ' . $font_family . ' !default;
$font-family-light-sans-serif:  ' . $font_family_light . ' !default;
$font-family-serif:         ' . $font_family_serif . ' !default;
$font-family-monospace:       monospace !default;

$base-background-color:       ' . $positive . ' !default;
$base-color:            ' . $text_color . ' !default;
.item-body p { color: ' . $text_color . ' !important;}

$button-stable-bg:        $stable !default;
$button-stable-text:        ' . $stable_contrast . ' !default;
$button-stable-border:      darken($stable, 10%) !default;
$button-stable-active-bg:     darken($stable, 10%) !default;
$button-stable-active-border:   darken($stable, 30%) !default;

$button-positive-bg:        $positive !default;
$button-positive-text:      ' . $positive_contrast . ' !default;
$button-positive-border:      darken($positive, 10%) !default;
$button-positive-active-bg:     darken($positive, 10%) !default;
$button-positive-active-border:   darken($positive, 30%) !default;

$button-calm-bg:          $calm !default;
$button-calm-text:        ' . $calm_contrast . ' !default;
$button-calm-border:        darken($calm, 10%) !default;
$button-calm-active-bg:       darken($calm, 10%) !default;
$button-calm-active-border:     darken($calm, 30%) !default;

$button-assertive-bg:       $assertive !default;
$button-assertive-text:       ' . $assertive_contrast . ' !default;
$button-assertive-border:     darken($assertive, 10%) !default;
$button-assertive-active-bg:    darken($assertive, 10%) !default;
$button-assertive-active-border:  darken($assertive, 30%) !default;

$button-balanced-bg:        $balanced !default;
$button-balanced-text:      ' . $balanced_contrast . ' !default;
$button-balanced-border:      darken($balanced, 10%) !default;
$button-balanced-active-bg:     darken($balanced, 10%) !default;
$button-balanced-active-border:   darken($balanced, 30%) !default;

$button-energized-bg:       $energized !default;
$button-energized-text:       ' . $energized_contrast . ' !default;
$button-energized-border:     darken($energized, 5%) !default;
$button-energized-active-bg:    darken($energized, 5%) !default;
$button-energized-active-border:  darken($energized, 5%) !default;

$button-royal-bg:         $royal !default;
$button-royal-text:         ' . $royal_contrast . ' !default;
$button-royal-border:       darken($royal, 8%) !default;
$button-royal-active-bg:      darken($royal, 8%) !default;
$button-royal-active-border:    darken($royal, 8%) !default;

$button-light-bg:         $light !default;
$button-light-text:         ' . $dark . ' !default;
$button-light-border:       darken($button-light-bg, 10%) !default;
$button-light-active-bg:      darken($button-light-bg, 10%) !default;
$button-light-active-border:    darken($button-light-bg, 30%) !default;

$button-dark-bg:          $dark !default;
$button-dark-text:        ' . $light . ' !default;
$button-dark-border:        darken($button-dark-bg, 10%) !default;
$button-dark-active-bg:       darken($button-dark-bg, 10%) !default;
$button-dark-active-border:     darken($button-dark-bg, 30%) !default;

$button-default-bg:         $button-stable-bg !default;
$button-default-text:       $button-stable-text !default;
$button-default-border:       $button-stable-border !default;
$button-default-active-bg:    $button-stable-active-bg !default;
$button-default-active-border:  $button-stable-active-border !default;

// Items
// -------------------------------

$item-default-text:         ' . $_val['dark_color'] . ' !default;
$item-default-active-bg:      darken($light, 20%) !default;

.ion-close,
.item.item-body { color: ' . $text_color . '; }

.item-radio .item-content { background-color: ' . $_val['light_color'] . ' !important; }

// Custom
// -------------------------------

.transparent { 
  background-color: rgba(' . $rgb[0] . ', ' . $rgb[1] . ', ' . $rgb[2] . ', ' . $opacity . '); 
  padding:20px; 
  width:100%;
  display:list-item;
  list-style:none;
  margin-bottom:10px;
}
.transparent a, .transparent a:hover { color: ' . $link_color . ' !important; }
.transparent a:hover { text-decoration: none; }
.transparent ol,
.transparent ul {
  list-style: inside;
}
.transparent ol li,
.transparent ul li {
  margin-left:5px;
}
.transparent ol li {
  list-style: decimal;
  margin-left:25px;
}

.menu .item a.item-content,
.list.card .icon {
  color: ' . $text_color . ' !important; 
}

// Forms
// -------------------------------

$input-bg:            ' . $light . ' !default;
$input-bg-disabled:         $stable !default;

$input-color:           ' . $light_contrast . ' !default;
$input-border:          $item-default-border !default;
$input-border-width:        $item-border-width !default;
$input-label-color:         ' . $dark . ' !default;
$input-color-placeholder:     lighten($dark, 40%) !default;

textarea,
input[type="text"],
input[type="password"],
input[type="datetime"],
input[type="datetime-local"],
input[type="date"],
input[type="month"],
input[type="time"],
input[type="week"],
input[type="number"],
input[type="email"],
input[type="url"],
input[type="search"],
input[type="tel"],
input[type="color"] {
  color: ' . $light_contrast . ' !important;
  background: ' . $light . ' !important;
}
.item-checkbox, .item-select select {
  color: ' . $light_contrast . ' !important;
}
$modal-bg-color:          ' . $positive . ' !default;
.modal { color: ' . $text_color . '; }
';

if(isset($_val['tab_active']))
{
  $ionic_vars .= 'ion-tabs.tabs-color-active-positive .tab-item.tab-item-active, ion-tabs.tabs-color-active-positive .tab-item.active, ion-tabs.tabs-color-active-positive .tab-item.activated { color:' . $_val['tab_active'] . ' !important; }';
  $ionic_vars .= '.item-content.activated { color:' . $tab_active_contrast . ' !important; }';
}

      $scss = new \scssc();
      $scss->setFormatter("scss_formatter_compressed");
      $scss->setImportPaths($ionic_scss_path);

      $css = $scss->compile($ionic_vars . $ionic_scss);

      if ($output_sass)
      {
        echo $ionic_vars . $ionic_scss;
        die();
      }

      $css_extra = '';

      if (! $preview)
      {
        \File::put($target_css, $css . $css_extra);

        // Generate screenshot
        if($screenshot)
        {
          $url = url('/api/v1/app-theme/preview/' . $theme);

          \Mobile\Controller\ThemeController::screenshot($url, $preview_image, 1);
        }
        echo $theme . ' done<br>';
      }
      else
      {
        return \View::make('user.app.preview', array(
          'theme' => $theme,
          'css' => $css . $css_extra
        ));
        echo 'prev';
      }
    }
    }
    return 'Ready';
  }

  public static function screenshot($url, $thumbnail, $empty_cache = 0, $timeout = 800)
  {
    \Config::set('screenshotserver.url', 'http://screenshotserver.dev');

    if($empty_cache == '0' && \File::exists($thumbnail))
    {
      return false;
    }

    $img = \Image::canvas(120, 200);

    $thumb_phone_url = file_get_contents(\Config::get('screenshotserver.url') . '/grab?url=' . $url . '&empty_cache=' . $empty_cache . '&browser_width=480&browser_height=800&thumb_width=120&thumb_height=null&timeout=' . $timeout);
    $thumb_phone = \Image::make('http:' . $thumb_phone_url);

    //$img->insert(public_path() . '/assets/images/mockups/shadow.png');
    $img->insert($thumb_phone, NULL, 0, 0);

    $img->save($thumbnail, 60);

    return $img->response();
  }


  public static function thumbnail($bg_image, $thumbnail, $empty_cache = 0)
  {
    if($empty_cache == '0' && \File::exists($thumbnail))
    {
      return false;
    }

    $img = \Image::canvas(120, 200);

    $thumb_phone_url = \Image::make($bg_image);
    $thumb_phone_url->resize(120, 200);

    $thumb_phone = \Image::make($thumb_phone_url);

    //$img->insert(public_path() . '/assets/images/mockups/shadow.png');
    $img->insert($thumb_phone, NULL, 0, 0);

    $img->save($thumbnail, 60);

    return $img->response();
  }

  /**
   * Preview template
   */
  public static function getPreview($theme)
  {
    return \View::make('user.app.preview', array(
      'theme' => $theme
    ));
  }

  public static function cf_sort_hex_colors($colors) {
    $map = array(
      '0' => 0,
      '1' => 1,
      '2' => 2,
      '3' => 3,
      '4' => 4,
      '5' => 5,
      '6' => 6,
      '7' => 7,
      '8' => 8,
      '9' => 9,
      'a' => 10,
      'b' => 11,
      'c' => 12,
      'd' => 13,
      'e' => 14,
      'f' => 15,
    );
    $c = 0;
    $sorted = array();
    foreach ($colors as $color) {
      $color = strtolower(str_replace('#', '', $color));
      if (strlen($color) == 6) {
        $condensed = '';
        $i = 0;
        foreach (preg_split('//', $color, -1, PREG_SPLIT_NO_EMPTY) as $char) {
          if ($i % 2 == 0) {
            $condensed .= $char;
          }
          $i++;
        }
        $color_str = $condensed;
      }
      $value = 0;
      foreach (preg_split('//', $color_str, -1, PREG_SPLIT_NO_EMPTY) as $char) {
        $value += intval($map[$char]);
      }
      $value = str_pad($value, 5, '0', STR_PAD_LEFT);
      $sorted['_'.$value.$c] = '#'.$color;
      $c++;
    }
    ksort($sorted);
    $colors = array();
    foreach($sorted as $color)
    {
      $colors[] = $color;
    }
    return $colors;
  }

  public static function getContrast($hexcolor, $dark = '#222', $light = '#fff')
  {
    if($hexcolor == '#ffffff' || $hexcolor == '#fff') return $dark;
    return (hexdec($hexcolor) > 0xffffff/2) ? $dark : $light;
  }

  public static function hex2rgb($hex)
  {
     $hex = str_replace("#", "", $hex);
  
     if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
     } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
     }
     $rgb = array($r, $g, $b);
     //return implode(",", $rgb); // returns the rgb values separated by commas
     return $rgb; // returns an array with the rgb values
  }

  public static function adjustBrightness($hex, $steps) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter
    $steps = max(-255, min(255, $steps));

    // Normalize into a six character long hex string
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
      $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }

    // Split into three parts: R, G and B
    $color_parts = str_split($hex, 2);
    $return = '#';

    foreach ($color_parts as $color) {
      $color   = hexdec($color); // Convert to decimal
      $color   = max(0,min(255,$color + $steps)); // Adjust color
      $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT); // Make two char hex code
    }

    return $return;
  }
}