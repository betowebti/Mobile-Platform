'use strict';

var CmsApp = angular.module('cmsApp', [
	'ngRoute',
	'ui.bootstrap',
    'monospaced.qrcode'
]);

CmsApp.config(function ($routeProvider) {
        $routeProvider.
        when('/', {
            templateUrl: 'app/dashboard',
			active: 'dashboard'
        }).
        when('/dashboard/:start/:end', {
            templateUrl: function(params) { return 'app/dashboard?start=' + params.start + '&end=' + params.end; },
			active: 'dashboard'
        }).
        when('/dashboard/:sl', {
            templateUrl: function(params) { return 'app/dashboard?sl=' + params.sl; },
			active: 'dashboard'
        }).
        when('/dashboard/:start/:end/:sl', {
            templateUrl: function(params) { return 'app/dashboard?start=' + params.start + '&end=' + params.end + '&sl=' + params.sl; },
			active: 'dashboard'
        }).
        when('/apps', {
            templateUrl: 'app/mobile',
			active: 'apps'
        }).
        when('/app/new', {
            templateUrl: 'app/app',
			active: 'apps'
        }).
        when('/app/edit/:sl', {
            templateUrl: function(params) { return 'app/app?sl=' + params.sl; },
			active: 'apps'
        }).
        when('/app/analytics', {
            templateUrl: 'app/app/analytics',
			active: 'app-analytics'
        }).
        when('/app/analytics/:start/:end', {
            templateUrl: function(params) { return 'app/app/analytics?start=' + params.start + '&end=' + params.end; },
			active: 'app-analytics'
        }).
        when('/app/analytics/:sl', {
            templateUrl: function(params) { return 'app/app/analytics?sl=' + params.sl; },
			active: 'app-analytics'
        }).
        when('/app/analytics/:start/:end/:sl', {
            templateUrl: function(params) { return 'app/app/analytics?start=' + params.start + '&end=' + params.end + '&sl=' + params.sl; },
			active: 'app-analytics'
        }).
        when('/app/widget-data', {
            templateUrl: 'app/app/widget-data',
			active: 'app-widget-data'
        }).
        when('/app/widget-data/:start/:end', {
            templateUrl: function(params) { return 'app/app/widget-data?start=' + params.start + '&end=' + params.end; },
			active: 'app-widget-data'
        }).
        when('/app/widget-data/:sl', {
            templateUrl: function(params) { return 'app/app/widget-data?sl=' + params.sl; },
			active: 'app-widget-data'
        }).
        when('/app/widget-data/:start/:end/:sl', {
            templateUrl: function(params) { return 'app/app/widget-data?start=' + params.start + '&end=' + params.end + '&sl=' + params.sl; },
			active: 'app-widget-data'
        }).
        when('/app/public-users', {
            templateUrl: 'app/app/public-users',
			active: 'app-public-users'
        }).
        when('/web', {
            templateUrl: '/app/web',
			active: 'web'
        }).
        when('/campaigns', {
            templateUrl: 'app/campaigns',
			active: 'campaigns'
        }).
        when('/campaign', {
            templateUrl: 'app/campaign',
			active: 'campaigns'
        }).
        when('/campaign/:sl', {
            templateUrl: function(params) { return 'app/campaign?sl=' + params.sl; },
			active: 'campaigns'
        }).
        when('/profile', {
            templateUrl: 'app/profile',
			active: 'profile'
        }).
        when('/users', {
            templateUrl: 'app/users',
			active: 'users'
        }).
        when('/user', {
            templateUrl: 'app/user',
			active: 'user-new'
        }).
        when('/user/:sl', {
            templateUrl: function(params) { return 'app/user?sl=' + params.sl; },
			active: 'user-edit'
        }).
        when('/media', {
            templateUrl: 'app/media',
			active: 'media'
        }).
        when('/upgrade', {
            templateUrl: 'app/upgrade'
        }).
        when('/account', {
            templateUrl: 'app/account',
			active: 'account'
        }).
        when('/order-subscription/:sl', {
            templateUrl: function(params) { return 'app/order-subscription?sl=' + params.sl; },
			active: 'subscription'
        }).
        when('/order-subscription-confirm/:sl', {
            templateUrl: function(params) { return 'app/order-subscription-confirm?sl=' + params.sl; },
			active: 'subscription',
			active_sub: 'invoice'
        }).
        when('/order-subscription-confirmed/:sl', {
            templateUrl: function(params) { return 'app/order-subscription-confirmed?sl=' + params.sl; },
			active: 'subscription'
        }).
        when('/admin/users', {
            templateUrl: 'app/admin/users',
			active: 'admin-users'
        }).
        when('/admin/user', {
            templateUrl: 'app/admin/user',
			active: 'admin-users'
        }).
        when('/admin/user/:sl', {
            templateUrl: function(params) { return 'app/admin/user?sl=' + params.sl; },
			active: 'admin-users'
        }).
        when('/admin/purchases', {
            templateUrl: 'app/admin/purchases',
			active: 'admin-purchases'
        }).
        when('/admin/plans', {
            templateUrl: 'app/admin/plans',
			active: 'admin-plans'
        }).
        when('/admin/plan', {
            templateUrl: 'app/admin/plan',
			active: 'admin-plans'
        }).
        when('/admin/plan/:sl', {
            templateUrl: function(params) { return 'app/admin/plan?sl=' + params.sl; },
			active: 'admin-plans'
        }).
        when('/admin/website', {
            templateUrl: 'app/admin/website',
			active: 'admin-website'
        }).
        when('/admin/website/:sl', {
            templateUrl: function(params) { return 'app/admin/website?sl=' + params.sl; },
			active: 'admin-website'
        }).
        when('/admin/cms', {
            templateUrl: 'app/admin/cms',
			active: 'admin-cms'
        }).
        otherwise({
            redirectTo: '/',
        });
    }
);

/* Controllers */
CmsApp.controller('MainCtrl', function($route, $routeParams, $scope, $location, $templateCache, $rootScope){
	$scope.$on('$routeChangeStart', function(scope, newRoute){
		// Loading animation
		$('#content-wrapper').html('<div class="small-throbber main-throbber" ng-controller="MainCtrl" id="spinner"> </div>');

		$('#spinner').css('margin', (parseInt($(window).outerHeight()) / 2) - 64 + 'px auto 0 auto');
	});

	$rootScope.$on('$routeChangeStart', function(event, next, current) {
        if (typeof(current) !== 'undefined'){
            $templateCache.remove(current.templateUrl);
        }
    });

	$scope.$watch('$viewContentLoaded', function() {
		onPartialLoad();
	});
/*
    $scope.setPopoverContent = function(content) {
        var timestamp = new Date().getUTCMilliseconds();
        $scope.helpPopover = '<div id="popover' + timestamp + '"><div class="spinner" style="margin:0"> <div class="rect1"></div> <div class="rect2"></div> <div class="rect3"></div> <div class="rect4"></div> <div class="rect5"></div> </div></div>';
        $http.get(content, { cache: true}).success(function(data) {
            $('#popover' + timestamp + '').html(data);
        });
    }
*/
});

CmsApp.controller('MainNavCtrl', function($scope, $route){

	// Expose $route
	$scope.$route = $route;
});

/* Owl Carousel */
CmsApp.directive('wrapOwlcarousel', function () {
    return {
        restrict: 'E',
		replace: true,
        link: function (scope, element, attrs) {
            var id = $(element).attr('id');

            var options = scope.$eval($(element).attr('data-options'));
            options.afterInit = function() { if (typeof owlAfterInit == 'function') { owlAfterInit(id); } };

            $(element).owlCarousel(options);
        }
    };
});

/* AngularUI overrides */

angular.module("template/accordion/accordion.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("template/accordion/accordion.html",
	"<div class=\"panel-group panel-group-default\" ng-transclude></div>");
}]);

angular.module("template/accordion/accordion-group.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("template/accordion/accordion-group.html",
	"<div class=\"panel panel-default\">\n" +
	"  <div class=\"panel-heading\">\n" +
	"    <h4 class=\"panel-title\">\n" +
	"      <a class=\"accordion-toggle\" ng-class=\"{'collapsed': isOpen}\" ng-click=\"toggleOpen()\" accordion-transclude=\"heading\"><span ng-class=\"{'text-muted': isDisabled}\">{{heading}}</span></a>\n" +
	"    </h4>\n" +
	"  </div>\n" +
	"  <div class=\"panel-collapse\" collapse=\"!isOpen\">\n" +
	"	  <div class=\"panel-body\" ng-transclude></div>\n" +
	"  </div>\n" +
	"</div>");
}]);

angular.module("template/popover/popover.html", []).run(["$templateCache", function($templateCache) {
  $templateCache.put("template/popover/popover.html",
	"<div class=\"popover {{placement}}\" ng-class=\"{ in: isOpen(), fade: animation() }\">\n" +
	"  <div class=\"arrow\"></div>\n" +
	"\n" +
	"  <div class=\"popover-inner\">\n" +
	"      <h3 class=\"popover-title\" bind-html-unsafe=\"title\" ng-show=\"title\"></h3>\n" +
	"      <div class=\"popover-content\" bind-html-unsafe=\"content\"></div>\n" +
	"  </div>\n" +
	"</div>\n" +
	"");
}]);