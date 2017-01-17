<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| API key
	|--------------------------------------------------------------------------
	|
	| In order to let people use this widget, you need a YouTube API key,
    | https://developers.google.com/youtube/v3/getting-started.
    | 
    | 1. Go to the Google Developers Console, https://console.developers.google.com/.
    | 2. Select a project, or create a new project.
	| 3. In the sidebar on the left, select APIs & auth. In the list of APIs, make 
	|    sure the status is ON for the YouTube Data API v3.
	| 4. In the sidebar on the left, select Credentials.
	| 5. At 'Public API access' click 'Create new key' and choose 'Browser key'.
	| 6. Enter the website where you've installed the site builder and click 'Create'.
	|    Use a wildcard (*) after your domain, like http://mobile.nowsquare.com/*
	| 7. Copy the API key and paste it below.
	|
	| It may take a few minutes for your API key to become active.
	|
	| Alternatively, if you don't want to use this widget, open the file
	| /widgets/youtube/config/widget.php and set 'active' => false.
	|
	*/

	'api_key'               => ''
);