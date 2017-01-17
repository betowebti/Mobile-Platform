<?php
namespace Mobile\Core;

/**
 * Social class
 *
 *
 * @package		Mobile
 * @category	Social
 * @version		0.01
 * @since		2015-06-08
 * @author		Sem Kokhuis
 */

class Social {

    /**
     * Get code / html for social share buttons
     * \Mobile\Core\Social::shareButtons($app, $page)
     */
    public static function shareButtons($app, $page, $id = 'share', $title = NULL)
	{
		$settings = json_decode($app->settings);

		$social_buttons = array(
			'email' => 1, 
			'twitter' => 1, 
			'facebook' => 1, 
			'googleplus' => 1, 
			'linkedin' => 1, 
			'pinterest' => 1
		);

		$social = (isset($settings->social)) ? (array) $settings->social : $social_buttons;
		$shares = '';

		foreach ($social as $button => $checked)
		{
			if ((boolean) $checked) 
            {
                $icon = '';
                switch ($button)
                {
                    case 'email': $icon = 'ion-email'; break;
                    case 'twitter': $icon = 'ion-social-twitter'; break;
                    case 'facebook': $icon = 'ion-social-facebook'; break;
                    case 'googleplus': $icon = 'ion-social-googleplus'; break;
                    case 'linkedin': $icon = 'ion-social-linkedin'; break;
                    case 'pinterest': $icon = 'ion-social-pinterest'; break;
                }
                $shares .= '{ share: "' . $button . '", logo: "' . $icon . '"},';
            }
		}
        $shares = rtrim($shares, ',');

		$social_size = (isset($settings->social_size)) ? $settings->social_size : 14;
		$social_icons_only = (isset($settings->social_icons_only)) ? $settings->social_icons_only : 0;
		$showLabel = ((boolean) $social_icons_only) ? 'false' : 'true';
		$social_show_count = (isset($settings->social_show_count)) ? $settings->social_show_count : 0;
		$showCount = ((boolean) $social_show_count) ? 'true' : 'false';

        if ($title == NULL)
        {
            $social_share_text = \Mobile\Controller\WidgetController::getData($page, 'social_share_text', '');
            if ($social_share_text == '') $social_share_text = $app->name . ' - ' . $page->name;
        }
        else
        {
            $social_share_text = $title;
        }

		$return = '';

		$return .= '<script>
$(\'#' . $id . '\').jsSocials({
	shares: [' . $shares . '],
	url: window.location.href,
	text: "' . $social_share_text . '",
	showLabel: ' . $showLabel . ',
	showCount: ' . $showCount . '
});
$(".jssocials").css("font-size", "' . $social_size . 'px");
</script>';

		return $return;

	}
}