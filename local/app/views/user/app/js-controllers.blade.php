<!-- skipmin --><script>

angular.module('ngApp.controllers', [])

.controller('MainCtrl', function($scope, $rootScope, $state, $stateParams) {
    $rootScope.$state = $state;
	$scope.$state = $state.current;
    $scope.params = $stateParams;
	$scope.bodyClass = '';
})

<?php
foreach($app->appPages as $page)
{
	$class = camel_case('c-' . $page->slug);
?>
.controller('<?php echo $class ?>Ctrl', function($scope, $rootScope, $state, $stateParams) {
	var scope = angular.element('body').scope();
	scope.bodyClass = '<?php echo $class ?>';
})

<?php
}
?>
.controller('NavCtrl', function($scope, $ionicSideMenuDelegate) {
	$scope.showMenu = function () {
		$ionicSideMenuDelegate.toggleLeft();
	  };
	  $scope.showRightMenu = function () {
		$ionicSideMenuDelegate.toggleRight();
	  };
});

</script>{{--skipmin--}}