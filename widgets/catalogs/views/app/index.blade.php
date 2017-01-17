<ion-view title="{{ $page->name }}">
	<ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="CatalogsCtrl" ng-init="sl = '{{ $sl }}'">
		<ion-refresher on-refresh="doRefresh()"></ion-refresher>
		<div ng-show="catalogs.items.length == 0" class="transparent">{{ trans('widget::global.loading_catalogs') }}</div>
		<div ng-show="catalogs.found == 0" class="transparent">{{ trans('widget::global.no_catalogs') }}</div>
		<div ng-if="catalogs.items.length > 0">
			<div ng-repeat="item in catalogs.items track by $index" class="list card">
				<div class="item item-divider">
					<strong>@{{ item.title }}</strong>
				</div>
				<div class="item" ng-show="item.image_thumb">
					<img ng-src="@{{ item.image_thumb }}" class="full-image">
				</div>
				<div class="item item-text-wrap item-divider">
					<p ng-bind-html="item.description"></p>
				</div>
				<div class="list" ng-repeat="category_item in item.structure_categories track by $index">
					<div class="item item-text-wrap" ng-class="{'item-thumbnail-left': category_item.image != ''}">
						<img src="@{{ category_item.image }}" ng-show="category_item.image">
						<h2>@{{ category_item.title }}</h2>
						<p ng-bind-html="category_item.description"></p>
					</div>
					<div class="list">
						<div ng-repeat="structure_item in category_item.structure_items track by $index" class="item item-text-wrap" ng-class="{'item-avatar': structure_item.image_thumb != ''}">
							<img src="@{{ structure_item.image_thumb }}" ng-show="structure_item.image_thumb">
							<h2>@{{ structure_item.title }}</h2>
							<p ng-bind-html="structure_item.description"></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
	</ion-content>
</ion-view>
