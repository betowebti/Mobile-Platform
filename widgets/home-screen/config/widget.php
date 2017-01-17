<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Call Us
	|--------------------------------------------------------------------------
	|
	| These variables can be used throughout the widget like \Config::get('widget::widget.active').
	| Config can contain trans('widget::global.name') variables.
	|
	*/

	'active'             => true,
	'can_be_home'        => true,
	'allow_multiple'     => true,
	'icon'               => 'home168',
	'default_icon'       => 'ion-android-home',
	'color'              => '575456',
	'group'              => 'general', // general, media, monetization, social

	/*
	|--------------------------------------------------------------------------
	| Plans
	|--------------------------------------------------------------------------
	|
	| 1 = Free
	| 2 = Standard
	| 3 = Deluxe
	| 4 = Professional
	|
	*/

	'plans'              => array(1, 2, 3, 4),

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

	'recommended'        => array(1, 3, 4, 6, 8),
	'name'               => trans('widget::global.name'),
	'default_name'       => trans('widget::global.default_name')
);
