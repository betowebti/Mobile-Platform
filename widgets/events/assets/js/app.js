angular.module('ngApp').controller('EventsCtrl', function($scope, $http, $ionicLoading) {

	$ionicLoading.show();
	$scope.events = [];

 	// Wait until the sl var is init
	$scope.$watch('sl', function () {
		$scope.showEvents();
	});

    $scope.showEvents = function() {
		// Load events
		$http.get(url + '/api/v1/widget/get/events/getEvents?sl=' + $scope.sl).then(function(resp) {
			$scope.events = resp.data;
			$ionicLoading.hide();
		}, function(err) {
			console.error('ERR', err.status);
		});
    };

    $scope.correctTimestring = function(string) {
        return new Date(Date.parse(string));
    };

    $scope.doRefresh = function() {
        $scope.showEvents();
        $scope.$broadcast('scroll.refreshComplete');
    };

})

.controller('EventCtrl', function($scope, $http, $ionicLoading) {
    $scope.correctTimestring = function(string) {
        return new Date(Date.parse(string));
    };
})