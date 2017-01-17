<?php
namespace Campaign\Model;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Eloquent, DB;
use Watson\Validating\ValidatingTrait;

Class Campaign extends Eloquent
{
	use ValidatingTrait;
	use SoftDeletingTrait;

    protected $table='campaigns';

    public function __construct(array $attributes = array()) {

        parent::__construct($attributes);

        static::creating(function($item)
        {
        });

        static::updating(function($item)
        {
        });
    }

    /**
     * Validation rules
     */
 
    protected $rules = array(
        'user_id'    => 'required|integer',
        'name'       => 'required|between:1,32',
    );

	/**
	 * Soft delete
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	public function getDates()
	{
		return array('created_at', 'updated_at', 'deleted_at', 'date_start', 'date_end');
	}

	public function getAttribute($key)
	{
		$value = parent::getAttribute($key);
		if($key == 'settings' && $value)
        {
		    $value = json_decode($value);
		}
		return $value;
	}

	public function setAttribute($key, $value)
	{
		if($key == 'settings' && $value)
        {
		    $value = json_encode($value);
		}
		parent::setAttribute($key, $value);
	}

	public function toArray()
	{
		$attributes = parent::toArray();
		if(isset($attributes['settings']))
        {
			$attributes['settings'] = json_decode($attributes['settings']);
		}
		return $attributes;
	}

    public function apps()
    {
        return $this->hasMany('Mobile\Model\App');
    }

}