<?php
namespace Widget\Controller;

/*
|--------------------------------------------------------------------------
| Widget app controller
|--------------------------------------------------------------------------
|
| App related logic
|
*/

class AppController extends \BaseController {

    /**
	 * Construct
     */
    public function __construct()
    {
    }

    /**
     * Main view
     */
    public function getIndex($app, $page)
    {
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));
		$products = array();
		$images_found = false;
		$shop_title = '';
		$shop_desc = '';

		$currency = \Mobile\Controller\WidgetController::getData($page, 'currency', 'USD');
		$flat_rate = \Mobile\Controller\WidgetController::getData($page, 'flat_rate', '0');
		$quantity_rate = \Mobile\Controller\WidgetController::getData($page, 'quantity_rate', '0');
		$total_rate = \Mobile\Controller\WidgetController::getData($page, 'total_rate', '0.00');
		$tax_rate = \Mobile\Controller\WidgetController::getData($page, 'tax_rate', '0.00');
		$tax_shipping = \Mobile\Controller\WidgetController::getData($page, 'tax_shipping', 1);
		$payment_provider = \Mobile\Controller\WidgetController::getData($page, 'payment_provider', 'PayPal');
		$payment_provider_email = \Mobile\Controller\WidgetController::getData($page, 'payment_provider_email', '');
		$sandbox = \Mobile\Controller\WidgetController::getData($page, 'sandbox', 1);
		$simpleCart = 'simpleCart';

/*
		// Check all e-commerce pages if there's discrepancy between settings 
		// like currency & tax rate so different simpleCart instances should be used.
		// This makes it possible to have multiple pages with different currencies, tax rates, etc..

		$conflict = false;
		$pages = $app->appPages()->where('widget', 'e-commerce')->where('id', '<>', $page->id)->get();

		if(count($pages) > 0)
		{
			$check_for = array(
				array('key' => 'currency', 'default' => 'USD', 'current' => $currency),
				array('key' => 'tax_rate', 'default' => '0.00', 'current' => $tax_rate),
				array('key' => 'flat_rate', 'default' => '0', 'current' => $flat_rate)
			);

			foreach($pages as $widget)
			{
				foreach($check_for as $check)
				{
					$check_this[$check['key']] = \Mobile\Controller\WidgetController::getData($widget, $check['key'], $check['default']);
					if($check_this[$check['key']] != $check['current']) $conflict = true; //$simpleCart = 'simpleCart' . $page->id;
				}
			}
		}
*/
		$products_list = \Mobile\Controller\WidgetController::getData($page, 'products', '');
		$products_list = json_decode($products_list);

		$i = 0;
		$js = '';
		if($products_list != NULL)
		{
			foreach($products_list->title as $row)
			{
				if((boolean)$products_list->active[$i] && trim($products_list->title[$i]) != '' && trim($products_list->price[$i]) != '')
				{
					$photo = $products_list->photo[$i];
					$thumb = ($photo != '') ? url('/api/v1/thumb/nail?w=160&h=160&img=' . $photo) : '';
					if($photo != '') $images_found = true;

					$products[] = array(
						'title' => $products_list->title[$i],
						'desc' => $products_list->desc[$i],
						'photo' => $photo,
						'thumb' => $thumb,
						'price' => $products_list->price[$i]
					);
				}
				$i++;
			}
		}

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl,
			'shop_title' => $shop_title,
			'shop_desc' => $shop_desc,
			'images_found' => $images_found,
			'products' => $products,
			'currency' => $currency,
			'payment_provider' => $payment_provider,
			'payment_provider_email' => $payment_provider_email,
			'flat_rate' => $flat_rate,
			'quantity_rate' => $quantity_rate,
			'total_rate' => $total_rate,
			'tax_rate' => $tax_rate,
			'tax_shipping' => $tax_shipping,
			'sandbox' => (boolean) $sandbox,
			'simpleCart' => $simpleCart
		]);
	}

    /**
     * Payment cancel
     */
    public function paymentCancel()
    {
		$sl = \Input::get('sl', '');
		$qs = \App\Core\Secure::string2array($sl);

		$app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->first();
		$page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

		$url = 'http://' . $app->domain() . '#/nav/' . $page->slug;

		echo '<script>document.location = "' . $url . '";</script>';
	}

    /**
     * Payment success
     */
    public function paymentSuccess($app, $page)
    {
		$sl = \Input::get('sl', '');
		$qs = \App\Core\Secure::string2array($sl);

		$app = \Mobile\Model\App::where('id', '=', $qs['app_id'])->first();
		$page = $app->appPages()->where('id', '=', $qs['page_id'])->first();

		$url = 'http://' . $app->domain() . '#/nav/' . $page->slug . '';

		\Session::put('empty_cart', true);

		echo '<script>document.location = "' . $url . '";</script>';
	}
}