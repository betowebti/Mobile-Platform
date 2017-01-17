<?php
$vars = array('logo', 'logo_retina', 'logo_footer', 'logo_footer_retina', 'scheme', 'header', 'custom_header', 'section', 'custom_section', 'phone', 'email', 'google_analytics', 'demo_mode');

foreach ($vars as $var)
{
	$val[$var] = \Lang::has('custom::global.' . $var) ? trans('custom::global.' . $var) : \Config::get('website::default.' . $var);
}

?>
<style type="text/css">
.horizontal-scroll {
	overflow-x:scroll;
	overflow-y:hidden;
	height:180px;
  	-webkit-overflow-scrolling: touch;
	width:100%;
	margin-bottom:20px;
}
.header-horizontal-scroll .image_picker_selector { width:{{ 7 * 268 }}px; }
</style>

<legend>{{ trans('website::admin.primary_color') }}</legend>
<?php
echo '<div class="color-horizontal-scroll horizontal-scroll">';
echo '<select name="scheme" class="image-picker">';
for ($i=1; $i < 7; $i++)
{
	$thumb = url('/api/v1/thumb/nail?w=240&h=135&img=' . '/website/lander/assets/images/preview' . $i . '.jpg');
	$sel = ($val['scheme'] == 'scheme' . $i) ? ' selected' : '';

	echo '<option data-img-src="' . $thumb . '" value="scheme' . $i . '"' . $sel . '>' . $i . '</option>';
}
echo '</select>';
echo '</div>';
echo '<style type="text/css">.color-horizontal-scroll .image_picker_selector { width:' . (($i - 1) * 268) . 'px; }</style>';
?>

<legend>{{ trans('website::admin.header_image') }}</legend>
<?php
$headers = \File::files(public_path() . '/website/lander/assets/images/headers');

echo '<div class="header-horizontal-scroll horizontal-scroll">';
echo '<select name="header" class="image-picker">';
foreach ($headers as $header)
{
	$file = (string) $header;
	$file = basename($file);
	$thumb = url('/api/v1/thumb/nail?w=240&h=135&img=/website/lander/assets/images/headers/' . $file);
	$sel = ($val['header'] == 'website/lander/assets/images/headers/' . $file) ? ' selected' : '';

	echo '<option data-img-src="' . $thumb . '" value="website/lander/assets/images/headers/' . $file . '"' . $sel . '>' . $file . '</option>';
}
echo '</select>';
echo '</div>';
echo '<style type="text/css">.header-horizontal-scroll .image_picker_selector { width:' . (count($headers) * 268) . 'px; }</style>';
?>

<?php
$field_label = trans('website::admin.custom_header_image');
$field_name = 'custom_header';
$field_value = $val['custom_header'];
?>
<div class="form-group">
	<input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $field_value }}">
	<label class="control-label col-lg-12">{{ $field_label }}</label>
	<div class="row">
		<div class="col-md-3">
			<div class="btn-group" role="group" style="margin-bottom:20px">
				<button type="button" class="btn btn-primary img-browse" data-id="{{ $field_name }}"><i class="fa fa-picture-o"></i> {{ trans('global.select_image') }}</button>
				<button type="button" class="btn btn-danger img-remove" data-id="{{ $field_name }}" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
			</div>
		</div>
		<div class="col-md-9">
			<div id="{{ $field_name }}-image" data-thumb="0" style="max-width:240px">
<?php if ($field_value != '') { echo '<img src="' . url($field_value) . '" class="thumbnail widget-thumb">'; } ?>
			</div>
		</div>
	</div>
</div>

<legend>{{ trans('website::admin.section_background') }}</legend>
<?php
$backgrounds = \File::files(public_path() . '/website/lander/assets/images/backgrounds');

echo '<div class="background-horizontal-scroll horizontal-scroll">';
echo '<select name="section" class="image-picker">';
foreach ($backgrounds as $background)
{
	$file = (string) $background;
	$file = basename($file);
	$thumb = url('/api/v1/thumb/nail?w=240&h=135&img=/website/lander/assets/images/backgrounds/' . $file);
	$sel = ($val['section'] == 'website/lander/assets/images/backgrounds/' . $file) ? ' selected' : '';

	echo '<option data-img-src="' . $thumb . '" value="website/lander/assets/images/backgrounds/' . $file . '"' . $sel . '>' . $file . '</option>';
}
echo '</select>';
echo '</div>';
echo '<style type="text/css">.background-horizontal-scroll .image_picker_selector { width:' . (count($backgrounds) * 268) . 'px; }</style>';

$field_label = trans('website::admin.custom_section_background');
$field_name = 'custom_section';
$field_value = $val['custom_section'];
?>
<div class="form-group">
	<input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $field_value }}">
	<label class="control-label col-lg-12">{{ $field_label }}</label>
	<div class="row">
		<div class="col-md-3">
			<div class="btn-group" role="group" style="margin-bottom:20px">
				<button type="button" class="btn btn-primary img-browse" data-id="{{ $field_name }}"><i class="fa fa-picture-o"></i> {{ trans('global.select_image') }}</button>
				<button type="button" class="btn btn-danger img-remove" data-id="{{ $field_name }}" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
			</div>
		</div>
		<div class="col-md-9">
			<div id="{{ $field_name }}-image" data-thumb="0" style="max-width:240px">
<?php if ($field_value != '') { echo '<img src="' . url($field_value) . '" class="thumbnail widget-thumb">'; } ?>
			</div>
		</div>
	</div>
</div>

<legend>{{ trans('website::admin.preview_phone') }}</legend>
<?php
$phones = array(
	'galaxy-s6' => 'Samsung-Galaxy-S6-Edge.png',
	'iphone6-gold' => 'iPhone-6-Gold.png',
	'iphone6-silver' => 'iPhone-6-Silver.png',
	'iphone6-space' => 'iPhone-6-Space.png'
);

echo '<div class="phone-horizontal-scroll horizontal-scroll" style="height:270px">';
echo '<select name="phone" class="image-picker">';
foreach ($phones as $class => $image)
{
	$thumb = url('/api/v1/thumb/nail?w=280&h=220&t=resize-ratio&img=/website/lander/assets/images/visuals/' . $image);
	$sel = ($val['phone'] == $class) ? ' selected' : '';

	echo '<option data-img-src="' . $thumb . '" value="' . $class . '"' . $sel . '>' . $image . '</option>';
}
echo '</select>';
echo '</div>';
echo '<style type="text/css">.phone-horizontal-scroll .image_picker_selector { width:' . (count($phones) * 150) . 'px; }</style>';
?>

<legend>{{ trans('website::admin.top_logo') }}</legend>
<div class="row">
    <div class="col-md-6">
<?php
$field_label = trans('website::admin.logo');
$field_name = 'logo';
$field_value = $val['logo'];
?>
        <div class="form-group">
            <input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $field_value }}">
            <label class="control-label col-lg-12">{{ $field_label }}</label>

            <div class="btn-group" role="group" style="margin-bottom:20px">
                <button type="button" class="btn btn-primary img-browse" data-id="{{ $field_name }}"><i class="fa fa-picture-o"></i> {{ trans('global.select_image') }}</button>
                <button type="button" class="btn btn-danger img-remove" data-id="{{ $field_name }}" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
            </div>
            <p class="help-block">{{ trans('website::admin.logo_help') }}</p>
            <br>

            <div id="{{ $field_name }}-image" data-thumb="0" style="max-width:240px">
<?php if ($field_value != '') { echo '<img src="' . url($field_value) . '" class="thumbnail widget-thumb">'; } ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
<?php
$field_label = trans('website::admin.retina_logo');
$field_name = 'logo_retina';
$field_value = $val['logo_retina'];
?>
        <div class="form-group">
            <input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $field_value }}">
            <label class="control-label col-lg-12">{{ $field_label }}</label>

			<div class="btn-group" role="group" style="margin-bottom:20px">
				<button type="button" class="btn btn-primary img-browse" data-id="{{ $field_name }}"><i class="fa fa-picture-o"></i> {{ trans('global.select_image') }}</button>
				<button type="button" class="btn btn-danger img-remove" data-id="{{ $field_name }}" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
			</div>
    		<p class="help-block">{{ trans('website::admin.retina_help') }}</p>
            <br>

			<div id="{{ $field_name }}-image" data-thumb="0" style="max-width:240px">
<?php if ($field_value != '') { echo '<img src="' . url($field_value) . '" class="thumbnail widget-thumb">'; } ?>
			</div>

	    </div>
    </div>
</div>

<legend>{{ trans('website::admin.footer_logo') }}</legend>
<div class="row">
    <div class="col-md-6">
<?php
$field_label = trans('website::admin.footer_logo');
$field_name = 'logo_footer';
$field_value = $val['logo_footer'];
?>
        <div class="form-group">
            <input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $field_value }}">
            <label class="control-label col-lg-12">{{ $field_label }}</label>

			<div class="btn-group" role="group" style="margin-bottom:20px">
				<button type="button" class="btn btn-primary img-browse" data-id="{{ $field_name }}"><i class="fa fa-picture-o"></i> {{ trans('global.select_image') }}</button>
				<button type="button" class="btn btn-danger img-remove" data-id="{{ $field_name }}" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
			</div>
            <br>

			<div id="{{ $field_name }}-image" data-thumb="0" style="max-width:240px">
<?php if ($field_value != '') { echo '<img src="' . url($field_value) . '" class="thumbnail widget-thumb">'; } ?>
			</div>
        </div>
    </div>
    <div class="col-md-6">
<?php
$field_label = trans('website::admin.retina_footer_logo');
$field_name = 'logo_footer_retina';
$field_value = $val['logo_footer_retina'];
?>
        <div class="form-group">
            <input type="hidden" name="{{ $field_name }}" id="{{ $field_name }}" value="{{ $field_value }}">
            <label class="control-label col-lg-12">{{ $field_label }}</label>

			<div class="btn-group" role="group" style="margin-bottom:20px">
				<button type="button" class="btn btn-primary img-browse" data-id="{{ $field_name }}"><i class="fa fa-picture-o"></i> {{ trans('global.select_image') }}</button>
				<button type="button" class="btn btn-danger img-remove" data-id="{{ $field_name }}" title="{{ trans('global.remove_image') }}"><i class="fa fa-remove"></i></button>
			</div>
            <br>

			<div id="{{ $field_name }}-image" data-thumb="0" style="max-width:240px">
<?php if ($field_value != '') { echo '<img src="' . url($field_value) . '" class="thumbnail widget-thumb">'; } ?>
			</div>
    		<p class="help-block">{{ trans('website::admin.retina_help') }}</p>

        </div>
    </div>
</div>

<legend>{{ trans('website::admin.contact_form') }}</legend>
<?php
if ($val['email'] == '')
{
	if ((\Auth::user()->parent_id == NULL))
	{
		$val['email'] = \Auth::user()->email;
	}
	else
	{
		$user = \User::find(\Auth::user()->parent_id);
		$val['email'] = $user->email;
	}
}

echo Former::text()
    ->name('email')
    ->forceValue($val['email'])
	->label(trans('website::admin.email_address'));


?>

<hr>
<?php
if ($_SERVER['HTTP_HOST'] != 'mobile.madewithpepper.com')
{
	echo Former::text()
		->name('google_analytics')
		->placeholder(trans('website::admin.google_analytics_placeholder'))
		->forceValue($val['google_analytics'])
		->label(trans('website::admin.google_analytics'));
?>
<hr>
<?php
}

$field_label = trans('website::admin.demo_mode');
$field_name = 'demo_mode';
$field_value = $val['demo_mode'];

echo Former::checkbox()
    ->name($field_name)
	->label($field_label)
    ->check((boolean)$field_value)
    ->dataClass('switcher-success')
    ->help(trans('website::admin.demo_mode_help'))
	->novalidate();
?>