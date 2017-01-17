<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}">
<?php
if($client_id == '')
{
    echo '<div class="transparent">' . trans('widget::global.no_client_id') . '</div>';
}
else
{
    if ($type == 'user' && $oAuth == NULL)
    {
        echo '<div class="transparent">' . trans('widget::global.no_account_connected') . '</div>';
    }
    else
    {
?>
        <div id="instagram{{ $page->id }}"></div>

        <script type="text/javascript">
        var feed = new Instafeed({
            target: 'instagram{{ $page->id }}',
            get: '{{ $type }}',
            tagName: '{{ $tag }}',
            sortBy: 'most-recent',
            limit: {{ $limit }},
            resolution: 'standard_resolution',
            clientId: '{{ $client_id }}',
<?php
    if ($type == 'user' || $type == 'tagged')
    {
            $oAuth = json_decode($oAuth);
?>
            userId: {{ $oAuth->uid }},
            accessToken: '{{ $oAuth->oauth_token->access_token }}',
<?php
    }
?>
            template: '<div class="card">\
<div class="item">\
<a href="@{{link}}" target="_system">\
<img class="full-image" src="@{{image}}" />\
</a>\
</div>\
<div class="item item-body">\
@{{caption}}\
</div>\
<div class="item item-divider">\
    <p>\
      <a href="@{{link}}" target="_system" class="subdued">@{{likes}} {{ trans('widget::global.likes') }}</a>\
      <a href="@{{link}}" target="_system" class="subdued">@{{comments}} {{ trans('widget::global.comments') }}</a>\
    </p>\
</div>\
</div>'
        });
        feed.run();
        </script>
<?php
    }
}
?>
    </ion-content>
</ion-view>