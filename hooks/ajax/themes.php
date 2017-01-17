<?php
$theme = \Input::get('theme', '');

if ($theme != '')
{
	\App\Core\Settings::set('theme', $theme, \Auth::user()->id);
}