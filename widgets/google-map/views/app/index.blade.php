<ion-view title="{{ $page->name }}">
	<ion-content padding="false" class="{{ $app->content_classes }}" ng-controller="MapCtrl">

		<div id="map" data-tap-disabled="true"></div>

		<ion-footer-bar class="bar-dark" ng-init="initMap()">
			<a ng-click="centerOnMe()" class="button button-icon icon ion-navigate"></a>
		</ion-footer-bar>

	</ion-content>
</ion-view>
