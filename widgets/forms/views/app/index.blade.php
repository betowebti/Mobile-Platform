<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}">
<?php
$title = \Mobile\Controller\WidgetController::getData($page, 'title', trans('widget::global.title_default'));
$content = \Mobile\Controller\WidgetController::getData($page, 'content', trans('widget::global.content_default'));
$content = str_replace('<p><br></p>', '', $content);

if ($content != '' || $title != '')
{
?>
        <div class="transparent">
<?php
    if($title != '')
    {
        echo '<h3>' . $title . '</h3>';
    }

    if ($content != '') echo $content;
?>
        </div>
<?php
}
?>
        <form style="margin-top:10px" method="post" action="{{ url('api/v1/widget/post/forms/postForm' ) }}" id="frm{{ $page->id }}" novalidate>
        <input type="hidden" name="sl" value="{{ $sl }}">
<?php
$form = \Mobile\Controller\WidgetController::getData($page, 'form', trans('widget::global.form_default'));
$form = json_decode($form);

$element_count = 0;
$i = 0;
$js = '';
if($form != NULL)
{
    echo '<div class="list">';

    foreach($form->name as $row)
    {
        $name = $form->name[$i];
        $type = $form->type[$i];
        $options = (isset($form->options[$i])) ? $form->options[$i] : [];
        $required = ((boolean)$form->required[$i]) ? '1' : '0';

        if($name != '')
        {
            $element_count++;

            // Hidden input with label name
            echo '<textarea name="el-' . $i . '[]" style="display:none">' . $name . '</textarea>';
            echo '<input type="hidden" name="el-required[' . $i . ']" value="' . $required . '">';

            switch($type)
            {
                case 'text':
                case 'email':
                case 'number':
                case 'tel':
                case 'date':
                case 'time':
                case 'datetime':

                    if ($type == 'datetime') $type = 'datetime-local';

                    echo '<label class="item item-input">';
                    echo '<span class="input-label">' . $name . '</span>';
                    echo '<input type="' . $type . '" name="el-' . $i . '[]">';
                    echo '</label>';

                break;
            }

            if($type == 'textarea')
            {
                echo '<label class="item item-input">';
                echo '<span class="input-label">'. $name . '</span>';
                echo '<textarea rows="4" name="el-' . $i . '[]"></textarea>';
                echo '</label>';
            }

            if($type == 'checkbox')
            {
                echo '<div class="item item-checkbox">';
                echo '<label class="checkbox">';
                echo '<input type="checkbox" name="el-' . $i . '[]" value="Yes">';
                echo '</label>' . $name;
                echo '</div>';
            }

            if($type == 'dropdown')
            {
                $options = explode("\n", $options);

                echo '<label class="item item-input item-select">';
                echo '<div class="input-label">';
                echo $name;
                echo '</div>';

                echo '<select name="el-' . $i . '[]">';

                foreach($options as $option)
                {
                    $selected = (strpos($option, '[x]') !== false) ? ' selected' : '';
                    $text = str_replace('[x]', '', $option);
                    $text = trim($text);

                    if($text != '')
                    {
                        echo '<option' . $selected . '>' . $text . '</option>';
                    }
                }

                echo '</select>';
                echo '</label>';
            }

            if($type == 'options')
            {
                $options = explode("\n", $options);

                echo '<div class="list" style="margin-top:20px">';
                echo '<label class="item item-input">';
                echo '<span class="input-label"><strong>' . $name . '</strong></span>';
                echo '</label>';

                foreach($options as $option)
                {
                    $selected = (strpos($option, '[x]') !== false) ? ' checked' : '';
                    $text = str_replace('[x]', '', $option);
                    $text = trim($text);

                    if($text != '')
                    {
                        echo '<label class="item item-radio">';
                        echo '<input type="radio"' . $selected . ' name="el-' . $i . '[]" value="' . str_replace('"', '&quot;', $text) . '">';
                        echo '<div class="radio-content">';
                        echo '<div class="item-content">';
                        echo $text;
                        echo '</div>';
                        echo '<i class="radio-icon ion-checkmark"></i>';
                        echo '</div>';
                        echo '</label>';
                    }
                }

                echo '</div>';
            }

            if($type == 'multiplechoice')
            {
                $options = explode("\n", $options);

                echo '<div class="list" style="margin-top:20px">';
                echo '<label class="item item-input">';
                echo '<span class="input-label"><strong>' . $name . '</strong></span>';
                echo '</label>';

                foreach($options as $option)
                {
                    $selected = (strpos($option, '[x]') !== false) ? ' checked' : '';
                    $text = str_replace('[x]', '', $option);
                    $text = trim($text);

                    if($text != '')
                    {
                        echo '<div class="item item-checkbox">';
                        echo '<label class="checkbox">';
                        echo '<input type="checkbox"' . $selected . ' name="el-' . $i . '[opts][]" value="' . str_replace('"', '&quot;', $text) . '">';
                        echo '</label>';
                        echo $text;
                        echo '</div>';
                    }
                }

                echo '</div>';
            }
        }
        $i++;
    }

    echo '</div>';
}

if($element_count > 0)
{
    $submit = \Mobile\Controller\WidgetController::getData($page, 'submission_button', trans('widget::global.submit'));
?>
        <button class="button button-block button-positive" type="submit">{{ $submit }}</button>
<?php
}
?>
        </form>

    </ion-content>
</ion-view>
<script>
$('#frm{{ $page->id }}').on('submit', function() {

    angular.element($('ion-view')).injector().get("$ionicLoading").show();

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        dataType: 'json',
        success: function(data)
        {
            $('#frm{{ $page->id }}')[0].reset();
            angular.element($('ion-view')).injector().get("$ionicPopup").alert({
                title: "{{ $page->name }}",
                template: data.msg,
                okText: "{{ trans('widget::global.ok') }}"
            });
        }
    }).always(function() {
        angular.element($('ion-view')).injector().get("$ionicLoading").hide();
    });;

    return false;
});
</script>