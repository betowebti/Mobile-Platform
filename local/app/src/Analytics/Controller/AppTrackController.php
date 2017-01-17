<?php
namespace Analytics\Controller;

use View, Auth, Input, Cache;
//use RedBeanPHP\R;

/*
|--------------------------------------------------------------------------
| App Track controller
|--------------------------------------------------------------------------
|
| App Track statistics
|
*/

class AppTrackController extends \BaseController {

  /**
   * Construct
   */
  public function __construct()
  {
    //$this->db_path = storage_path() . '/userdata/';
    $this->logged_in = Auth::check();
    $this->track_when_logged_in = false;
    $this->track_localhost = true; // only works for 127.0.0.1
    $track = ($this->track_when_logged_in && Auth::check()) ? true : false;
    $track = (! $this->track_when_logged_in && Auth::check()) ? false : $track;
    $this->track = (! Auth::check()) ? true : $track;
  }

  /**
   * Track an app visit with JS file
   */
  public function getApp($app_hash)
  {
    if($app_hash != '' && ((! \Auth::check() && ! $this->track_when_logged_in) || (\Auth::check() && $this->track_when_logged_in)))
    {
      $app_id = \App\Core\Secure::staticHashDecode($app_hash);
      $app = \Mobile\Model\App::where('id', $app_id)->first();

      $track = new \Analytics\Controller\AppTrackController;
      $stat_id = $track->visit($app);
/*
      $js = 'if (navigator.geolocation)
{
  navigator.geolocation.getCurrentPosition(refineTrack, function(error) {});
}

function refineTrack(position)
{
  var http = new XMLHttpRequest();
  var p = "lat=" + position.coords.latitude + "&lng=" + position.coords.longitude + "&h=' . $app_hash . '&i=' . $stat_id . '";
  http.open("post", "' . url('/api/v1/app-track/visit') . '", true); 
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.send(p); 
}';*/

      $js = '';

      $response = \Response::make($js);
      $response->header('Content-Type', 'application/javascript');
      return $response;
    }
    else
    {
      $response = \Response::make('');
      $response->header('Content-Type', 'application/javascript');
      return $response;
    }
  }

  /**
   * Track an app visit with JS file
   */
  public function postVisit()
  {
    $app_hash = \Request::get('h');
    $id = \Request::get('i', 0);
    $lat = \Request::get('lat', 0);
    $lng = \Request::get('lng', 0);

    if($app_hash != '' && is_numeric($id) && $id > 0 && $lat > 0 && $lng > 0)
    {
      $location = \App\Core\Geo::reverse($lat, $lng);

      $app_id = \App\Core\Secure::staticHashDecode($app_hash);
      $app = \Mobile\Model\App::where('id', $app_id)->first();
      $stats = \Analytics\Model\AppStat::find($id);

      $stats->latitude = $lat;
      $stats->longitude = $lng;
      $stats->city = (isset($location['city'])) ? $location['city'] : '';
      $stats->region = (isset($location['region'])) ? $location['region'] : '';
      $stats->country = (isset($location['country'])) ? $location['country'] : '';
      $stats->countryCode = (isset($location['countryCode'])) ? $location['countryCode'] : '';

      $stats->save();
    }
  }

  /**
   * Track a visit
   * $track = new \Analytics\Controller\AppTrackController;
    * $track->visit();
   */
  public function visit($app, $page = NULL)
  {
    if(! $this->track) return;

    $ip = \Analytics\Controller\AppTrackController::ip();

    if(! $this->track_localhost && $ip == '127.0.0.1') return;

    // Parse user agent
    $ua = \App\Core\Piwik::getDevice();

    // Retrieve GEO info
    $geo = \App\Core\Geo::ip2address($ip);

    $stats = new \Analytics\Model\AppStat;

    $stats->ip = $ip;
    $stats->app_id = $app->id;
    $stats->os = $ua['os'];
    $stats->client = $ua['client'];
    $stats->device = $ua['device'];
    $stats->brand = $ua['brand'];
    $stats->model = $ua['model'];

    $stats->latitude = (isset($geo['latitude'])) ? $geo['latitude'] : 0;
    $stats->longitude = (isset($geo['longitude'])) ? $geo['longitude'] : 0;
    $stats->city = (isset($geo['city'])) ? $geo['city'] : '';
    $stats->region = (isset($geo['region'])) ? $geo['region'] : '';
    $stats->country = (isset($geo['country'])) ? $geo['country'] : '';
    $stats->countryCode = (isset($geo['countryCode'])) ? $geo['countryCode'] : '';

    if($page != NULL)
    {
      $stats->page_id = $page->id;
      $stats->page_widget = $page->widget;
      $stats->page_name = $page->name;
    }

    $stats->save();
/*
    // Connect to SQLite database
    R::setup('sqlite:' . $this->db_path . 'app_' . $app->id . '_stats.sqlite');

    // Parse user agent
    $ua = \App\Core\Piwik::getDevice();

    // Retrieve GEO info
    $geo = \App\Core\Geo::ip2address($ip);

    $stats = R::dispense('stats');

    $stats->created_at = date('Y-m-d H:i:s');
    $stats->ip = $ip;

    $stats->os = $ua['os'];
    $stats->client = $ua['client'];
    $stats->device = $ua['device'];
    $stats->brand = $ua['brand'];
    $stats->model = $ua['model'];

    $stats->latitude = (isset($geo['latitude'])) ? $geo['latitude'] : 0;
    $stats->longitude = (isset($geo['longitude'])) ? $geo['longitude'] : 0;
    $stats->city = (isset($geo['city'])) ? $geo['city'] : '';
    $stats->region = (isset($geo['region'])) ? $geo['region'] : '';
    $stats->country = (isset($geo['country'])) ? $geo['country'] : '';
    $stats->countryCode = (isset($geo['countryCode'])) ? $geo['countryCode'] : '';

    if($page != NULL)
    {
      $stats->page_id = $page->id;
      $stats->page_widget = $page->widget;
      $stats->page_name = $page->name;
    }

    $id = R::store($stats);

    R::close();
*/
    return $stats->id;
  }

  /**
   * Get visits + segment info for given date range.
   * Accepts array of app model(s).
   * $stats = \Analytics\Controller\AppTrackController::getVisits([$app], $date_start, $date_end);
   */
  public static function getVisits($apps, $date_start, $date_end)
  {
    $range = \Analytics\Controller\AppAnalyticsController::getRange($date_start, $date_end);
    $segments = array('os', 'client', 'city', 'country');
    $total_segments = array();
    $return_apps = array();
    $total_visits = 0;
    $i = 0;

    // Get visits per date
    foreach($apps as $app)
    {
      //$sqlite = storage_path() . '/userdata/app_' . $app->id . '_stats.sqlite';
      //if(\File::exists($sqlite))
      //{
        $subtotal = 0;
        $return_apps[$i]['id'] = $app->id;
        $return_apps[$i]['name'] = $app->name;
        $app_date_start = ($app->created_at > $date_start) ? $app->created_at : $date_start;

        //R::addDatabase('DB' . $app->id, 'sqlite:' . $sqlite);
        //R::selectDatabase('DB' . $app->id);

        // Get visits
        //$sql = "SELECT date(created_at) as created_at, count(id) as total FROM `app_stats` WHERE created_at >= '" . $date_start . " 00:00:00' AND created_at <= '" . $date_end . " 23:59:59' GROUP BY date(created_at) ORDER BY created_at ASC";
        $sql = "SELECT date(created_at) as created_at, count(id) as total FROM `app_stats` WHERE app_id = :app_id AND created_at >= :date_start AND created_at <= :date_end GROUP BY date(created_at) ORDER BY created_at ASC";
        $rows = \DB::select(
          \DB::raw($sql), 
          array(
            'app_id' => $app->id,
            'date_start' => $date_start . ' 00:00:00',
            'date_end' => $date_end . ' 23:59:59'
          )
        );

        $app_visits = array();
        foreach($rows as $row)
        {
          $app_visits[$row->created_at] = $row->total;
        }

        foreach($range as $date)
        {
          if($date . '23:59:59' < $app_date_start)
          {
            $visits = NULL;
          }
          else
          {
            $visits = (isset($app_visits[$date])) ? $app_visits[$date] : 0;
            $total_visits += $visits;
            $subtotal += $visits;
          }

          $app_range[$date] = $visits;
        }

        $return_apps[$i]['total'] = $subtotal;
        $return_apps[$i]['range'] = $app_range;

        // Get segments
        foreach($segments as $segment)
        {
          //$sql = "SELECT " . $segment . " as segment, count(id) as total FROM `app_stats` WHERE created_at >= '" . $date_start . " 00:00:00' AND created_at <= '" . $date_end . " 23:59:59' GROUP BY " . $segment . " ORDER BY total ASC";
          $sql = "SELECT " . $segment . " as segment, count(id) as total FROM `app_stats` WHERE app_id = :app_id AND created_at >= :date_start AND created_at <= :date_end GROUP BY " . $segment . " ORDER BY total ASC";
          $rows = \DB::select(
            \DB::raw($sql), 
            array(
              'app_id' => $app->id,
              'date_start' => $date_start . ' 00:00:00',
              'date_end' => $date_end . ' 23:59:59'
            )
          );
          //$rows = R::getAll($sql);

          foreach($rows as $row)
          {
            // Segment app
            $return_apps[$i][$segment][$row->segment] = $row->total;

            // Segment totals
            $segment_total = (isset($total_segments[$segment][$row->segment])) ? $total_segments[$segment][$row->segment] : 0;
            $total_segments[$segment][$row->segment] = $row->total + $segment_total;
          }
        }

        // Get heatmap data
        //$sql = "SELECT latitude, longitude, count(id) as total FROM `app_stats` WHERE created_at >= '" . $date_start . " 00:00:00' AND created_at <= '" . $date_end . " 23:59:59' GROUP BY latitude, longitude ORDER BY created_at ASC";
        $sql = "SELECT latitude, longitude, count(id) as total FROM `app_stats` WHERE app_id = :app_id AND created_at >= :date_start AND created_at <= :date_end GROUP BY latitude, longitude ORDER BY created_at ASC";
        $rows = \DB::select(
          \DB::raw($sql), 
          array(
            'app_id' => $app->id,
            'date_start' => $date_start . ' 00:00:00',
            'date_end' => $date_end . ' 23:59:59'
          )
        );
        //$rows = R::getAll($sql);

        $app_visits = array();
        foreach($rows as $row)
        {
          if($row->longitude != 0 && $row->latitude != 0 && $row->longitude != '' && $row->latitude != '')
          {
            $latlng = $row->latitude . ',' . $row->longitude;

            $return_apps[$i]['heatmap'][$latlng] = $row->total;

            $count = $row->total;
            if(isset($total_segments['heatmap'][$latlng])) $count += $total_segments['heatmap'][$latlng];
            $total_segments['heatmap'][$latlng] = $count;
          }
        }

        if(! isset($return_apps[$i]['heatmap'])) $return_apps[$i]['heatmap'] = array();

      //  R::close();
        $i++;
      //}
    }

    return array(
      'apps' => $return_apps,
      'total' => $total_visits,
      'segments' => $total_segments
    );
  }

  /**
   * Get visits info for given date range.
   * Accepts array of app model(s).
   * $stats = \Analytics\Controller\AppTrackController::getVisitsOnly([$app], $date_start, $date_end);
   */
  public static function getVisitsOnly($apps, $date_start, $date_end)
  {
    $range = \Analytics\Controller\AppAnalyticsController::getRange($date_start, $date_end);
    $return_apps = array();
    $total_visits = 0;

    // Get visits per date
    $i = 0;
    foreach($apps as $app)
    {
      //$sqlite = storage_path() . '/userdata/app_' . $app->id . '_stats.sqlite';
      //if(\File::exists($sqlite))
      //{
        $subtotal = 0;
        $return_apps[$i]['id'] = $app->id;
        $return_apps[$i]['name'] = $app->name;
        $app_date_start = ($app->created_at > $date_start) ? $app->created_at : $date_start;

        //R::addDatabase('DB' . $app->id, 'sqlite:' . $sqlite);
        //R::selectDatabase('DB' . $app->id);

        // Get visits
        //$sql = "SELECT count(id) as total FROM `app_stats` WHERE created_at >= '" . $date_start . " 00:00:00' AND created_at <= '" . $date_end . " 23:59:59'";
        //$row = R::getRow($sql);
        //$sql = "SELECT date(created_at) as created_at, count(id) as total FROM `app_stats` WHERE app_id = :app_id AND created_at >= :date_start AND created_at <= :date_end GROUP BY date(created_at) ORDER BY created_at ASC";
        $sql = "SELECT count(id) as total FROM `app_stats` WHERE app_id = :app_id AND created_at >= :date_start AND created_at <= :date_end";
        $row = \DB::select(
          \DB::raw($sql), 
          array(
            'app_id' => $app->id,
            'date_start' => $date_start . ' 00:00:00',
            'date_end' => $date_end . ' 23:59:59'
          )
        )[0];
        $return_apps[$i]['total'] = $row->total;
        $total_visits += $row->total;

      //  R::close();
        $i++;
      //}
    }

    return array(
      'apps' => $return_apps,
      'total' => $total_visits
    );
  }

  /**
   * Get real IP address
   * \Analytics\Controller\AppTrackController::ip()
   */
  public static function ip() {
    $headers = array ('HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'HTTP_VIA', 'HTTP_X_COMING_FROM', 'HTTP_COMING_FROM', 'HTTP_CLIENT_IP' );
 
    foreach ( $headers as $header ) {
      if (isset ( $_SERVER [$header]  )) {
      
        if (($pos = strpos ( $_SERVER [$header], ',' )) != false) {
          $ip = substr ( $_SERVER [$header], 0, $pos );
        } else {
          $ip = $_SERVER [$header];
        }
        $ipnum = ip2long ( $ip );
        if ($ipnum !== - 1 && $ipnum !== false && (long2ip ( $ipnum ) === $ip)) {
          if (($ipnum - 184549375) && // Not in 10.0.0.0/8
          ($ipnum  - 1407188993) && // Not in 172.16.0.0/12
          ($ipnum  - 1062666241)) // Not in 192.168.0.0/16
          if (($pos = strpos ( $_SERVER [$header], ',' )) != false) {
            $ip = substr ( $_SERVER [$header], 0, $pos );
          } else {
            $ip = $_SERVER [$header];
          }
          return $ip;
        }
      }
      
    }
    return $_SERVER ['REMOTE_ADDR'];
  }
}