<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| (External) links
	|--------------------------------------------------------------------------
	|
	| toc_url refers to url where ToS is found. If toc_url is empty, no agreement is shown at signup.
	|
	*/

	'toc_url' => '',

	/*
	|--------------------------------------------------------------------------
	| Allow registration
	|--------------------------------------------------------------------------
	|
	| If false, visitors can't register.
	|
	*/

	'allow_registration' => true,

	/*
	|--------------------------------------------------------------------------
	| Show homepage
	|--------------------------------------------------------------------------
	|
	| If false, no homepage is shown and visitors are redirected to /platform
	|
	*/

	'show_homepage' => true,

	/*
	|--------------------------------------------------------------------------
	| SEO
	|--------------------------------------------------------------------------
	|
	| Search Engine Optimization according to the Google _escaped_fragment_ manifest
	| https://developers.google.com/webmasters/ajax-crawling/docs/getting-started
	| Please note there're some widgets that can't be spidered because the content
	| is set with AJAX.
	|
	*/

	'seo' => true

);
