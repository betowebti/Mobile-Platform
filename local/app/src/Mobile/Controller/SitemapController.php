<?php
namespace Mobile\Controller;

/*
|--------------------------------------------------------------------------
| Sitemap controller
|--------------------------------------------------------------------------
|
| Sitemap related logic
|
*/

class SitemapController extends \BaseController {

    /**
     * Render sitemap
     */
    public function showSitemap($local_domain = NULL)
    {
		// Get app
		if ($local_domain == NULL)
		{
			// Check for custom user domain
			$url_parts = parse_url(\URL::current());
			$domain = str_replace('www.', '', $url_parts['host']);

			$app = \Mobile\Model\App::where('domain', '=', $domain)
				->orWhere('domain', '=', 'www.' . $domain)
				->first();
		}
		else
		{
			$app = \Mobile\Model\App::where('local_domain', '=', $local_domain)->first();
		}

		if(empty($app))
		{
			return \Redirect::to('/');
		}

		if (! (\Config::get('system.seo', true)))
		{
			return \Redirect::to($app->domain());
		}

		// Check expiration
		if (isset(\Auth::user()->expires) && \Auth::user()->expires->format("Y-m-d") < \Carbon::now()->format("Y-m-d"))
		{
			return View::make('user.app.message', array(
				'app_title' => trans('admin.account_expired'),
				'app_message' => trans('admin.account_is_expired')
			));
		}

		$hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';

		$sitemap = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
		$sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . PHP_EOL;
		$sitemap .= '  xmlns:xhtml="http://www.w3.org/1999/xhtml">' . PHP_EOL;

		foreach($app->appPages as $page)
		{
			if($page->hidden == 0)
			{
				$sitemap .= '  <url>' . PHP_EOL;
				$sitemap .= '    <loc>' . $app->domain() . '#' . $hashPrefix . '/nav/' . $page->slug . '</loc>' . PHP_EOL;
				$sitemap .= '  </url>' . PHP_EOL;
			}
		}

		$sitemap .= '</urlset>';

		header("Content-Type: text/xml;charset=utf-8");
		echo $sitemap;
    }
}