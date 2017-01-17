<?php
namespace Mobile\Model;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Watson\Validating\ValidatingTrait;
use Eloquent, DB;

Class App extends Eloquent implements StaplerableInterface
{
    use EloquentTrait;
	use ValidatingTrait;

    protected $table = 'apps';

    /**
     * Validation rules
     */
/*
    protected $rules = array(
        'name' => 'required|alpha_dash|between:1,32'
    );
*/
    /**
     * Laravel-Stapler
     */

    protected $fillable = ['header', 'background_smarthpones'];

    public function __construct(array $attributes = array(), $exists = false) {

        // Is used for custom icon and header icon for future use
        $this->hasAttachedFile('header', [
            'styles' => [
                'icon1024' => '1024x1024#',
                'icon152' => '152x152#',
                'icon140' => '140x140#',
                'icon120' => '120x120#',
                'icon80' => '80x80#',
                'icon76' => '76x76#',
                'icon70' => '70x70#',
                'icon40' => '40x40#',
                'icon21' => '21x21#'
            ]
        ]);
        $this->hasAttachedFile('background_smarthpones', [
            'styles' => [
                'bg' => '640x1136#'
            ]
        ]);

        parent::__construct($attributes, $exists);

        //parent::boot();

        static::creating(function($item)
        {
        });

        static::updating(function($item)
        {
        });
    }

	/**
	 * Soft delete
	 *
	 * @var array
	 */
	protected $dates = ['deleted_at'];

	public function scopeDomain($query)
	{
		return (trim($this->domain) != '') ? 'http://' . $this->domain : url('/mobile/' . $this->local_domain);
	}

	public function scopeIcon($query, $size = 70, $default = false)
	{
        if ($this->header_file_name != '' && ! $default)
        {
            return $this->header->url('icon' . $size);
        }
        elseif (trim($this->icon) != '')
        {
            return url('/static/app-icons/' . $this->icon . '/' . $size . '.png');
        }
        else
        {
            return url('/static/app-icons/' . $this->appType->app_icon . '/' . $size . '.png');
        }
        
		//return (trim($this->icon) != '') ? $this->icon : $this->appType->app_icon;
	}

	public function scopeTheme($query)
	{
		return (trim($this->theme) != '') ? $this->theme : $this->appType->name;
	}

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function campaign()
    {
        return $this->belongsTo('Campaign\Model\Campaign');
    }

    public function appPages()
    {
        return $this->hasMany('Mobile\Model\AppPage')->orderBy('lft');
    }

    public function appType()
    {
        return $this->belongsTo('Mobile\Model\AppType')->orderBy('sort');
    }

    public function scenarioBoards()
    {
        return $this->belongsToMany('Beacon\Model\ScenarioBoard');
    }

    public function appStats()
    {
        return $this->hasMany('Analytics\Model\AppStat');
    }
}