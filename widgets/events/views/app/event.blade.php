<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="EventCtrl" ng-init="sl = '{{ $sl }}';">
<?php
if(! $found)
{
?>
		<div class="transparent">
			<h3>{{ trans('widget::global.event_not_found') }}</h3>
		</div>
<?php
}
else
{
?>
		<div class="card" style="margin-bottom:50px">
			<div class="item item-divider">
				<strong>{{ $event->title }}</strong>
			</div>
<?php if ($event->image != '') { ?>
  			<div class="item item-body">
                <img src="{{ url($event->image); }}" class="full-image"/>
            </div>
<?php } ?>
			<div class="item item-body">
                <p style="margin-top:0"><span style="float:left;width:18px;text-align:center"> <i class="ion-calendar"></i></span> {{correctTimestring('<?php echo $event->event_start ?>') | date:'mediumDate' : 'UTC'}}<?php if ($event->event_end != '') { ?> - {{correctTimestring('<?php echo $event->event_end ?>') | date:'mediumDate' : 'UTC'}}<?php } ?></p>
                <p><span style="float:left;width:18px;text-align:center"> <i class="ion-ios-location"></i></span> {{ $event->location }}</p>
			</div>

<?php if($event->description != '') { ?>
			<div class="item item-body">
				{{ $event->description }}
			</div>
<?php } else { ?>
			<div class="item item-body">
				{{ $event->brief_description }}
			</div>
<?php } ?>
<?php
if ($social_share)
{
	echo '<div class="item item-body text-center">
	<div id="share' . $event->id . '"></div>
</div>';

	echo \Mobile\Core\Social::shareButtons($app, $page, 'share' . $event->id, $page->name . ' - ' . $event->title . ' @ ' . $event->location);
}
?>
			 <div class="item item-divider no-padding">
                <a class="button button-positive button-small button-clear button-block" href="{{ $back }}">
				  <i class="icon icon-button ion-android-arrow-back"></i>
                    {{ trans('widget::global.back') }}
                </a>
			 </div>
		</div>
<?php
}
?>
    </ion-content>
</ion-view>