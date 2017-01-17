<?php
namespace App\Controller;

use View, Auth, Input, Cache;

/*
|--------------------------------------------------------------------------
| oAuth controller
|--------------------------------------------------------------------------
|
| oAuth related logic
|
*/

class oAuthController extends \BaseController {

    /**
	 * Construct
     */
    public function __construct()
    {

    }

    /**
     * Show log partial
     */
    public function getTwitter()
    {
		session_start();
		$server = new \League\OAuth1\Client\Server\Twitter(array(
			'identifier' => '',
			'secret' => '',
			'callback_uri' => url('/api/v1/oauth/twitter'),
		));

		$oauth_token = \Input::get('oauth_token');
		$oauth_verifier = \Input::get('oauth_verifier');

		if (! empty($oauth_token) && ! empty($oauth_verifier)) {
			// Retrieve the temporary credentials we saved before
			$temporaryCredentials = unserialize($_SESSION['temporary_credentials']);
			//$temporaryCredentials = \Session::get('oauth_temp');
			//$temporaryCredentials = unserialize($temporaryCredentials);

			// We will now retrieve token credentials from the server
			$tokenCredentials = $server->getTokenCredentials($temporaryCredentials, $oauth_token, $oauth_verifier);

			// User is an instance of League\OAuth1\Client\Server\User
			$user = $server->getUserDetails($tokenCredentials);

			// UID is a string / integer unique representation of the user
			$uid = $server->getUserUid($tokenCredentials);

			// Email is either a string or null (as some providers do not supply this data)
			$email = $server->getUserEmail($tokenCredentials);

			// Screen name is also known as a username (Twitter handle etc)
			$screenName = $server->getUserScreenName($tokenCredentials);

			dd($user);

			var_dump($tokenCredentials->getIdentifier());
			var_dump($tokenCredentials->getSecret());
		}

		$temporaryCredentials = $server->getTemporaryCredentials();

		\Session::put('oauth_temp', serialize($temporaryCredentials));
		$_SESSION['temporary_credentials'] = serialize($temporaryCredentials);
		session_write_close();

		//\Session::put('oauth_identifier', $temporaryCredentials->getIdentifier());
		//\Session::put('oauth_secret', $temporaryCredentials->getSecret());

		$server->authorize($temporaryCredentials);
    }

}