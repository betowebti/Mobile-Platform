<ion-view title="{{ $page->name }}">
    <ion-content padding="true" class="{{ $app->content_classes }}" ng-controller="CouponCtrl" ng-init="sl = '{{ $sl }}'; code = '{{ $coupon->code }}';">
<?php
if(! $found)
{
?>
		<div class="transparent">
			<h3>{{ trans('widget::global.coupon_not_found') }}</h3>
		</div>
<?php
}
else
{
?>
		<button ng-show="coupon.logged_in == true" class="button button-positive button-block" ng-click="systemModal('login')"><i class="icon ion-person"></i> {{ trans('widget::global.my_account') }}</button>
		<button ng-show="coupon.logged_in == false" class="button button-positive button-block" ng-click="systemModal('login')">{{ trans('widget::global.sign_in_to_use_feature') }} <i class="icon ion-log-in"></i></button>

		<div class="card" style="margin-bottom:50px">
			<div class="item item-divider">
				<strong>{{ $coupon->title }}</strong>
			</div>

  			<div class="item item-body">

					<img src="{{ ($coupon->image == '') ? url('/widgets/coupons/assets/img/coupon.png') : url($coupon->image); }}" class="full-image"/>

<?php if($coupon->type == 1) { ?>
					<div class="row">
					  <div class="col text-center">
						  <div><strong>{{ $coupon->currency_symbol }}{{ \Widget\Controller\AppController::formatCurrency($coupon->original_price) }}</strong></div>
						  {{ trans('widget::global.original_price') }}
					  </div>
					  <div class="col text-center">
						  <div><strong>{{ $coupon->currency_symbol }}{{ \Widget\Controller\AppController::formatCurrency($coupon->original_price - $coupon->new_price) }}</strong></div>
						  {{ trans('widget::global.discount') }}
					  </div>
					  <div class="col text-center">
<?php if($coupon->discount_type == 1) { ?>
						  <div><strong>{{ $coupon->currency_symbol }}{{ \Widget\Controller\AppController::formatCurrency($coupon->new_price) }}</strong></div>
						  {{ trans('widget::global.new_price') }}
<?php } ?>
<?php if($coupon->discount_type == 2) { ?>
						  <div><strong>{{ $coupon->discount }}%</strong></div>
						  {{ trans('widget::global.savings') }}
<?php } ?>
					  </div>
					</div>
<?php } ?>

<?php if($coupon->type == 2) { ?>
					<div class="row">
					  <div class="col text-center">
						  {{ trans('widget::global.buy') }}
						  <div><strong>{{ $coupon->buy }}</strong></div>
					  </div>
					  <div class="col text-center">
						  {{ trans('widget::global.get') }}
						  <div><strong>{{ $coupon->get }}</strong></div>
					  </div>
					  <div class="col text-center">
						  <div><strong>{{ $coupon->get - $coupon->buy }}</strong></div>
						  {{ trans('widget::global.free') }}
					  </div>
					</div>
<?php } ?>

					<button class="button button-large button-block button-balanced" id="redeem" ng-show="coupon.logged_in == true && coupon.redeemed == false" ng-btn-ok="{{ trans('widget::global.redeem') }}" ng-btn-cancel="{{ trans('widget::global.cancel') }}" ng-really-title="{{ trans('widget::global.redeem_coupon') }}" ng-really-message="{{ trans('widget::global.confirm_redeem') }}" ng-really-click="redeemCoupon('{{ $coupon->code }}')">
					{{ trans('widget::global.redeem') }}
					</button>

					<span class="button button-large button-block icon-left ion-android-done button-assertive" ng-show="coupon.logged_in == true && coupon.redeemed == true" style="cursor:default;">{{ trans('widget::global.redeemed') }}</span>

			</div>

<?php if($coupon->details != '') { ?>
			<div class="item item-body">
				{{ $coupon->details }}
			</div>
<?php } else { ?>
			<div class="item item-body">
				{{ $coupon->brief_description }}
			</div>
<?php } ?>

<?php if($coupon->conditions != '') { ?>
			<div class="item item-icon-left icon ion-android-alert item-body">
				{{ $coupon->conditions }}
			</div>
<?php } ?>
<?php
if ($social_share)
{
	echo '<div class="item item-body text-center">
	<div id="share' . $coupon->code . '"></div>
</div>';

	echo \Mobile\Core\Social::shareButtons($app, $page, 'share' . $coupon->code, $page->name . ' - ' . $coupon->title);
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