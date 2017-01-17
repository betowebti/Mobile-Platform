<link rel="stylesheet" href="{{ url('hooks/assets/themes/css/style.css') }}" />
<?php
if (! \Auth::check())
{
$theme_default = (isset($hook_config['themes']['default'])) ? $hook_config['themes']['default'] : 'white';
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