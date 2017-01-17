<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="PhotosCtrl" ng-init="sl = '{{ $sl }}'">

		<div ng-show="items.items.length == 0" class="transparent">{{ trans('widget::global.loading_photos') }}</div>
 		<div ng-show="items.found == 0" class="transparent">{{ trans('widget::global.no_photos') }}</div>

		<ion-gallery ion-gallery-items="photos" ion-gallery-row="3"></ion-gallery>
	</ion-content>
</ion-view>