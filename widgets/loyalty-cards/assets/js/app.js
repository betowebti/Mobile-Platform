angular.module('ngApp').controller('LoyaltyCardsCtrl', function($scope, $http, $ionicLoading) {

	$ionicLoading.show();
	$scope.cards = [];

 	// Wait until the sl var is init
	$scope.$watch('sl', function () {
		$scope.showCards();
	});

    $scope.showCards = function() {
		// Load cards
		$http.get(url + '/api/v1/widget/get/loyalty-cards/getCards?sl=' + $scope.sl).then(function(resp) {
			$scope.cards = resp.data;
			$ionicLoading.hide();
		}, function(err) {
			console.error('ERR', err.status);
		});
    };

    $scope.correctTimestring = function(string) {
        return new Date(Date.parse(string));
    };

    $scope.doRefresh = function() {
        $scope.showCards();
        $scope.$broadcast('scroll.refreshComplete');
    };

})

.controller('LoyaltyCardCtrl', function($rootScope, $scope, $http, $ionicLoading, $ionicPopup) {

	$ionicLoading.show();
	$scope.card = [];

 	// Wait until the sl var is init
	$scope.$watch('sl', function () {
		$scope.checkStatus();
	});

    $scope.checkStatus = function() {
		// Load card info
		$http.get(url + '/api/v1/widget/get/loyalty-cards/checkStatus?sl=' + $scope.sl).then(function(resp) {
			$scope.card = resp.data;
			$ionicLoading.hide();
		}, function(err) {
			console.error('ERR', err.status);
		});
    };

    $scope.stampCard = function(logged_in, stamps, total, redeemed) {

		if (! logged_in) 
		{
			$rootScope.systemModal('login');
		}
		else
		{
			if (redeemed)
			{
				$ionicPopup.alert({
					title: $scope.redeemed_content
				});
				return;
				e.preventDefault();
			}

			$scope.data = {};

			var title = (stamps == total) ? $scope.redeem_freebie : $scope.stamp_card;
			var button = (stamps == total) ? $scope.redeem : $scope.stamp;
			var content = (stamps == total) ? $scope.redeem_content : $scope.stamp_content;

			var myPopup = $ionicPopup.show({
				template: '<input type="password" ng-model="data.code">',
				title: title,
				subTitle: content,
				scope: $scope,
				buttons: [
					{ text: $scope.cancel },
					{
						text: '<b>' + button + '</b>',
						type: 'button-positive',
						onTap: function(e) {
							if (! $scope.data.code) {
								e.preventDefault();
							} else {
								return $scope.data.code;
							}
						}
					}
				]
			});
			myPopup.then(function(res) {
				if (typeof res !== 'undefined')
				{
					$ionicLoading.show();
	
					$http.get(url + '/api/v1/widget/get/loyalty-cards/checkStatus?code=' + res + '&sl=' + $scope.sl).then(function(resp) {
						$scope.card = resp.data;

						if (typeof resp.data.response.msg !== 'undefined')
						{
							$ionicPopup.alert({
								title: resp.data.response.msg
							});
						}

						$ionicLoading.hide();
					}, function(err) {
						console.error('ERR', err.status);
					});
				}
			});
		}
    };

    $scope.correctTimestring = function(string) {
        return new Date(Date.parse(string));
    };
});