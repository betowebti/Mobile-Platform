<?php
namespace Mobile\Model;

use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;
use Baum\Node;
use Eloquent, DB;

/**
* AppPage
*/
class AppPage extends Node implements StaplerableInterface {
    use EloquentTrait;

	/**
	* Table name.
	*
	* @var string
	*/
	protected $table = 'app_pages';

	/**
	 * Laravel-Stapler
	 */
	
	protected $fillable = ['header', 'background_smarthpones'];

	public function __construct(array $attributes = array()) {
		$this->hasAttachedFile('header', [
			'styles' => [
				'img' => '640x128'
			]
		]);
        $this->hasAttachedFile('background_smarthpones', [
            'styles' => [
                'bg' => '640x1136#'
            ]
        ]);
	
		parent::__construct($attributes);

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

	public function app()
	{
		return $this->belongsTo('Mobile\Model\App');
	}

	public function appWidgetData()
	{
		return $this->hasMany('Mobile\Model\AppWidgetData');
	}

  public function appStats()
  {
      return $this->hasMany('Analytics\Model\AppStat');
  }
  
  //////////////////////////////////////////////////////////////////////////////

  //
  // Below come the default values for Baum's own Nested Set implementation
  // column names.
  //
  // You may uncomment and modify the following fields at your own will, provided
  // they match *exactly* those provided in the migration.
  //
  // If you don't plan on modifying any of these you can safely remove them.
  //

  // /**
  //  * Column name which stores reference to parent's node.
  //  *
  //  * @var string
  //  */
  // protected $parentColumn = 'parent_id';

  // /**
  //  * Column name for the left index.
  //  *
  //  * @var string
  //  */
  // protected $leftColumn = 'lft';

  // /**
  //  * Column name for the right index.
  //  *
  //  * @var string
  //  */
  // protected $rightColumn = 'rgt';

  // /**
  //  * Column name for the depth field.
  //  *
  //  * @var string
  //  */
  // protected $depthColumn = 'depth';

  // /**
  //  * Column to perform the default sorting
  //  *
  //  * @var string
  //  */
  // protected $orderColumn = null;

  // /**
  // * With Baum, all NestedSet-related fields are guarded from mass-assignment
  // * by default.
  // *
  // * @var array
  // */
  // protected $guarded = array('id', 'parent_id', 'lft', 'rgt', 'depth');

  //
  // This is to support "scoping" which may allow to have multiple nested
  // set trees in the same database table.
  //
  // You should provide here the column names which should restrict Nested
  // Set queries. f.ex: company_id, etc.
  //

  // /**
  //  * Columns which restrict what we consider our Nested Set list
  //  *
  //  * @var array
  //  */
  // protected $scoped = array();

  //////////////////////////////////////////////////////////////////////////////

  //
  // Baum makes available two model events to application developers:
  //
  // 1. `moving`: fired *before* the a node movement operation is performed.
  //
  // 2. `moved`: fired *after* a node movement operation has been performed.
  //
  // In the same way as Eloquent's model events, returning false from the
  // `moving` event handler will halt the operation.
  //
  // Below is a sample `boot` method just for convenience, as an example of how
  // one should hook into those events. This is the *recommended* way to hook
  // into model events, as stated in the documentation. Please refer to the
  // Laravel documentation for details.
  //
  // If you don't plan on using model events in your program you can safely
  // remove all the commented code below.
  //

  // /**
  //  * The "booting" method of the model.
  //  *
  //  * @return void
  //  */
  // protected static function boot() {
  //   // Do not forget this!
  //   parent::boot();

  //   static::moving(function($node) {
  //     // YOUR CODE HERE
  //   });

  //   static::moved(function($node) {
  //     // YOUR CODE HERE
  //   });
  // }

}
