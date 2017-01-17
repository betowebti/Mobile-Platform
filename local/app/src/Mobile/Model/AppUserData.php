<?php
namespace Mobile\Model;

use Eloquent, DB;

Class AppUserData extends Eloquent
{
    protected $table='app_user_data';

	/**
	 * Soft delete
	 *
	 * @var array
	 */

	public function app()
	{
		return $this->belongsTo('Mobile\Model\App');
	}

	public function appPage()
	{
		return $this->belongsTo('Mobile\Model\AppPage');
	}
}