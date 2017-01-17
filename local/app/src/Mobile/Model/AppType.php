<?php
namespace Mobile\Model;

use Eloquent, DB;

Class AppType extends Eloquent
{

    protected $table='app_types';

    public $timestamps = false;

    public function app()
    {
        return $this->hasOne('Mobile\Model\App');
    }

    public function recommended()
    {
        return $this->belongsToMany('Mobile\Model\AppPageType', 'app_page_type_app_type', 'app_type_id');
    }

    public function appThemes()
    {
        return $this->hasMany('Mobile\Model\AppTheme');
    }
}