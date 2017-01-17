<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Catalogs
	|--------------------------------------------------------------------------
	|
	| These variables can be used throughout the widget like \Config::get('widget::widget.active').
	| Config can contain trans('widget::global.name') variables.
	|
	*/

	'active'             => true,
	'can_be_home'        => true,
	'allow_multiple'     => true,
	'icon'               => 'triptych',
	'default_icon'       => 'ion-ios-folder',
	'color'              => '9e0b5d',
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

	'recommended'        => array(1, 3, 6, 7, 8),
	'name'               => trans('widget::global.name'),

	/*
	|--------------------------------------------------------------------------
	| Data structure configuration
	|--------------------------------------------------------------------------
	|
	| Types: image, text, textarea, boolean 
	| To do: wysiwyg, number, price, date, time, datetime, icon
	|
	*/

	'structure' => [
		'catalog' => [
			'name' => trans('widget::global.catalog'),
			'options' => [
				'image' => [
					'name' => trans('widget::global.image'),
					'type' => 'image',
					'thumb' => ['w' => 420, 'h' => 310],
					'required' => false
				],
				'title' => [
					'name' => trans('widget::global.title'),
					'type' => 'text',
					'required' => true
				],
				'description' => [
					'name' => trans('widget::global.description'),
					'type' => 'textarea',
					'rows' => 4,
					'required' => false
				],
				'active' => [
					'name' => trans('widget::global.active'),
					'type' => 'boolean',
					'default' => 1
				]
			],
			'show_in_list' => ['image', 'title', 'description', 'active']
		],
		'category' => [
			'name' => trans('widget::global.category'),
			'name_edit' => trans('widget::global.edit_categories'),
			'options' => [
				'image' => [
					'name' => trans('widget::global.image'),
					'type' => 'image',
					'thumb' => ['w' => 160, 'h' => 160],
					'required' => false
				],
				'title' => [
					'name' => trans('widget::global.title'),
					'type' => 'text',
					'required' => true
				],
				'description' => [
					'name' => trans('widget::global.description'),
					'type' => 'textarea',
					'required' => false
				],
				'active' => [
					'name' => trans('widget::global.active'),
					'type' => 'boolean',
					'default' => 1
				]
			]
		],
		'item' => [
			'name' => trans('widget::global.item'),
			'name_edit' => trans('widget::global.edit_items'),
			'options' => [
				'image' => [
					'name' => trans('widget::global.image'),
					'type' => 'image',
					'thumb' => ['w' => 80, 'h' => 80],
					'required' => false
				],
				'title' => [
					'name' => trans('widget::global.title'),
					'type' => 'text',
					'required' => true
				],
				'description' => [
					'name' => trans('widget::global.description'),
					'type' => 'textarea',
					'required' => false
				],
				'active' => [
					'name' => trans('widget::global.active'),
					'type' => 'boolean',
					'default' => 1
				]
			],
			'show_in_list' => ['image', 'title', 'description']
		]
	]
);