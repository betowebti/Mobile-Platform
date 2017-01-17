<ion-view title="{{ $page->name }}">
	<ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="FlickrCtrl" ng-init="{{ $ngInit }}">
		<div id="photos" class="clearfix">
			<div ng-repeat="photo in photos.items">
				<div ng-if="$index % 3 === 0" 
					   ng-init="group = photos.items.slice($index, $index + 3)">
					<div class="row">
						<div class="col col-33" ng-repeat="grItem in group"> <a ng-href="@{{ grItem.link }}" target="_blank"><img ng-src="@{{ grItem.media.m }}" class="photo"></a> </div>
					</div>
				</div>
			</div>
		</div>
	</ion-content>
</ion-view>