<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="RssCtrl" ng-init="sl = '{{ $sl }}'">
<?php
if($error !== false)
{
	echo '<div class="transparent">' . $error . '</div>';
}
else
{
?>
		<ion-refresher on-refresh="doRefresh()"></ion-refresher>
		<div ng-show="feed.length == 0" class="transparent">{{ trans('widget::global.loading_feed') }}</div>
		<div ng-show="feed.success == false" class="transparent">@{{ feed.error }}</div>

		<div ng-if="feed.success == true">

			<div class="card" style="margin-top:5px">
				<div class="item">
					<h2>@{{ feed.title }}</h2>
					<p>@{{ feed.desc }}</p>
				</div>
			</div>

			<div ng-repeat="entry in feed.items track by $index" class="list card">
				<div class="item item-divider">
					<h2><a href="@{{ entry.permalink }}">@{{ entry.title }}</a></h2>
					<p>@{{correctTimestring(entry.date) | date:'medium' : 'UTC'}}</p>
				</div>
	
				<div class="item item-body">
					<a href="@{{ entry.permalink }}" ng-if="entry.image"><img ng-src="@{{ entry.image }}" class="full-image"/></a>
					<p ng-style="entry.image == '' ? { 'margin-top' : '0' } : { 'margin-top' : '5px' }">@{{ entry.content }}</p>
				</div>
			</div>

		</div>
<?php
}
?>
	</ion-content>
</ion-view>