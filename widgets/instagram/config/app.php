<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Instagram Client ID
	|--------------------------------------------------------------------------
	|
	| In order to use this widget, you need an Instagram Client ID and Client secret.
    | (http://darkwhispering.com/how-to/get-a-instagram-client_id-key).
    | 
	| 1. Go to the Instagram Developer page, https://instagram.com/developer/ and log in.
	| 2. Click on "Register your application".
	| 3. If you don’t already have a application registered, you will now see a form to register a new one. 
    |    If you instead see a list of "clients", click the "Register new client" at the top right. 
    | 4. Set application name to your websites name.
	| 5. Type in a description, something like "Registering to get a client ID to use with Mobile Site Builder Pro".
	| 6. Add the url of your site at the Website URL and as a Redirect URI(s) use http://[yourdomain.com]/api/v1/widget/get/instagram/oAuth.
	| 7. Hit register.
	| 8. Click "Manage clients" in the top menu and you will now see you new client in the list and a client id, client secret and more. 
    |    Copy the client id and client secret and paste that in the file /widgets/instagram/config/app.php.
	| 9. Save and you are ready to use the Instagram widget.
	| 
	| Alternatively, if you don't want to use this widget, open the file
	| /widgets/instagram/config/widget.php and set 'active' => false.
	|
	*/

	'client_id'             => '',
	'client_secret'         => ''
);
