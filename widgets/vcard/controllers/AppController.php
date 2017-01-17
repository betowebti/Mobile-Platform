<?php
namespace Widget\Controller;

require_once __DIR__ . '/../vendor/transliterator/src/Behat/Transliterator/Transliterator.php';
require_once __DIR__ . '/../vendor/vcard/src/VCard.php';

use Behat\Transliterator\Transliterator;
use JeroenDesloovere\VCard\VCard;

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
		$vcard_button = \Mobile\Controller\WidgetController::getData($page, 'vcard_button', trans('widget::global.download_vcard'));
		$vcard_data = \Mobile\Controller\WidgetController::getData($page, 'vcard', '');

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'vcard_data' => $vcard_data,
			'vcard_button' => $vcard_button,
			'sl' => $sl
		]);
	}

    /**
     * Download vCard
     */
    public function getDownload($app, $page)
    {
		$vcard_data = \Mobile\Controller\WidgetController::getData($page, 'vcard', '');

		if($vcard_data != '')
		{
			$vcard_data = json_decode($vcard_data, true);

			// define vcard
			$vcard = new VCard();

			// add personal data
			$vcard->addName($vcard_data['last_name'], $vcard_data['first_name'], '', (isset($vcard_data['prefix'])) ? $vcard_data['prefix'] : '', (isset($vcard_data['prefix'])) ? $vcard_data['suffix'] : '');

			// add work data
			$vcard->addCompany((isset($vcard_data['company'])) ? $vcard_data['company'] : '');
			$vcard->addJobtitle((isset($vcard_data['job_title'])) ? $vcard_data['job_title'] : '');
			$vcard->addEmail((isset($vcard_data['email'])) ? $vcard_data['email'] : '');
			$vcard->addPhoneNumber((isset($vcard_data['phone_work'])) ? $vcard_data['phone_work'] : '', 'PREF;WORK');
			$vcard->addAddress(null, null, (isset($vcard_data['business_street'])) ? $vcard_data['business_street'] : '', (isset($vcard_data['business_city'])) ? $vcard_data['business_city'] : '', (isset($vcard_data['business_state'])) ? $vcard_data['business_state'] : '', (isset($vcard_data['business_zip'])) ? $vcard_data['business_zip'] : '', (isset($vcard_data['business_country'])) ? $vcard_data['business_country'] : '', 'WORK;POSTAL');
			$vcard->addURL((isset($vcard_data['work_website'])) ? $vcard_data['work_website'] : '');

			// Personal data
			$vcard->addPhoneNumber((isset($vcard_data['phone_home'])) ? $vcard_data['phone_home'] : '', 'HOME');
			$vcard->addAddress(null, null, (isset($vcard_data['home_street'])) ? $vcard_data['home_street'] : '', (isset($vcard_data['home_city'])) ? $vcard_data['home_city'] : '', (isset($vcard_data['home_state'])) ? $vcard_data['home_state'] : '', (isset($vcard_data['home_zip'])) ? $vcard_data['home_zip'] : '', (isset($vcard_data['home_country'])) ? $vcard_data['home_country'] : '', 'HOME');
			$vcard->addURL((isset($vcard_data['personal_website'])) ? $vcard_data['personal_website'] : '');

			$photo = (isset($vcard_data['photo'])) ? $vcard_data['photo'] : '';
			if($photo != '')
			{
				$vcard->addPhoto(url($photo));
			}

			// return vcard as a string
			//return $vcard->getOutput();

			// return vcard as a download
			return $vcard->download();
		}
	}
}