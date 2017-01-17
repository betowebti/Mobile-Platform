angular.module('ngApp').controller('CouponsCtrl', function($scope, $http, $ionicLoading) {

	$ionicLoading.show();
	$scope.coupons = [];

 	// Wait until the sl var is init
	$scope.$watch('sl', function () {
		$scope.showCoupons();
	});

    $scope.showCoupons = function() {
		// Load coupons
		$http.get(url + '/api/v1/widget/get/coupons/getCoupons?sl=' + $scope.sl).then(function(resp) {
			$scope.coupons = resp.data;
			$ionicLoading.hide();
		}, function(err) {
			console.error('ERR', err.status);
		});
    };

    $scope.correctTimestring = function(string) {
        return new Date(Date.parse(string));
    };

    $scope.doRefresh = function() {
        $scope.showCoupons();
        $scope.$broadcast('scroll.refreshComplete');
    };

})

.controller('CouponCtrl', function($scope, $http, $ionicLoading) {

	$ionicLoading.show();

 	// Wait until the sl var is init
	$scope.$watch('sl', function () {
		// Load coupons
		$http.get(url + '/api/v1/widget/get/coupons/checkRedeemed?sl=' + $scope.sl + '&code=' + $scope.code).then(function(resp) {
			$scope.coupon = resp.data;
			$ionicLoading.hide();
		}, function(err) {
			console.error('ERR', err.status);
		});
	});

    $scope.redeemCoupon = function(code, back_url) {

		$ionicLoading.show();

		$http.get(url + '/api/v1/widget/get/coupons/redeemCoupon?code=' + code + '&sl=' + $scope.sl).then(function(resp) {
			$scope.coupon = resp.data;
			$ionicLoading.hide();
		}, function(err) {
			console.error('ERR', err.status);
		});
    };
})

/**
 * A generic confirmation for risky actions.
 * Usage: Add attributes: ng-really-message="Are you sure"? ng-really-click="takeAction()" function
 */
.directive('ngReallyClick', ['$ionicPopup', function($ionicPopup) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            element.bind('click', function() {
				var confirmPopup = $ionicPopup.confirm({
					title: attrs.ngReallyTitle,
					template: attrs.ngReallyMessage,
					buttons: [
						  { text: attrs.ngBtnCancel },
						  { text: '<b>' + attrs.ngBtnOk + '</b>', type: 'button-positive', 
						    onTap: function(e) 
						  	{
								setTimeout(function(){ scope.$apply(attrs.ngReallyClick); });
							}
						  }
					]
				});
            });
        }
    }
}]);