<?php
$theme_default = (isset($hook_config['themes']['default'])) ? $hook_config['themes']['default'] : 'white';
$theme_user_can_change = (isset($hook_config['themes']['user_can_change'])) ? $hook_config['themes']['user_can_change'] : true;

if ($theme_user_can_change)
{
	$current_theme = \App\Core\Settings::get('theme', $theme_default, \Auth::user()->id);
?>
    						<li class="dropdown">
								<a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-tint"></i> <?php echo trans('global.theme') ?> <span class="caret"></span></a>
								<ul class="dropdown-menu" id="switch-theme">
<?php
	$themes = array(
		'default' => 'Default',
		'clean' => 'Clean',
		'fresh' => 'Fresh',
		'asphalt' => 'Asphalt',
		'purple-hills' => 'Purple Hills',
		'adminflare' => 'Adminflare',
		'dust' => 'Dust',
		'frost' => 'Frost',
		'silver' => 'Silver',
		'white' => 'White'
	);

	foreach($themes as $theme => $theme_title)
	{
		$active = ($theme == $current_theme) ? ' class="active"' : '';
		echo '<li' . $active . ' data-theme="' . $theme . '"><a href="javascript:switchTheme(\'' . $theme . '\');">' . $theme_title . '</a></li>';
	}
?>
								</ul>
							</li>
<script>
function switchTheme (theme)
{
	$('body').removeClass('theme-default theme-clean theme-fresh theme-asphalt theme-purple-hills theme-adminflare theme-dust theme-frost theme-silver theme-white');
	$('body').addClass('theme-' + theme);
	$('#switch-theme').find('li').removeClass('active');
	$('#switch-theme').find('li[data-theme=' + theme + ']').addClass('active');

    var jqxhr = $.ajax({
	  type: 'POST',
	  url: app_root + "/api/v1/hook/ajax/themes",
	  data: { theme: theme },
	  cache: false
	})
	.done(function(data) {
		showSaved();
	})
	.fail(function() {
	  console.log('Error changing theme');
	});
}
init.push(function () {
	$('body').removeClass('theme-default theme-clean theme-fresh theme-asphalt theme-purple-hills theme-adminflare theme-dust theme-frost theme-silver theme-white');
	$('body').addClass('theme-<?php echo $current_theme ?>');
});
</script>
<?php
}
else
{
?>
<script>
init.push(function () {
	$('body').removeClass('theme-default theme-clean theme-fresh theme-asphalt theme-purple-hills theme-adminflare theme-dust theme-frost theme-silver theme-white');
	$('body').addClass('theme-<?php echo $theme_default ?>');
});
</script>
<?php	
}
?>