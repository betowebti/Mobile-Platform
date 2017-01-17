<?php
namespace Widget\Controller;

/*
|--------------------------------------------------------------------------
| Widget admin controller
|--------------------------------------------------------------------------
|
| Admin editor related logic
|
*/

class AdminController extends \BaseController {

    /**
	 * Construct
     */
    public function __construct()
    {
    }

    /**
     * Content tab
     */
    public function getContent($app, $page)
    {
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));
		$structure = \Config::get('widget::widget.structure');

		$colspan = count($structure['catalog']['show_in_list']) + 2;

        return \View::make('admin.content')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl,
			'structure' => $structure,
			'colspan' => $colspan
		]);
	}
}