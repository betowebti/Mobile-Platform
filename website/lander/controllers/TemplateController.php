<?php
namespace Website\Controller;

/*
|--------------------------------------------------------------------------
| Template controller
|--------------------------------------------------------------------------
|
| Routes and other template logic
|
*/

class TemplateController extends \BaseController {

    /**
     * Routes
     */
    public function getRoute($url_parts)
    {
		$path = (! isset($url_parts['path']) || $url_parts['path'] == '/') ? '/' : $url_parts['path'];

		// Don't change this
		$favicon = \App\Core\Settings::get('favicon', '/favicon.ico');
		$page_title = \App\Core\Settings::get('page_title', trans('global.app_title'));
		$page_description = \App\Core\Settings::get('page_description', trans('global.app_title_slogan'));

		// Plans
		$plans = \App\Model\Plan::orderBy('sort')->get();

		switch (count($plans)) {
			case 1: $plans_class = 'col-sm-12 col-lg-12'; break;
			case 2: $plans_class = 'col-sm-12 col-lg-6'; break;
			case 3: $plans_class = 'col-sm-12 col-lg-4'; break;
			case 4: $plans_class = 'col-sm-12 col-lg-3'; break;
			case 5: $plans_class = 'col-sm-12 col-lg-4'; break;
			default: $plans_class = 'col-sm-12 col-lg-4';
		}

		// Widgets
		$widgets = \Mobile\Controller\WidgetController::loadAllWidgetConfig();

		// Template variables
		$demo_mode = \Lang::has('website::global.demo_mode') ? trans('website::global.demo_mode') : \Config::get('website::default.demo_mode');
		if ($_SERVER['HTTP_HOST'] == 'mobile.madewithpepper.com') $demo_mode = 1;
		$logo = \Lang::has('website::global.logo') ? trans('website::global.logo') : \Config::get('website::default.logo');
		$logo_retina = \Lang::has('website::global.logo_retina') ? trans('website::global.logo_retina') : \Config::get('website::default.logo_retina');
		$logo_footer = \Lang::has('website::global.logo_footer') ? trans('website::global.logo_footer') : \Config::get('website::default.logo_footer');
		$logo_footer_retina = \Lang::has('website::global.logo_footer_retina') ? trans('website::global.logo_footer_retina') : \Config::get('website::default.logo_footer_retina');
		$scheme = \Lang::has('website::global.scheme') ? trans('website::global.scheme') : \Config::get('website::default.scheme');
		$header = \Lang::has('website::global.header') ? trans('website::global.header') : \Config::get('website::default.header');
		$custom_header = \Lang::has('website::global.custom_header') ? trans('website::global.custom_header') : \Config::get('website::default.custom_header');
        if ($custom_header != '') $header = $custom_header;
		$section = \Lang::has('website::global.section') ? trans('website::global.section') : \Config::get('website::default.section');
		$custom_section = \Lang::has('website::global.custom_section') ? trans('website::global.custom_section') : \Config::get('website::default.custom_section');
        if ($custom_section != '') $section = $custom_section;

		$phone = \Lang::has('website::global.phone') ? trans('website::global.phone') : \Config::get('website::default.phone');
		$google_analytics = \Lang::has('website::global.google_analytics') ? trans('website::global.google_analytics') : \Config::get('website::default.google_analytics');
		if ($_SERVER['HTTP_HOST'] == 'mobile.madewithpepper.com') $google_analytics = 'UA-61577613-1';

		switch($path)
		{
			case '/': 
		        return \View::make('home', compact(
					'favicon',
					'page_title',
					'page_description',
					'plans',
					'plans_class',
					'widgets',
					'demo_mode',
					'logo',
					'logo_retina',
					'logo_footer',
					'logo_footer_retina',
					'scheme',
					'header',
					'section',
					'google_analytics',
					'phone'
				));
				break;
			default:
				return \Response::view('app.errors.404', [], 404);
		}
	}

    /**
     * Form post
     */
    public function form()
    {
		$input = \Request::all();

		$body = '<table cellspacing="0" cellpadding="5">';
		foreach ($input as $name => $val)
		{
			$body .= '<tr>';
			$body .= '<td valign="top">' . $name . '</td>';
			$body .= '<td valign="top">' . str_replace([chr(10), chr(11), chr(13)], '<br>', $val) . '</td>';
			$body .= '</tr>';
		}
		$body .= '</table>';
		$body .= '<hr>';
		$body .= 'IP: ' . \App\Core\IP::address();

		$email = \Lang::has('website::global.email') ? trans('website::global.email') : \Config::get('website::default.email');
		$subject = trans('website::global.txt_get_in_touch');

		if ($email == '')
		{
			if ((\Auth::user()->parent_id == NULL))
			{
				$email = \Auth::user()->email;
			}
			else
			{
				$user = \User::find(\Auth::user()->parent_id);
				$email = $user->email;
			}
		}

		// Send mail
		$recipients = explode(',', $email);

		if(count($recipients) > 0 && isset($recipients[0]) && $recipients[0] != '')
		{
			$subject = '[' . $_SERVER['HTTP_HOST'] . '] ' . $subject;

			\Mail::send('website::admin.mail', ['body' => $body], function($message) use($input, $recipients, $subject)
			{
				$message->from($input['email'], $input['name'])->to($recipients)->subject($subject);
			});
		}
	}
}