<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Loyalty Cards
	|--------------------------------------------------------------------------
	|
	| These variables can be used throughout the widget like \Config::get('widget::widget.active').
	| Config can contain trans('widget::global.name') variables.
	|
	*/

	'active'             => true,
	'can_be_home'        => true,
	'allow_multiple'     => true,
	'icon'               => 'printing12',
	'default_icon'       => 'ion-android-checkmark-circle',
	'color'              => 'f4511e',
	'group'              => 'monetization', // general, media, monetization, social

	/*
	|--------------------------------------------------------------------------
	| Recommended
	|--------------------------------------------------------------------------
	|
	| 1 = business
	| 2 = music
	| 3 = events
	| 4 = restaurants
	| 5 = blog
	| 6 = education
	| 7 = photography
	| 8 = other
	|
	*/

	'recommended'        => array(1, 4, 8),
	'name'               => trans('widget::global.name')
);
