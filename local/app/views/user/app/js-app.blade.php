<!-- skipmin --><script>

var sl_app = '{{ \App\Core\Secure::array2string(array('app_id' => $app->id, 'language' => $app->language)); }}';
var locale = '{{ \App::getLocale() }}';
var url = '{{ url() }}';

var app_dependencies = ['ionic', 'ngResource', 'ngApp.controllers', 'ngApp.services'];

var ngApp = angular.module('ngApp', app_dependencies)

.run(function($ionicPlatform, $rootScope, $ionicLoading, $ionicModal, $ionicPopup, $templateCache, $http, $state) {
    $rootScope.$on('loading:show', function() {
        $ionicLoading.show({
            noBackdrop: false
        });
    });

    $rootScope.$on('loading:hide', function() {
        $ionicLoading.hide();
    });

	$ionicPlatform.ready(function() {
		/* Ready */
	});

	/* Global login & registration */
	$rootScope.systemModal = function (tpl) {

		$ionicModal.fromTemplateUrl(url + '/system.html?tpl=' + tpl + '&sl=' + sl_app, {
			id: tpl,
			scope: $rootScope,
      		backdropClickToClose: false,
			animation: 'slide-in-up'
		}).then(function(modal) {
			$rootScope.closeSysModal();

			$rootScope.sysModal = modal;
			$rootScope.sysModal.show();
		});
	};

	$rootScope.closeSysModal = function() {
		if (typeof $rootScope.sysModal !== 'undefined')  $rootScope.sysModal.hide();
	};

	$rootScope.$on('modal.hidden', function(event, modal) {
		if (modal.id == 'login') $templateCache.remove(url + '/system.html?tpl=login&sl=' + sl_app);
		if (modal.id == 'register') $templateCache.remove(url + '/system.html?tpl=register&sl=' + sl_app);
		if (modal.id == 'reset') $templateCache.remove(url + '/system.html?tpl=reset&sl=' + sl_app);
	});

	/* Registration */
	$rootScope.systemRegister = function (user) {
		$ionicLoading.show();
		var mail = (typeof user === 'undefined' || typeof user.mail === 'undefined') ? '' : user.mail;
		var pass = (typeof user === 'undefined' || typeof user.pass === 'undefined') ? '' : user.pass;

		$http({
			url: url + '/api/v1/account/public-user-register', 
			method: "POST",
			data: {mail: mail, pass: pass, sl: sl_app}
		}).success(function(response){

			$ionicPopup.alert({
				title: response.title,
				content: response.content
			}).then(function(res) {
				if (response.result == 'success')
				{
					$rootScope.sysModal.hide();
					$rootScope.sysAccountCallback();
				}
			});
			
		}).error(function(){
			alert(response.responseText);
		}).finally(function(){
			$ionicLoading.hide();
		});
	};

	/* Login */
	$rootScope.systemLogin = function (user) {
		$ionicLoading.show();
		var mail = (typeof user === 'undefined' || typeof user.mail === 'undefined') ? '' : user.mail;
		var pass = (typeof user === 'undefined' || typeof user.pass === 'undefined') ? '' : user.pass;

		$http({
			url: url + '/api/v1/account/public-user-login', 
			method: "POST",
			data: {mail: mail, pass: pass, sl: sl_app}
		}).success(function(response){

			if (typeof response.title !== 'undefined')
			{
				$ionicPopup.alert({
					title: response.title,
					content: response.content
				}).then(function(res) {
					if (response.result == 'success')
					{
						$rootScope.sysModal.hide();
					}
				});
			}
			else
			{
				$rootScope.sysModal.hide();
				$rootScope.sysAccountCallback();
			}
			
		}).error(function(){
			alert(response.responseText);
		}).finally(function(){
			$ionicLoading.hide();
		});
	};

	/* Reset */
	$rootScope.systemReset = function (user) {
		$ionicLoading.show();
		var mail = (typeof user === 'undefined' || typeof user.mail === 'undefined') ? '' : user.mail;

		$http({
			url: url + '/api/v1/account/public-user-reset', 
			method: "POST",
			data: {mail: mail, sl: sl_app}
		}).success(function(response){

			$ionicPopup.alert({
				title: response.title,
				content: response.content
			}).then(function(res) {
				if (response.result == 'success')
				{
					$rootScope.sysModal.hide();
				}
			});
			
		}).error(function(){
			alert(response.responseText);
		}).finally(function(){
			$ionicLoading.hide();
		});
	};

	/* Logout */
	$rootScope.systemLogout = function () {
		$ionicLoading.show();

		$http({
			url: url + '/api/v1/account/public-user-logout', 
			method: "POST",
			data: {sl: sl_app}
		}).success(function(response){
			$rootScope.sysModal.hide();
			$rootScope.sysAccountCallback();
		}).error(function(){
			alert(response.responseText);
		}).finally(function(){
			$ionicLoading.hide();
		});
	};

	/* General callback */
	$rootScope.sysAccountCallback = function () {
		$state.go($state.$current, {}, { reload: true });
	};

})

.config(function($stateProvider, $locationProvider, $urlRouterProvider, $ionicConfigProvider, $httpProvider) {

<?php
if (\Config::get('system.seo', true))
{
?>
	$locationProvider.hashPrefix('!');
		/*.html5Mode(true)*/
<?php
}
?>

	$ionicConfigProvider.backButton.previousTitleText(false).text('');
	$ionicConfigProvider.views.transition('none');

    $httpProvider.interceptors.push(function($rootScope) {
        return {
            /* http request show loading */
            request: function(config) {
               $rootScope.$broadcast('loading:show');
               return config
            },
            /* Hide loading in case any occurred */
            requestError: function(response) {
               $rootScope.$broadcast('loading:hide');
               return response
            },
            /* Hide loading once got response */
            response: function(response) {
               $rootScope.$broadcast('loading:hide');
               return response
            },
            /* Hide loading if got any response error  */
            responseError: function(response) {
              $rootScope.$broadcast('loading:hide');
              return response
            }
        }
    });

<?php
if($app->layout == 'tabs-bottom')
{
	echo '	$ionicConfigProvider.tabs.position("bottom");';
}
elseif($app->layout == 'tabs-top')
{
	echo '	$ionicConfigProvider.tabs.position("top");';
}
?>

	$stateProvider

	.state('nav', {
		url: '/nav',
		abstract: true,
		templateUrl: '<?php echo url('/api/v1/mobile/view/' . $app->local_domain) ?>',
      	controller: 'NavCtrl'
	})

	.state('nav.widget1', {
	  url: '/widget/:widget/:func/:sl/:id',
	  cache: false,
	  views: {
		'mainContent': {
		  templateUrl: function(params) { return '<?php echo url('/api/v1/widget/route') ?>/' + params.widget + '/' + params.func + '/' + params.sl + '/' + params.id; }
		}
	  }
	})

	.state('nav.widget2', {
	  url: '/widget/:widget/:func/:sl/:id/:extra',
	  cache: false,
	  views: {
		'mainContent': {
		  templateUrl: function(params) { return '<?php echo url('/api/v1/widget/route') ?>/' + params.widget + '/' + params.func + '/' + params.sl + '/' + params.id + '/' + params.extra; }
		}
	  }
	})

<?php
$first_slug = '';

foreach($app->appPages as $page)
{
	if($first_slug == '') $first_slug = $page->slug;
	$class = camel_case('c-' . $page->slug);
?>
	.state('nav.<?php echo $page->slug ?>', {
		  url: '/<?php echo $page->slug ?>',
		  class: '<?php echo $class ?>',
		  cache: false,
		  views: {
			'mainContent': {
			  templateUrl: '<?php echo url('/api/v1/mobile/view/' . $app->local_domain . '?_escaped_fragment_=' . $page->slug) ?>',
			  controller: '<?php echo $class ?>Ctrl'
			}
		  }
		})
<?php
}
?>;

	$urlRouterProvider.otherwise('/nav/<?php echo $first_slug ?>');

});

</script>{{--skipmin--}}