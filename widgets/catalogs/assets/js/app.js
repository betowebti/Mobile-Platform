angular.module('ngApp').controller('CatalogsCtrl', function($scope, $http, $ionicLoading) {

	$ionicLoading.show();
	$scope.catalogs = [];

 	// Wait until the sl var is init
	$scope.$watch('sl', function () {
		$scope.showCatalogs();
	});

    $scope.showCatalogs = function() {
		// Load catalogs
		$http.get(url + '/api/v1/widget/get/catalogs/getCatalogs?sl=' + $scope.sl).then(function(resp) {
			$scope.catalogs = resp.data;
			$ionicLoading.hide();
		}, function(err) {
			console.error('ERR', err.status);
		});
    };

    $scope.correctTimestring = function(string) {
        return new Date(Date.parse(string));
    };

    $scope.doRefresh = function() {
        $scope.showCatalogs();
        $scope.$broadcast('scroll.refreshComplete');
    };

})

.controller('CatalogCtrl', function($scope, $http, $ionicLoading) {

	$ionicLoading.show();

 	// Wait until the sl var is init
	$scope.$watch('sl', function () {
		// Load catalogs
		$http.get(url + '/api/v1/widget/get/catalogs/checkRedeemed?sl=' + $scope.sl + '&code=' + $scope.code).then(function(resp) {
			$scope.catalog = resp.data;
			$ionicLoading.hide();
		}, function(err) {
			console.error('ERR', err.status);
		});
	});

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