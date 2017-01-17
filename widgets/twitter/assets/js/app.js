angular.module('ngApp').controller('TwitterCtrl', function($scope, $http) {

	$scope.home_timeline = [];

 	// Wait until the var is init
	$scope.$watch('sl', function () {
		$scope.showHomeTimeline();
	});

    $scope.showHomeTimeline = function() {
		$http.get(url + '/api/v1/widget/get/twitter/getTweets?sl=' + $scope.sl).then(function(resp) {
			$scope.home_timeline = resp.data;

		}, function(err) {
			console.error('ERR', err.status);
		});
    };

    $scope.correctTimestring = function(string) {
        return new Date(Date.parse(string));
    };

    $scope.doRefresh = function() {
        $scope.showHomeTimeline();
        $scope.$broadcast('scroll.refreshComplete');
    };

})
.filter('linkUsername', function() {
	return function(text) {
		return '<a href="http://twitter.com/' + text.slice(1) + '">' + text + '</a>';
	};
})
.filter('linkHashtag', function() {
	return function(text) {
		return '<a href="http://twitter.com/search/%23' + text.slice(1) + '">' + text + '</a>';
	};
})
.filter('tweet', function() {
	return function(text) {
		var urlRegex = /((https?:\/\/)?[\w-]+(\.[\w-]+)+\.?(:\d+)?(\/\S*)?)/g;
		var twitterUserRegex = /@([a-zA-Z0-9_]{1,20})/g;
		var twitterHashTagRegex = /\B#(\w+)/g;

		text = text.replace(urlRegex," <a href='$&' target='_blank'>$&</a>").trim();
		text = text.replace(twitterUserRegex,"<a href='http://www.twitter.com/$1' target='_blank'>@$1</a>");
		text = text.replace(twitterHashTagRegex,"<a href='http://twitter.com/search/%23$1' target='_blank'>#$1</a>");

		return text;
	};
});