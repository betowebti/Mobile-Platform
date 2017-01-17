<?php
namespace Analytics\Model;

use Eloquent, DB;

Class AppStat extends Eloquent
{

	protected $table = 'app_stats';

	public $timestamps = false;

	public static function boot()
	{
	  static::creating(function($model)
	  {
      $demo = false;
      
      if ($demo) {
        $min_epoch = strtotime('2016-04-01 12:00:00');
        $max_epoch = strtotime(date('Y-m-d H:i:s'));
        $rand_epoch = rand($min_epoch, $max_epoch);
  
        $os = ['Windows', 'Mac', 'Android', 'iOS'];
        $browser = ['Chrome', 'FireFox', 'Safari', 'Internet Explorer'];
  
        $cities = [
          0 => [
            'city' => 'London',
            'lat' => 51.507351,
            'lon' => -0.127758,
          ],
          1 => [
            'city' => 'Berlin',
            'lat' => 52.520007,
            'lon' => 13.404954,
          ],
          2 => [
            'city' => 'Paris',
            'lat' => 48.856614,
            'lon' => 2.352222,
          ],
          3 => [
            'city' => 'San Francisco',
            'lat' => 37.774929,
            'lon' => -122.419416,
          ],
          4 => [
            'city' => 'New York',
            'lat' => 40.712784,
            'lon' => -74.005941,
          ],
          5 => [
            'city' => 'Tokyo',
            'lat' => 35.689487,
            'lon' => 139.691706,
          ],
          6 => [
            'city' => 'Sidney',
            'lat' => -33.867487,
            'lon' => 151.206990,
          ],
          7 => [
            'city' => 'Melbourne',
            'lat' => -37.814107,
            'lon' => 144.963280,
          ],
          8 => [
            'city' => 'Moscow',
            'lat' => 55.755826,
            'lon' => 37.617300,
          ]
        ];

        $city = mt_rand(0, count($cities)-1);

        $point = AppStat::generate_random_point([$cities[$city]['lat'], $cities[$city]['lon']], 10);

        $latitude = $point[0];
        $longitude = $point[1];

	      $model->app_id = mt_rand(1, 9);
	      $model->os = $os[mt_rand(0, count($os)-1)];
	      $model->client = $browser[mt_rand(0, count($browser)-1)];
	      $model->city = $cities[$city]['city'];
	      $model->latitude = $latitude;
	      $model->longitude = $longitude;
	      $model->created_at = date('Y-m-d H:i:s', $rand_epoch);
      } else {
        $model->created_at = $model->freshTimestamp();
      }
	  });
	}

	public function app()
	{
		return $this->belongsTo('Mobile\Model\App');
	}

	public function appPage()
	{
		return $this->belongsTo('Mobile\Model\AppPage');
	}

  /**
   * Given a $centre (latitude, longitude) co-ordinates and a
   * distance $radius (miles), returns a random point (latitude,longtitude)
   * which is within $radius miles of $centre.
   *
   * @param  array $centre Numeric array of floats. First element is 
   *                       latitude, second is longitude.
   * @param  float $radius The radius (in miles).
   * @return array         Numeric array of floats (lat/lng). First 
   *                       element is latitude, second is longitude.
   */

  public static function generate_random_point( $centre, $radius ){

      $radius_earth = 3959; //miles

      //Pick random distance within $distance;
      $distance = lcg_value()*$radius;

      //Convert degrees to radians.
      $centre_rads = array_map( 'deg2rad', $centre );

      //First suppose our point is the north pole.
      //Find a random point $distance miles away
      $lat_rads = (pi()/2) -  $distance/$radius_earth;
      $lng_rads = lcg_value()*2*pi();


      //($lat_rads,$lng_rads) is a point on the circle which is
      //$distance miles from the north pole. Convert to Cartesian
      $x1 = cos( $lat_rads ) * sin( $lng_rads );
      $y1 = cos( $lat_rads ) * cos( $lng_rads );
      $z1 = sin( $lat_rads );


      //Rotate that sphere so that the north pole is now at $centre.

      //Rotate in x axis by $rot = (pi()/2) - $centre_rads[0];
      $rot = (pi()/2) - $centre_rads[0];
      $x2 = $x1;
      $y2 = $y1 * cos( $rot ) + $z1 * sin( $rot );
      $z2 = -$y1 * sin( $rot ) + $z1 * cos( $rot );

      //Rotate in z axis by $rot = $centre_rads[1]
      $rot = $centre_rads[1];
      $x3 = $x2 * cos( $rot ) + $y2 * sin( $rot );
      $y3 = -$x2 * sin( $rot ) + $y2 * cos( $rot );
      $z3 = $z2;


      //Finally convert this point to polar co-ords
      $lng_rads = atan2( $x3, $y3 );
      $lat_rads = asin( $z3 );

      return array_map( 'rad2deg', array( $lat_rads, $lng_rads ) );
 }
}
