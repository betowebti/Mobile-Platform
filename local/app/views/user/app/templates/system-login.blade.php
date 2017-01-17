<ion-modal-view cache-view="false">
<?php if ($logged_in) { ?>
	<ion-header-bar>
		<a href="javascript:void(0);" class="button button-clear pull-right button-dark" ng-click="closeSysModal()"><i class="icon ion-close close-modal"></i></a>
		<h1 class="title">{{ trans('global.my_account') }}</h1>
	</ion-header-bar>
	<ion-content>

		<div class="padding item item-light text-center">
			{{ \App\Controller\AccountController::publicUser($app_id)->email }}
		</div>

		<div class="padding">
			<button type="submit" ng-click="systemLogout()" class="button button-block button-stable icon-right ion-log-out">{{ trans('global.logout') }}</button>
		</div>

	</ion-content>
<?php } else { ?>
	<ion-header-bar>
		<a href="javascript:void(0);" class="button button-clear pull-right button-dark" ng-click="closeSysModal()"><i class="icon ion-close close-modal"></i></a>
		<h1 class="title">{{ trans('global.sign_in_account') }}</h1>
	</ion-header-bar>
	<ion-content>

		<form name="systemLoginForm">
			<div class="list padding" style="padding:0 10px; margin-bottom:10px">
			  <label class="item item-input">
				<span class="input-label">{{ trans('global.email') }}</span>
				<input type="text" ng-model="user.mail" required>
			  </label>
			  <label class="item item-input">
				<span class="input-label">{{ trans('global.password') }}</span>
				<input type="password" ng-model="user.pass" required>
			  </label>
			</div>
			<div class="padding">
				<button type="submit" ng-click="systemLogin(user)" class="button button-block button-stable icon-right ion-log-in">{{ trans('global.sign_in') }}</button>
			</div>
			<div class="padding item item-light text-center">
				<button class="button button-clear button-dark" ng-click="systemModal('register')">{{ trans('global.create_an_account') }}</button>
				<span style="font-size:1.5em; position:relative;top:11px">|</span>
				<button class="button button-clear button-dark" ng-click="systemModal('reset')">{{ trans('global.forgot_password') }}</button>
			</div>
		</form>
<?php } ?>
</ion-modal-view>