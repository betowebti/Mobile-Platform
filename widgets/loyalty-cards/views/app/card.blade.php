<ion-view title="{{ $card->title }}">
    <ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="LoyaltyCardCtrl" ng-init="sl = '{{ $sl }}';stamp_content='{{ trans('widget::global.stamp_content') }}';redeem_freebie='{{ trans('widget::global.redeem_freebie') }}';redeem='{{ trans('widget::global.redeem') }}';stamp='{{ trans('widget::global.stamp') }}';stamp_card='{{ trans('widget::global.stamp_card') }}';cancel='{{ trans('widget::global.cancel') }}';redeem_content='{{ str_replace("'", "\'", str_replace('"', '&quot;', trans('widget::global.earned_freebie', ['freebie' => $card->freebie ]))) }}';redeemed_content='{{ str_replace("'", "\'", str_replace('"', '&quot;', trans('widget::global.redeemed_freebie', ['freebie' => $card->freebie ]))) }}';">
<?php
if(! $found)
{
?>
		<div class="transparent">
			<h3>{{ trans('widget::global.card_not_found') }}</h3>
		</div>
<?php
}
else
{
?>
		<button ng-show="card.logged_in == true" class="button button-positive button-block" ng-click="systemModal('login')"><i class="icon ion-person"></i> {{ trans('widget::global.my_account') }}</button>
		<button ng-show="card.logged_in == false" class="button button-positive button-block" ng-click="systemModal('login')">{{ trans('widget::global.sign_in_to_use_feature') }} <i class="icon ion-log-in"></i></button>
<?php
$col_cnt = 1;
$stamps = $card->stamps + 1;
$columns = 3;

echo '<section class="card-container">';

for($i = 0; $i < $stamps; $i++)
{
	if (($i % $columns  == 0 || $i == 0) && $columns > 1)
	{
		echo '<div class="row icon-row">';
	}

	echo '<div class="col text-center">';

	if ($i == $stamps - 1)
	{
		echo '<a href="javascript:void(0);" class="stamp" ng-click="stampCard(card.logged_in, card.stamps, ' . $card->stamps . ', card.redeemed)"><i class="icon ' . $card->freebie_icon . '"></i></a>';
	}
	else
	{
		echo '<span class="stamp" ng-show="card.stamps > ' . $i . '"><i class="icon ' . $card->stamp_icon . '"></i></span>';
		echo '<a href="javascript:void(0);" class="stamp" ng-click="stampCard(card.logged_in, card.stamps, ' . $card->stamps . ', card.redeemed)" ng-show="card.logged_in == false || (card.logged_in == true && card.stamps <= ' . $i . ')"><span>' . ($i + 1) . '</span></a>';
	}

	echo '</div>';

	if ($col_cnt == $columns && $columns > 1)
	{
		echo '</div>';
		$col_cnt = 1;
	}
	else
	{
		$col_cnt++;
	}

}

if ($col_cnt != 1)
{
	for ($i=0; $i<($columns - $col_cnt) + 1; $i++)
	{
		echo '<div class="col text-center">&nbsp;</div>';
	}
	echo '</div>';
}

echo '</section>';

?>

		<div class="card" style="margin-bottom:65px">
<?php if($card->description != '') { ?>
			<div class="item item-body">
				{{ $card->description }}
			</div>
<?php } else { ?>
			<div class="item item-body">
				{{ $card->brief_description }}
			</div>
<?php } ?>
			<div class="item item-divider">
                <p style="margin-top:0">{{ trans('widget::global.valid') }}: {{correctTimestring('<?php echo $card->valid_start ?>') | date:'mediumDate' : 'UTC'}}<?php if ($card->valid_end != '') { ?> - {{correctTimestring('<?php echo $card->valid_end ?>') | date:'mediumDate' : 'UTC'}}<?php } ?></p>
			</div>
<?php
if ($social_share)
{
	echo '<div class="item item-body text-center">
	<div id="share' . $card->id . '"></div>
</div>';

	echo \Mobile\Core\Social::shareButtons($app, $page, 'share' . $card->id, $page->name . ' - ' . $card->title . ' @ ');
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