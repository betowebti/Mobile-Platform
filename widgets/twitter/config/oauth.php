<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| oAuth
	|--------------------------------------------------------------------------
	|
	| In order to let people connect their account to this widget, you need a
    | Twitter API key (https://dev.twitter.com/faq#46).
    | 
    | Go to https://apps.twitter.com/ and click "Create New App". Fill in the 
    | details and for "Callback URL" only enter your domain (this cannot be left
	| blank allthough the actual callback url is set in the script). When the App
	| has been created, click the "Keys and Access Tokens" tab and paste the 
	| Consumer Key (api_key) and Consumer Secret (api_secret) below.
    | 
    | This widget doesn't need write access, so you can click "modify app permissions"
    | at Access Level, and select "Read only". Click "Update Settings" and you're
    | ready to go.
	|
	| Alternatively, if you don't want to use this widget, open the file
	| /widgets/twitter/config/widget.php and set 'active' => false.
	|
	*/

	'api_key'               => '',
	'api_secret'            => ''
);
