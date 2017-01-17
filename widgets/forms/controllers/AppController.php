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
        $sl =  \App\Core\Secure::array2string(array('app_id' => $app->id, 'page_id' => $page->id));

        echo \View::make('widget::app.index')->with([
			'app' => $app,
			'page' => $page,
			'sl' => $sl
		])->render();
	}

    /**
     * Process form post
     */
    public function postForm($app, $page)
    {
        $form = \Request::except('sl', 'el-required');
		$error = false;

		// Parse settings
		$i = 0;
		$data = array();
        $html = '<table border="0" cellspacing="0" cellpadding="5">';

		foreach($form as $key => $val)
		{
			$required = (bool)\Input::get('el-required.' . $i . '', 0);
			$label = (isset($val[0])) ? $val[0] : '';
			$value = (isset($val[1])) ? $val[1] : '';

			if(isset($val['opts']))
			{
				$value = implode('<br>', $val['opts']);
			}

			if($required && $value == '')
			{
				$error .= ' - ' . $label . "<br>";
			}

			$data[] = array('name' => trim($label), 'val' => trim($value));
            $html .= '<tr><td valign="top"><strong>' . trim($label) . '</strong></td><td valign="top">' . str_replace("\n", '<br>', trim($value)) . '</td></tr>';
			$i++;
		}

        $html .= '</table>';

		if(! $error)
		{
			$app_user_data = new \Mobile\Model\AppUserData;

			$app_user_data->app_id = $app->id;
			$app_user_data->app_page_id = $page->id;

			$app_user_data->name = '[' . $app->name . '] ' . $page->name;
			$app_user_data->value = json_encode($data);

			$app_user_data->save();

			// Send mail
			$recipients = \Mobile\Controller\WidgetController::getData($page, 'recipients', '[""]');
			$recipients = json_decode($recipients);
			$recipients = $recipients[0];
			$recipients = explode(',', $recipients);

			if(count($recipients) > 0 && isset($recipients[0]) && $recipients[0] != '')
			{
				$subject = '[' . $_SERVER['HTTP_HOST'] . '] ' . $app->name . ' - ' . $page->name;
	
				\Mail::send('widget::admin.mail', ['body' => $html], function($message) use($app, $recipients, $subject)
				{
					$message->from(\Config::get('mail.from.address'), $app->name);
					$message->to($recipients)->subject($subject);
				});
			}

			$success_message = \Mobile\Controller\WidgetController::getData($page, 'success_message', trans('widget::global.success_message_default'));
			return \Response::json(array('msg' => str_replace(chr(10), '<br>', $success_message)));
		}
		else
		{
			$error = trans('widget::global.required_error') . "<br><br>" . $error;
			return \Response::json(array('msg' => $error));
		}
	}
}