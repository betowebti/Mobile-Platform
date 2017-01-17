<?php
namespace App\Core;

/**
 * Help class
 *
 *
 * @package		Core
 * @category	Base
 * @version		0.01
 * @since		2014-09-18
 * @author		Sem Kokhuis
 */

class Help extends \BaseController {

    /**
     * Show help text in popover, AJAX loaded
	 * echo \App\Core\Help::popover('incorrect_time', 'top');
     */
    public static function popover($item, $placement = 'top')
    {
        // ng-click="setPopoverContent(\'' . url('/app/help/' . $item) . '\')" 
        // popover="{{helpPopover}}"
        return '<span class="help-popover" popover="' . Help::getHelp($item) . '" popover-trigger="mouseenter" popover-placement="' . $placement . '" popover-append-to-body="true"><i class="fa fa-question-circle"></i></span>';
    }

    /**
     * Show help text in popover
     */
    public static function getHelp($item)
    {
        switch($item)
        {
            case 'role': 
                return '<p>' . trans('global.help_role1') . '</p><p>' . trans('global.help_role2') . '</p><p>' . trans('global.help_role3') . '</p>'; 
                break;
            case 'role_owner': 
                return '<p>' . trans('global.help_role_owner1') . '</p><p>' . trans('global.help_role_owner2') . '</p>'; 
                break;
			case 'incorrect_time': return trans('global.help_incorrect_time'); break;
        }
    }

    /**
     * Hopscotch editor tour
     */
	public function getEditor($lang = 'en')
	{
        $tour = array(
            'id' => 'app-editor',
            'showPrevButton' => true,
            'i18n' => array(
                            'nextBtn' => trans('global.next'),
                            'prevBtn' => trans('global.back'),
                            'doneBtn' => trans('global.done'),
                            'closeTooltip' => trans('global.close') 
                        ),
            'steps' => array(
                array(
                    'title' => trans('help.add_new_page'),
                    'content' => trans('help.add_new_page_help'),
                    'target' => 'tour-add-page',
                    'fixedElement' => false,
                    'xOffset' => '0',
                    'yOffset' => '13',
                    'placement' => 'right'
                ),
                array(
                    'title' => trans('help.design_layout'),
                    'content' => trans('help.design_layout_help'),
                    'target' => 'tour-design',
                    'fixedElement' => false,
                    'xOffset' => '0',
                    'yOffset' => '0',
                    'placement' => 'bottom'
                ),
                array(
                    'title' => trans('help.options'),
                    'content' => trans('help.options_help'),
                    'target' => 'tour-options',
                    'fixedElement' => false,
                    'xOffset' => '0',
                    'yOffset' => '-17',
                    'zIndex' => '99999',
                    'placement' => 'left'
                )
             )
        );

		return \Response::json($tour, 200);
	}
}