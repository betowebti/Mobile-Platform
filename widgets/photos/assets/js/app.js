app_dependencies.push('ion-gallery');

angular.module('ngApp')

.config(function(ionGalleryConfigProvider) {
	ionGalleryConfigProvider.setGalleryConfig({
		action_label: 'X',
		toggle: false,
		row_size: 3
	});
})

.controller('PhotosCtrl', ['$scope', '$http', '$ionicLoading', function($scope, $http, $ionicLoading) {

	$ionicLoading.show();
	$scope.items = [];
	$scope.photos = {'src':'','sub':'','thumb':''};

 	// Wait until the sl var is init
	$scope.$watch('sl', function () {
		$scope.showPhotos();
	});

    $scope.showPhotos = function() {
		$http.get(url + '/api/v1/widget/get/photos/getPhotos?sl=' + $scope.sl).then(function(resp) {
			$scope.items = resp.data;
			$scope.photos = resp.data.photos;
			$ionicLoading.hide();
		}, function(err) {
			console.error('ERR', err.status);
		});
	}
}]);