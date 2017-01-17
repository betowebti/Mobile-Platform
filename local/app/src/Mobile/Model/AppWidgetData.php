<?php
namespace Mobile\Model;

use Eloquent, DB;

Class AppWidgetData extends Eloquent
{
    protected $table='app_widget_data';

	/**
	 * Soft delete
	 *
	 * @var array
	 */

	public function appPage()
	{
		return $this->belongsTo('Mobile\Model\AppPage');
	}
}