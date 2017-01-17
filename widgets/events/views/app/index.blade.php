<ion-view title="{{ $page->name }}">
  <ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="EventsCtrl" ng-init="sl = '{{ $sl }}'">

    <ion-refresher on-refresh="doRefresh()"></ion-refresher>
    <div ng-show="events.length == 0" class="transparent">{{ trans('widget::global.loading_events') }}</div>
     <div ng-show="events.found == 0" class="transparent">{{ trans('widget::global.no_events') }}</div>

    <div ng-if="events.length > 0">
      <div ng-repeat="entry in events track by $index" class="list card">
        <div class="item item-divider">
          <strong>@{{ entry.title }}</strong>
          <p><span style="float:left;width:18px;text-align:center"> <i class="ion-calendar"></i></span> @{{correctTimestring(entry.event_start) | date:'mediumDate' : 'UTC'}}<span ng-if="entry.event_end != ''"> - @{{correctTimestring(entry.event_end) | date:'mediumDate' : 'UTC'}}</span></p>
          <p><span style="float:left;width:18px;text-align:center"> <i class="ion-ios-location"></i></span> @{{ entry.location }}</p>
        </div>

        <div class="item item-body" ng-if="entry.image != ''">
          <a href="#{{ $hashPrefix }}/nav/widget/events/getEvent/{{ $sl }}/@{{ entry.id }}">
            <img ng-src="@{{ entry.image || url + '/widgets/events/assets/img/empty.gif' }}" class="full-image"/>
          </a>
        </div>

        <div class="item item-body">
          <p ng-bind-html="entry.brief_description" style="margin-top:0"></p>
        </div>

        <div class="item item-divider no-padding">
          <a class="button button-positive button-small button-clear button-block" href="#{{ $hashPrefix }}/nav/widget/events/getEvent/{{ $sl }}/@{{ entry.id }}">
            {{ trans('widget::global.more') }}
            <i class="icon icon-button ion-android-arrow-forward"></i>
          </a>
        </div>

      </div>
    </div>

  </ion-content>
</ion-view>