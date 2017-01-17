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
        $client_id = \Config::get('widget::app.client_id');
        $type = \Mobile\Controller\WidgetController::getData($page, 'type', 'user');
        $tag = \Mobile\Controller\WidgetController::getData($page, 'tag', '');
        $limit = \Mobile\Controller\WidgetController::getData($page, 'limit', 10);
        $oAuth = \Mobile\Controller\WidgetController::getData($page, 'oAuth', NULL);

        echo \View::make('widget::app.index')->with([
            'app' => $app,
            'page' => $page,
            'type' => $type,
            'tag' => $tag,
            'limit' => $limit,
            'client_id' => $client_id,
            'oAuth' => $oAuth
        ]);
    }

    /**
     * oAuth handshake
     */
    public function oAuth($app, $page)
    {
        session_start();

        $code = \Input::get('code', '');
        $state = \Input::get('state', '');

        $sl = \Input::get('sl', '');
        $qs = \App\Core\Secure::string2array($sl);

        $app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->first();
        $page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

        $provider = new \League\OAuth2\Client\Provider\Instagram([
            'clientId'          => \Config::get('widget::app.client_id'),
            'clientSecret'      => \Config::get('widget::app.client_secret'),
            'redirectUri'       => url('/api/v1/widget/get/instagram/oAuth?sl=' . $sl),
        ]);

        if ($code == '') {

            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: '.$authUrl);
            exit;
        
        // Check given state against previously stored one to mitigate CSRF attack
        } elseif ($state == '' || ($state !== $_SESSION['oauth2state'])) {
        
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        
        } else {
        
            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);

            // Optional: Now you have a token you can look up a users profile data
            try {

                // We got an access token, let's now get the user's details    
                $user = $provider->getResourceOwner($token);

                $oAuth = array(
                    'uid' => $user->getId(),
                    'nickname' => $user->getNickname(),
                    'name' => $user->getName(),
                    'profile_picture' => $user->getImageurl(),
                    'oauth_token' => $token
                );

                \Mobile\Controller\WidgetController::setData($page, 'oAuth', $oAuth);

                echo \View::make('widget::admin.oauth-success')->with([
                    'app' => $app,
                    'page' => $page
                ]);
                die();
        
            } catch (Exception $e) {
        
                // Failed to get user details
                exit('Oh dear...');
            }
        }
    }

    /**
     * Disconnect account, remove oAuth data
     */
    public function oAuthDisconnect($app, $page)
    {
        \Mobile\Controller\WidgetController::setData($page, 'oAuth', NULL);
    }
}