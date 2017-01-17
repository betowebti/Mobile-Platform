<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="LoyaltyCardsCtrl" ng-init="sl = '{{ $sl }}'">

		<button ng-show="cards.logged_in == true" class="button button-positive button-block" ng-click="systemModal('login')"><i class="icon ion-person"></i> {{ trans('widget::global.my_account') }}</button>
		<button ng-show="cards.logged_in == false" class="button button-positive button-block" ng-click="systemModal('login')">{{ trans('widget::global.sign_in') }} <i class="icon ion-log-in"></i></button>

		<ion-refresher on-refresh="doRefresh()"></ion-refresher>

		<div ng-show="cards.items.length == 0" class="transparent">{{ trans('widget::global.loading_loyalty_cards') }}</div>
 		<div ng-show="cards.found == 0" class="transparent">{{ trans('widget::global.no_loyalty_cards') }}</div>

		<div ng-if="cards.items.length > 0">
			<div ng-repeat="entry in cards.items track by $index" class="list card">
				<div class="item item-divider">
					<strong>@{{ entry.title }}</strong>
					<p ng-bind-html="entry.brief_description" style="margin-top:0"></p>
				</div>

				<div class="item item-body">
					<a href="#{{ $hashPrefix }}/nav/widget/loyalty-cards/getCard/{{ $sl }}/@{{ entry.id }}" style="text-decoration:none">
						<span ng-show="entry.redeemed == 0" class="button button-small icon-left @{{ entry.stamp_icon }} button-positive" style="position:absolute; margin:10px">@{{ entry.stamped }}</span>
						<span ng-show="entry.redeemed == 1" class="button button-small icon-left ion-android-done button-assertive" style="position:absolute; margin:10px">{{ trans('widget::global.redeemed') }}</span>
						<span ng-show="entry.image == ''">
							<div class="card-holder item-divider">
								@{{ entry.stamps }} + 1
							</div>
						</span>
						<span ng-show="entry.image != ''">
							<img ng-src="@{{ entry.image || url + '/widgets/loyalty-cards/assets/img/card.png' }}" class="full-image"/>
						</span>
					</a>
				</div>

				<div class="item item-divider">
					<p class="text-muted" style="margin-top:0">{{ trans('widget::global.start') }}: @{{correctTimestring(entry.valid_start) | date:'shortDate' : 'UTC'}}</p>
				</div>
			</div>
		</div>

    </ion-content>
</ion-view>