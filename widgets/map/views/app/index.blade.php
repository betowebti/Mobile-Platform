<ion-view title="{{ $page->name }}">

	<ion-nav-buttons side="{{ ($app->layout == 'side-right') ? 'primary': 'secondary'; }}">
        <button ng-click="getRoute()" class="button icon ion-navigate">{{ trans('widget::global.get_route') }}</button>
    </ion-nav-buttons>

	<ion-content padding="false" class="{{ $app->content_classes }}" ng-controller="MapCtrl" ng-init="sl = '{{ $sl }}';id = '{{ $page->id }}'" data-tap-disabled="true">

		<leaflet id="map{{ $page->id }}" markers="markers" defaults="defaults"></leaflet>

	</ion-content>
</ion-view>
