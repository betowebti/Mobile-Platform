<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}">
<?php
if ($access_token == '')
{
	echo '<div class="transparent">' . trans('widget::global.no_access_token') . '</div>';
}
elseif ($facebook_id == '')
{
	echo '<div class="transparent">' . trans('widget::global.enter_facebook_id') . '</div>';
} 
else
{
?>
		<div id="facebookWall{{ $page->id }}" class="facebookWall"></div>

<script>
$('#facebookWall{{ $page->id }}').facebookWall({
	id: '{{ $facebook_id }}',
	access_token: '{{ $access_token }}',
	limit: {{ $limit }}
});
</script>

<script id="headingTpl" type="text/x-jquery-tmpl">
<?php if ((boolean) $show_title) { ?>
<div class="transparent text-center">
	<h4 style="margin:0">${name}</h4>
</div>
<?php } ?>
</script>

<script id="feedTpl" type="text/x-jquery-tmpl">
<div class="list card">

  <div class="item item-avatar avatar-square">
    <img src="${from.picture}">
    <h2><a href="http://www.facebook.com/profile.php?id=${from.id}" target="_blank">${from.name}</a></h2>
    <p>${created_time}</p>
  </div>

  <div class="item item-body">
	@{{if picture}}
		<img class="small-image" src="${picture}">
	@{{/if}}

    @{{html message}}

    <p>
      <a href="http://www.facebook.com/profile.php?id=${from.id}" target="_blank" class="subdued">
		@{{if likes}}
			${likes_count} @{{if likes_count == 1}}{{ trans('widget::global.like') }}@{{else}}{{ trans('widget::global.likes') }}@{{/if}}
		@{{else}}
			0 {{ trans('widget::global.likes') }}
		@{{/if}}
	  </a>
      <a href="http://www.facebook.com/profile.php?id=${from.id}" target="_blank" class="subdued">
		@{{if comments}}
			${comment_count} @{{if comment_count == 1}}{{ trans('widget::global.comment') }}@{{else}}{{ trans('widget::global.comments') }}@{{/if}}
		@{{else}}
			0 {{ trans('widget::global.comments') }}
		@{{/if}}
	  </a>
    </p>
  </div>
</div>
</script>
<?php
}
?>
	</ion-content>
</ion-view>