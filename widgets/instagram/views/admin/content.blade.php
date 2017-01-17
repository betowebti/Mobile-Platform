<?php
/*
 |--------------------------------------------------------------------------
 | Instagram type
 |--------------------------------------------------------------------------
 */

$field_label = trans('widget::global.feed_type');
$field_name = 'type';
$field_options = array('user' => trans('widget::global.user')/*, 'popular' => trans('widget::global.popular'), 'tagged' => trans('widget::global.tag'), 'location' => trans('widget::global.location')*/);
$field_default_value = 'user';
$type = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);
$field_help = '';

echo Former::select()
    ->name($field_name)
    ->id($field_name)
    ->forceValue($type)
    ->label($field_label)
    ->placeholder(' ')
    ->options($field_options)
    ->help($field_help)
    ->class('select2-required'); // select2 / select2-required

/*
 |--------------------------------------------------------------------------
 | Instagram feed
 |--------------------------------------------------------------------------
 */

$style = '';
$style_connect = '';

if($type == 'popular')
{
    $style = ' style="display:none"';
    $style_connect = ' style="display:none"';
}
elseif($type == 'user')
{
    $style = ' style="display:none"';
}
elseif($type == 'tagged')
{
    $field_label = trans('widget::global.tag');
    $style_connect = ' style="display:none"';
}
elseif($type == 'location')
{
    $field_label = trans('widget::global.location');
    $style_connect = ' style="display:none"';
}
$field_name = 'tag';
$field_default_value = '';
$field_value = \Mobile\Controller\WidgetController::getData($page, $field_name, $field_default_value);

echo '<div id="tag_input"' . $style . '>';
echo Former::text()
    ->name($field_name)
    ->forceValue($field_value)
    ->label($field_label);
echo '</div>';

echo '<div id="connect_input"' . $style_connect . '>';
echo '<div style="margin:20px 0">';

if($oAuth == NULL)
{
    echo '<a href="'. url('/api/v1/widget/get/instagram/oAuth?sl=' . $sl) . '" class="btn btn-success btn-lg btn-block" target="_blank"><i class="fa fa-instagram"></i> ' . trans('widget::global.connect_account') . '</a>';
}
else
{
    echo '<a href="javascript:void(0);" onclick="widgetDisconnectAccount()" class="btn btn-danger btn-lg btn-block"><i class="fa fa-unlock"></i> ' . trans('widget::global.disconnect_account') . '</a>';
}

echo '</div>';
echo '</div>';

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
          url: "{{ url('/api/v1/widget/get/instagram/oAuthDisconnect') }}",
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

$('#type').on('change', function() {
    switch($(this).val())
    {
        case 'popular':
            $('#tag_input').hide();
            $('#connect_input').hide();
            break;
        case 'user':
            $('#tag_input').hide();
            $('#connect_input').show();
            break;
        case 'tagged':
            $('#tag_input .control-label').text("{{ trans('widget::global.tag') }}");
            $('#tag_input').show();
            $('#connect_input').hide();
            break;
    }
});
</script>
<?php
$limit = \Mobile\Controller\WidgetController::getData($page, 'limit', 10);

echo Former::number()
    ->name('limit')
    ->forceValue($limit)
    ->label(trans('widget::global.max_photos'));
?>