<?php
namespace Mobile\Controller;

/*
|--------------------------------------------------------------------------
| PhoneGap controller
|--------------------------------------------------------------------------
|
| PhoneGap related logic
|
*/

class PhonegapController extends \BaseController {

  private $ch;
  private $token = "";
  private $cert_keys = array();
  private $cert_keypw = array();

  /**
   * Authenticate with the Phonegap API
   */
  public function __construct()
  {
    $token_or_username = \Config::get('phonegap.username');
    $password = \Config::get('phonegap.password');

    // Init CURL
    $this->ch = curl_init();

    // Set options
    curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);

    // Check if second parameter is empty, if so auth by token, otherwise auth by username/password
    if(empty($password))
    {
      $this->token = "auth_token=" . $token_or_username;
    }
    else
    {
      curl_setopt($this->ch, CURLOPT_USERPWD, "$token_or_username:$password");
    }
  }

  /**
   * Get the keys from a user
   */
  public static function getUserKeys($platform = NULL, $user_id = 0)
  {
    if ($user_id == 0) $user_id = (\Auth::user()->parent_id == NULL) ? \Auth::user()->id : \Auth::user()->parent_id;

    $platforms = ($platform == NULL) ? ['ios', 'android', 'winphone'] : [$platform];

    foreach ($platforms as $platform)
    {
      $keys[$platform] = \Cache::rememberForever($platform . 'Keys' . $user_id, function() use($user_id, $platform)
      {
        $pg = new \Mobile\Controller\PhonegapController;
        $keys = $pg->getKeys($platform);

        $filtered_keys = [];

        if (is_array($keys))
        {
          foreach ($keys as $key)
          {
            if (starts_with($key->title, 'u' . $user_id . '-'))
            {
              $filtered_keys[] = $key;
            }
          }
        }
        return $filtered_keys;
      });
    }

    if ($platform != NULL)
    {
      $keys = $keys[$platform];
    }

    return $keys;
  }

  /**
   * Forget cache keys from user
   */
  public static function forgetUserKeys($platform = NULL, $user_id = 0)
  {
    if ($user_id == 0) $user_id = (\Auth::user()->parent_id == NULL) ? \Auth::user()->id : \Auth::user()->parent_id;

    $platforms = ($platform == NULL) ? ['ios', 'android', 'winphone'] : [$platform];

    foreach ($platforms as $platform)
    {
      \Cache::forget($platform . 'Keys' . $user_id);
    }
  }

  /* Get Account info */
  public function getAccountInfo()
  {
    // URL
    $url = "https://build.phonegap.com/api/v1/me?" . $this->token;
    
    // Options
    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch, CURLOPT_HTTPGET, true);
    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($this->ch, CURLOPT_POST, false);
    
    // Output
    $output = curl_exec($this->ch);
    $obj = json_decode($output);
    return $obj;
  }
  
  /* Get app info */
  public function getApps()
  {
    // URL
    $url = "https://build.phonegap.com/api/v1/apps?" . $this->token;
    
    // Options
    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch, CURLOPT_HTTPGET, true);
    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($this->ch, CURLOPT_POST, false);
    
    // Output
    $output = curl_exec($this->ch);
    $obj = json_decode($output);
    if(is_object($obj) && isset($obj->apps) && !empty($obj->apps))
    {
      return $obj->apps;
    }
    
    return array();
  }
    
  /* Get key info (per platform) */
  public function getKeys($platform)
  {
    // URL
    $url = "https://build.phonegap.com/api/v1/keys/" . $platform . "?" . $this->token;
    
    // Options
    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch, CURLOPT_HTTPGET, true);
    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($this->ch, CURLOPT_POST, false);
    
    // Output
    $output = curl_exec($this->ch);
    $obj = json_decode($output);
    return $obj->keys;
  }
  
  /* Get app info */
  public function getApp($id)
  {
    // URL
    $url = "https://build.phonegap.com/api/v1/apps/" . $id  . "/?" . $this->token;
    
    // Options
    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch, CURLOPT_HTTPGET, true);
    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
    curl_setopt($this->ch, CURLOPT_POST, false);
    
    // Output
    $output = curl_exec($this->ch);
    $out = json_decode($output);
    if(is_object($out))
    {
      return $out;
    }
    else
    {
      return false;
    }
  }

  /* Get downloadlink */
  public function getDownloadLink($id, $platform)
  {
    $app = $this->getApp($id);
    
    $waittime = 0;
    $sleeptime = 3;
    $maxwaittime = 60;
    
    if($app)
    {
      if($app->status->{$platform} == 'complete')
      {
        // Yay, we've got a downloadlink
        $url = "https://build.phonegap.com" . $app->download->{$platform} . "?" . $this->token;
        
        // We must have the amazonaws link because, in the meanwhile the app could be removed by another user in BuildTool.
        // This means the download link will be unavailable in PGB because of the app might not exist anymore.
        // However, the amazonaws link remains available for an x period of time.
        
        // Options
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_HTTPGET, true);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->ch, CURLOPT_POST, false);
        
        // Output
        $output = curl_exec($this->ch);
        $out = json_decode($output);
        if(is_object($out))
        {
          return $out->location;
        }

        return $url;
      }
      else if($app->status->{$platform} == 'pending')
      {
        // Wait x seconds to check if status moved from pending to 'failed'/'error' or 'complete'
        // Normally this takes about 15 seconds and should not loop forever.
        // Therefore make sure your excution time limit is high.
        sleep($sleeptime);
        
        $waittime = $waittime + $sleeptime;
        
        // If waited for over maximum wait time, get out.
        if($waittime > $maxwaittime)
        {
          return false;
        }
        else
        {
          // Try to get downloadlink again
          return $this->getDownloadLink($id, $platform);
        }
      }
    }

    // We failed :-(
    return "";
  }

  /* Upload a new app */
  public function uploadApp($file, $identifier, $title, $debug = false, $force = false, $createMethod = 'file')
  {
    // Check if app with this bundle identifier already exists
    $apps = $this->getApps();

    foreach ($apps as $app)
    {
      if($app->id == $identifier)
      {
        // Remove app
        $this->deleteApp($app->id);
      }
    }

    // Unlock keys for 1 hour
    if(count($this->cert_keys) > 0)
    {
      if(!empty($this->cert_keys['ios']))
      {
        $url = "https://build.phonegap.com/api/v1/keys/ios/" . $this->cert_keys['ios'] . "?" . $this->token;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPGET, false);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        
        $post = array(
          "data" => json_encode(array(
                    'password' => $this->cert_keypw['ios']
                    ))
        );
        
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($this->ch);
      }
      
      if(!empty($this->cert_keys['android']))
      {
        $url = "https://build.phonegap.com/api/v1/keys/android/" . $this->cert_keys['android'] . "?" . $this->token;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPGET, false);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        
        $post = array(
          "data" => json_encode(array(
                    'key_pw' => $this->cert_keypw['android_key'],
                    'keystore_pw' => $this->cert_keypw['android_keystore']
                    ))
        );

        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($this->ch);
      }

      if(!empty($this->cert_keys['blackberry']))
      {
        $url = "https://build.phonegap.com/api/v1/keys/blackberry/" . $this->cert_keys['blackberry'] . "?" . $this->token;
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_HTTPGET, false);
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');

        $post = array(
          "data" => json_encode(array(
                    'password' => $this->cert_keypw['blackberry']
                    ))
        );

        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
        curl_exec($this->ch);
      }
    }

    // URL
    $url = "https://build.phonegap.com/api/v1/apps?" . $this->token;

    // Options
    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch, CURLOPT_POST, true);
    curl_setopt($this->ch, CURLOPT_HTTPGET, false);
    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');

    if(count($this->cert_keys) > 0)
    {
      $post = array(
        "data" => json_encode(array(
                  'create_method' => $createMethod,
                  'title' => $title,
                  'keys' => $this->cert_keys,
                  'private' => true,
                  'debug' => $debug
                  )),
        "file" => new \CurlFile($file)
      );
    }
    else
    {
      $post = array(
        "data" => json_encode(array(
                  'create_method' => $createMethod,
                  'title' => $title,
                  'private' => true,
                  'debug' => $debug
                  )),
        "file" => new \CurlFile($file)
      );
    }
    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);

    // Output
    $output = curl_exec($this->ch);
    $obj = json_decode($output);

    if(is_object($obj) && isset($obj->error) && !empty($obj->error))
    {
      // App limit error
      if($obj->error == 'Private app limit reached')
      {
        // Delete last app, try upload again
        if($force)
        {
          $this->deleteLastApp();
          return $this->uploadApp($file, $identifier, $title, $debug);
        }
        else
        {
          return "limit";
        }
      }

      // Unknown error
      else
      {
        // Try build
        if (isset($obj->error))
        {
          return $obj;
          //return $obj->error;
        }
        else
        {
          return $obj;
          //return $obj->id;
        }
      }
    }
    elseif (is_object($obj) && isset($obj->id))
    {
      return $obj->id;
    }
    else
    {
      return "";
    }
  }

  /* Set iOS Certificate */
  public function setIOSCertificate($id)
  {
    $this->cert_keys['ios'] = $id;
  }

  /* Upload iOS Certificate */
  public function uploadIOSCertificate($p12_file, $prov_file, $title, $password)
  {
    // Check if key already exists
    $keys = $this->getKeys("ios");
    foreach($keys as $key)
    {
      if($key->title == $title)
      {
        // Set key in array
        $this->cert_keys['ios'] = $key->id;
        $this->cert_keypw['ios'] = $password;

        // Return id
        return $key->id;
      }
    }
    
    // URL
    $url = "https://build.phonegap.com/api/v1/keys/ios?" . $this->token;
    
    // Options
    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch, CURLOPT_POST, true);
    curl_setopt($this->ch, CURLOPT_HTTPGET, false);
    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');

    $post = array(
      "data" => json_encode(array(
                'title' => $title,
                'password' => $password
                )),
      "cert" => "@" . $p12_file,
      "profile" => "@" . $prov_file
    );
    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
    
    // Output
    $output = curl_exec($this->ch);
    $obj = json_decode($output);

    if (is_object($obj) && isset($obj->error) && !empty($obj->error))
    {
      return $obj->error;
    }
    elseif (is_object($obj) && isset($obj->id))
    {
      $this->cert_keys['ios'] = $obj->id;
      return $obj->id;
    }
    
    return "";
  }

  /* Set Android Certificate */
  public function setAndroidCertificate($id)
  {
    $this->cert_keys['android'] = $id;
  }

  /* Upload Android Certificate */
  public function uploadAndroidCertificate($keystore_file, $title, $alias, $key_pw, $keystore_pw)
  {
    // Check if key already exists
    $keys = $this->getKeys("android");
    foreach($keys as $key)
    {
      if($key->title == $title)
      {        
        // Set key in array
        $this->cert_keys['android'] = $key->id;
        $this->cert_keypw['android_key'] = $key_pw;
        $this->cert_keypw['android_keystore'] = $keystore_pw;
        
        // Return id
        return $key->id;
      }
    }
  
    // URL
    $url = "https://build.phonegap.com/api/v1/keys/android?" . $this->token;
    
    // Options
    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch, CURLOPT_POST, true);
    curl_setopt($this->ch, CURLOPT_HTTPGET, false);
    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');

    $post = array(
      "data" => json_encode(array(
                'title' => $title,
                'alias' => $alias,
                'key_pw' => $key_pw,
                'keystore_pw' => $keystore_pw
                )),
      "keystore" => "@" . $keystore_file
    );
    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
    
    // Output
    $output = curl_exec($this->ch);
    $obj = json_decode($output);

    if (is_object($obj) && isset($obj->error) && !empty($obj->error))
    {
      return $obj->error;
    }
    elseif (is_object($obj) && isset($obj->id))
    {
      $this->cert_keys['android'] = $obj->id;
      return $obj->id;
    }
    
    return "";
  }

  /* Set WinPhone Publisher ID */
  public function setWinPhonePuplisherID($id)
  {
    $this->cert_keys['winphone'] = $id;
  }

  /* Add WinPhone Publisher ID */
  public function uploadWinPhonePuplisherID($title, $publisher_id)
  {
    // Check if key already exists
    $keys = $this->getKeys("winphone");
    foreach($keys as $key)
    {
      if($key->title == $title)
      {        
        // Set key in array
        $this->cert_keys['winphone'] = $key->id;
        
        // Return id
        return $key->id;
      }
    }
  
    // URL
    $url = "https://build.phonegap.com/api/v1/keys/winphone?" . $this->token;
    
    // Options
    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch, CURLOPT_POST, true);
    curl_setopt($this->ch, CURLOPT_HTTPGET, false);
    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');

    $post = array(
      "data" => json_encode(array(
                'title' => $title,
                'publisher_id' => $publisher_id
                ))
    );
    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
    
    // Output
    $output = curl_exec($this->ch);
    $obj = json_decode($output);

    if (is_object($obj) && isset($obj->error) && !empty($obj->error))
    {
      return $obj->error;
    }
    elseif (is_object($obj) && isset($obj->id))
    {
      $this->cert_keys['winphone'] = $obj->id;
      return $obj->id;
    }
    
    return "";
  }
  
  /* Upload BlackBerry Certificate */
  public function uploadBlackBerryCertificate($db_file, $csk_file, $title, $password)
  {
    // Check if key already exists
    $keys = $this->getKeys("blackberry");
    foreach($keys as $key)
    {
      if($key->title == $title)
      {
        // Set key in array
        $this->cert_keys['blackberry'] = $key->id;
        $this->cert_keypw['blackberry'] = $password;
        
        // Return id
        return $key->id;
      }
    }
    
    // URL
    $url = "https://build.phonegap.com/api/v1/keys/blackberry?" . $this->token;
    
    // Options
    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch, CURLOPT_POST, true);
    curl_setopt($this->ch, CURLOPT_HTTPGET, false);
    curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'POST');

    $post = array(
      "data" => json_encode(array(
                'title' => $title,
                'password' => $password
                )),
      "db" => "@" . $db_file,
      "csk" => "@" . $csk_file
    );
    curl_setopt($this->ch, CURLOPT_POSTFIELDS, $post);
    
    // Output
    $output = curl_exec($this->ch);
    $obj = json_decode($output);

    if (is_object($obj) && isset($obj->error) && !empty($obj->error))
    {
      return $obj->error;
    }
    elseif (is_object($obj) && isset($obj->id))
    {
      $this->cert_keys['blackberry'] = $obj->id;
      return $obj->id;
    }

    return "";
  }

  /* Delete key */
  public function deleteKey($platform, $id)
  {
    if(!empty($id))
    {
      $url = "https://build.phonegap.com/api/v1/keys/" . $platform . "/" . $id . "?" . $this->token;
      curl_setopt($this->ch, CURLOPT_URL, $url);
      curl_setopt($this->ch, CURLOPT_POST, 0);
      curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
      $r = curl_exec($this->ch);
      return $r;
    }
  }

  /* Check app status */
  public function checkApp($id)
  {
    $app = $this->getApp($id);
    if ($app)
    {
      return $app->status;
    }
    
    return false;
  }
  
  /* Check is user is logged in to the Phonegap API */
  public function checkLogin() {
  
    $obj = $this->getAccountInfo();
    if(is_object($obj) && !isset($obj->error))
    {
      return true;
    }
    return false;
  }
  
  /* Delete app */
  public function deleteApp($id)
  {
    if(!empty($id))
    {
      $url = "https://build.phonegap.com/api/v1/apps/" . $id . "?" . $this->token;
      curl_setopt($this->ch, CURLOPT_URL, $url);
      curl_setopt($this->ch, CURLOPT_POST, 0);
      curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
      $r = curl_exec($this->ch);
      return $r;
    }
  }

  /* Delete last added app */
  public function deleteLastApp()
  {
    $apps = $this->getApps();
    
    $appid = 0;
    
    // Highest id = last added app
    foreach ($apps as $app)
    {
      if($app->id > $appid)
      {
        $appid = $app->id;
      }
      
    }
    
    $this->deleteApp($appid);
  }
  
  /* Close connection */
  public function __destruct()
  {
    curl_close($this->ch);
  }
}