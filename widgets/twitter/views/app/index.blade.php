<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="TwitterCtrl" ng-init="sl = '{{ $sl }}'">
<?php
if($oAuth == NULL)
{
	echo '<div class="transparent">' . trans('widget::global.no_account_connected') . '</div>';
}
else
{
	if(isset($twitter->errors))
	{
		foreach($twitter->errors as $error)
		{
			echo '<div class="transparent">' . $error->message . '</div>';
		}
	}
	else
	{
		echo '<ion-refresher on-refresh="doRefresh()"></ion-refresher>';
		echo '<div ng-show="home_timeline.length == 0" class="transparent">' . trans('widget::global.loading_tweets') . '</div>';

?>
		<div ng-repeat="entry in home_timeline track by $index" class="list card">
			<div class="item item-avatar" style="padding-bottom:0">
				<img ng-src="@{{ entry.user.profile_image_url }}" style="-webkit-border-radius:0;-moz-border-radius:0;border-radius:0;"/>
				<h2><a href="https://twitter.com/@{{ entry.user.screen_name }}" target="_blank" class="button button-clear button-dark" style="max-height:none; line-height:normal; min-height:0;">@{{ entry.user.name }}</a></h2>
				<p><a href="https://twitter.com/@{{ entry.user.screen_name }}/status/@{{ entry.id_str }}" target="_blank" class="button icon-left ion-ios-clock-outline button-clear button-small">@{{correctTimestring(entry.created_at) | date:'medium' : 'UTC'}}</a></p>
			</div>

			<div class="item item-body">
				<p style="margin-top:0" ng-bind-html="entry.text | tweet"></p>
				<img ng-if="entry.extended_entities" ng-src="@{{ entry.extended_entities.media[0].media_url }}" style="width: 100%;"/>

			</div>

		</div>
<?php
	}
}
?>
	</ion-content>
</ion-view>