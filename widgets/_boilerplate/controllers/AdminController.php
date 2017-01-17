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
        return \View::make('admin.content')->with([
			'app' => $app,
			'page' => $page
		]);
	}
}