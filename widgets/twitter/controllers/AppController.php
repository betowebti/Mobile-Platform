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
    $twitter = NULL;
    $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));
    $oAuth = \Mobile\Controller\WidgetController::getData($page, 'oAuth', NULL);

    echo \View::make('widget::app.index')->with([
      'app' => $app,
      'page' => $page,
      'oAuth' => $oAuth,
      'sl' => $sl
    ]);
  }

  /**
   * Tweets
   */
  public function getTweets($app, $page)
  {
    $cache_minutes = \Mobile\Controller\WidgetController::getData($page, 'cache', 1);

    $json = \Cache::remember('widget-twitter-' . $app->id . '-' . $page->id, $cache_minutes, function() use($page)
    {
      $oAuth = \Mobile\Controller\WidgetController::getData($page, 'oAuth', NULL);

      if($oAuth != NULL) 
      {
        $oAuth = json_decode($oAuth);

        $oauth_consumer_key = \Config::get('widget::oauth.api_key');
        $oauth_consumer_secret = \Config::get('widget::oauth.api_secret');
        $oauth_token = $oAuth->oauth_token;
        $oauth_signature = $oAuth->oauth_verifier;

        $oauth_hash = '';
        $oauth_hash .= 'oauth_consumer_key=' . $oauth_consumer_key . '&';
        $oauth_hash .= 'oauth_nonce=' . time() . '&';
        $oauth_hash .= 'oauth_signature_method=HMAC-SHA1&';
        $oauth_hash .= 'oauth_timestamp=' . time() . '&';
        $oauth_hash .= 'oauth_token=' . $oauth_token . '&';
        $oauth_hash .= 'oauth_version=1.0';
        $base = '';
        $base .= 'GET';
        $base .= '&';
        $base .= rawurlencode('https://api.twitter.com/1.1/statuses/user_timeline.json');
        $base .= '&';
        $base .= rawurlencode($oauth_hash);
        $key = '';
        $key .= rawurlencode($oauth_consumer_secret);
        $key .= '&';
        $key .= rawurlencode($oauth_signature);
        $signature = base64_encode(hash_hmac('sha1', $base, $key, true));
        $signature = rawurlencode($signature);

        $oauth_header = '';
        $oauth_header .= 'oauth_consumer_key="' . $oauth_consumer_key . '", ';
        $oauth_header .= 'oauth_nonce="' . time() . '", ';
        $oauth_header .= 'oauth_signature="' . $signature . '", ';
        $oauth_header .= 'oauth_signature_method="HMAC-SHA1", ';
        $oauth_header .= 'oauth_timestamp="' . time() . '", ';
        $oauth_header .= 'oauth_token="' . $oauth_token . '", ';
        $oauth_header .= 'oauth_version="1.0", ';
        $curl_header = array("Authorization: Oauth {$oauth_header}", 'Expect:');

        $curl_request = curl_init();
        curl_setopt($curl_request, CURLOPT_HTTPHEADER, $curl_header);
        curl_setopt($curl_request, CURLOPT_HEADER, false);
        curl_setopt($curl_request, CURLOPT_URL, 'https://api.twitter.com/1.1/statuses/user_timeline.json');
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($curl_request);
        curl_close($curl_request);

        return json_decode($json, false);
      }
    });

    return \Response::json($json);
  }

  /**
   * oAuth handshake
   */
  public function oAuth($app, $page)
  {
    session_start();

    $oauth_token = \Input::get('oauth_token');
    $oauth_verifier = \Input::get('oauth_verifier');

    $sl = \Input::get('sl', '');
    $qs = \App\Core\Secure::string2array($sl);

    $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->first();
    $page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

    $server = new \League\OAuth1\Client\Server\Twitter(array(
      'identifier' => \Config::get('widget::oauth.api_key'),
      'secret' => \Config::get('widget::oauth.api_secret'),
      'callback_uri' => url('/api/v1/widget/get/twitter/oAuth?sl=' . $sl),
    ));

    if (! empty($oauth_token) && ! empty($oauth_verifier)) {
      // Retrieve the temporary credentials we saved before
      $temporaryCredentials = unserialize($_SESSION['temporary_credentials']);

      // We will now retrieve token credentials from the server
      $tokenCredentials = $server->getTokenCredentials($temporaryCredentials, $oauth_token, $oauth_verifier);

      // User is an instance of League\OAuth1\Client\Server\User
      $user = $server->getUserDetails($tokenCredentials);

      // UID is a string / integer unique representation of the user
      //$uid = $server->getUserUid($tokenCredentials);

      // Email is either a string or null (as some providers do not supply this data)
      //$email = $server->getUserEmail($tokenCredentials);

      // Screen name is also known as a username (Twitter handle etc)
      //$screenName = $server->getUserScreenName($tokenCredentials);

      $oAuth = array(
        'nickname' => $user->nickname,
        'name' => $user->name,
        'imageUrl' => $user->imageUrl,
        'oauth_token' => $tokenCredentials->getIdentifier(),
        'oauth_verifier' => $tokenCredentials->getSecret()
      );

      \Mobile\Controller\WidgetController::setData($page, 'oAuth', $oAuth);

      echo \View::make('widget::admin.oauth-success')->with([
        'app' => $app,
        'page' => $page
      ]);
      die();
    }

    $temporaryCredentials = $server->getTemporaryCredentials();
    $_SESSION['temporary_credentials'] = serialize($temporaryCredentials);

    session_write_close();

    $server->authorize($temporaryCredentials);
    die();
  }

  /**
   * Disconnect account, remove oAuth data
   */
  public function oAuthDisconnect($app, $page)
  {
    \Mobile\Controller\WidgetController::setData($page, 'oAuth', NULL);
  }
}