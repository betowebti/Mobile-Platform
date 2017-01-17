<?php
$theme_default = (isset($hook_config['themes']['default'])) ? $hook_config['themes']['default'] : 'white';
$current_theme = (\Auth::check()) ?  \App\Core\Settings::get('theme', $theme_default, \Auth::user()->id) : $theme_default;

echo ' theme-' . $current_theme;
?>