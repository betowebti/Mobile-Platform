<?php
/*
 |--------------------------------------------------------------------------
 | Check configuration
 |--------------------------------------------------------------------------
 */

if(\Config::get('widget::oauth.api_key') == '' || \Config::get('widget::oauth.api_secret') == '')
{
	echo "<div class=\"alert alert-danger\">This widget is not configured yet. Please check <strong>/widgets/twitter/config/oauth.php</strong>.</div>";
}
else
{
	/*
	 |--------------------------------------------------------------------------
	 | Cache
	 |--------------------------------------------------------------------------
	 */

	$field_label = trans('widget::global.cache');
	$field_name = 'cache';
	$field_options = array(
		1 => trans('widget::global.minute', ['amount' => 1]),
		5 => trans('widget::global.minutes', ['amount' => 5]),
		10 => trans('widget::global.minutes', ['amount' => 10]),
		30 => trans('widget::global.minutes', ['amount' => 30]),
		60 => trans('widget::global.hour', ['amount' => 1]),
		720 => trans('widget::global.hours', ['amount' => 12]),
		1440 => trans('widget::global.hours', ['amount' => 24])
	);
	$field_default_value = 1;
	$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
	$field_help = '';

	echo Former::select()
		->name($field_name)
		->forceValue($field_value)
		->label($field_label)
		->placeholder(' ')
		->options($field_options)
		->help($field_help)
		->class('select2-required'); 

	echo '<hr>';

	/*
	 |--------------------------------------------------------------------------
	 | Check oAuth
	 |--------------------------------------------------------------------------
	 */
	if($oAuth == NULL)
	{
		echo '<a href="'. url('/api/v1/widget/get/twitter/oAuth?sl=' . $sl) . '" class="btn btn-success btn-lg btn-block" target="_blank"><i class="fa fa-twitter"></i> ' . trans('widget::global.connect_account') . '</a>';
	}
	else
	{
		echo '<a href="javascript:void(0);" onclick="widgetDisconnectAccount()" class="btn btn-danger btn-lg btn-block"><i class="fa fa-unlock"></i> ' . trans('widget::global.disconnect_account') . '</a>';
	}
?>
<script>

function widgetOAuthCallback()
{
	reloadPreview();
	getAppPageContent('{{ $sl }}', 'page-content-tab');
	showSaved();
}

function widgetDisconnectAccount()
{
	if(confirm("{{ trans('widget::global.confirm_disconnect') }}"))
	{
		var jqxhr = $.ajax({
		  type: 'GET',
		  url: "{{ url('/api/v1/widget/get/twitter/oAuthDisconnect') }}",
		  data: { sl: "{{ $sl }}" },
		  cache: false
		})
		.done(function(data) {
			reloadPreview();
			getAppPageContent('{{ $sl }}', 'page-content-tab');
			showSaved();
		})
		.fail(function() {
		  console.log('Error loading page info: ' + url);
		})
		.always(function() {
		});
	}
}
</script>
<?php
}
?>