angular.module('ngApp').controller('RssCtrl', function($scope, $http) {

	$scope.feed = [];

 	// Wait until the var is init
	$scope.$watch('sl', function () {
		$scope.showFeed();
	});

    $scope.showFeed = function() {
		$http.get(url + '/api/v1/widget/get/rss/getFeed?sl=' + $scope.sl).then(function(resp) {
			$scope.feed = resp.data;

		}, function(err) {
			console.error('ERR', err.status);
		});
    };

    $scope.correctTimestring = function(string) {
        return new Date(Date.parse(string));
    };

    $scope.doRefresh = function() {
        $scope.showFeed();
        $scope.$broadcast('scroll.refreshComplete');
    };

});