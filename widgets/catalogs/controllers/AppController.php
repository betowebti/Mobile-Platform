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
        $sl = \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));
		$hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'hashPrefix' => $hashPrefix,
			'sl' => $sl
		]);
	}

    /**
     * Get catalog
     */
    public function getCatalogs($app, $page)
    {
		$hashPrefix = (\Config::get('system.seo', true)) ? '!' : '';
		$back = '#' . $hashPrefix . '/nav/' . $page->slug;
		$found = 0;
		$return_tmp = array();
		$catalogs = \Mobile\Controller\WidgetController::getData($page, 'catalogs[]', NULL);

		if ($catalogs != NULL)
		{
			foreach ($catalogs as $key1 => $catalog)
			{
				if ((boolean) $catalog->active)
				{
					$catalog_structure_categories = $catalog->structure_categories;
					$index = count($return_tmp);
					$return_tmp[$index] = $catalog;
					$return_tmp[$index]->structure_categories = [];

					foreach ($catalog_structure_categories as $key2 => $category)
					{
						if ((boolean) $category->active)
						{
							$category_structure_items = $category->structure_items;
							$cat_index = count($return_tmp[$index]->structure_categories);
							$return_tmp[$index]->structure_categories[$cat_index] = $category;
							$return_tmp[$index]->structure_categories[$cat_index]->structure_items = [];

							foreach ($category_structure_items as $key3 => $item)
							{
								if ((boolean) $item->active)
								{
									$return_tmp[$index]->structure_categories[$cat_index]->structure_items[] = $item;
								}
							}
							
						}
					}

					$found++;
				}
			}
			$return['items'] = $return_tmp;
		}

		$return['found'] = $found;

		return \Response::json($return);
	}

    /**
	 * -------------------------------------------------------------------------------------------------------------------
     * Admin
     */

    /**
     * Edit catalog admin
     */
    public function editCatalog($app, $page)
    {
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));
		$structure = \Config::get('widget::widget.structure');

        return \View::make('admin.edit-catalog')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl,
			'structure' => $structure['catalog']
		]);
	}

    /**
     * Edit categories admin
     */
    public function editCategories($app, $page)
    {
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));
		$structure = \Config::get('widget::widget.structure');
		$colspan = count($structure['category']['options']) + 2;

        return \View::make('admin.edit-categories')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl,
			'structure' => $structure,
			'colspan' => $colspan
		]);
	}

    /**
     * Edit items admin
     */
    public function editItems($app, $page)
    {
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));
		$structure = \Config::get('widget::widget.structure');
		$colspan = count($structure['item']['options']) + 2;

        return \View::make('admin.edit-items')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl,
			'structure' => $structure,
			'colspan' => $colspan
		]);
	}

    /**
     * Edit item admin
     */
    public function editItem($app, $page)
    {
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));
		$structure = \Config::get('widget::widget.structure');

        return \View::make('admin.edit-item')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl,
			'structure' => $structure
		]);
	}
}