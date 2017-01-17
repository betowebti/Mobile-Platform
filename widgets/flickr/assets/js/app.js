angular.module('ngApp')

.factory('Flickr', function($resource, $q) {
  var photosPublic = $resource('http://api.flickr.com/services/feeds/photos_public.gne', 
      { format: 'json', jsoncallback: 'JSON_CALLBACK' }, 
      { 'load': { 'method': 'JSONP' } });
      
  return {
    search: function(query, type) {
      var q = $q.defer();

		if(type == 'tags')
		{
		  photosPublic.load({
			tags: query
		  }, function(resp) {
			q.resolve(resp);
		  }, function(err) {
			q.reject(err);
		  })
		}

		if(type == 'id')
		{
		  photosPublic.load({
			id: query
		  }, function(resp) {
			q.resolve(resp);
		  }, function(err) {
			q.reject(err);
		  })
		}

		if(type == 'ids')
		{
		  photosPublic.load({
			ids: query
		  }, function(resp) {
			q.resolve(resp);
		  }, function(err) {
			q.reject(err);
		  })
		}
      
      return q.promise;
    }
  }
})

.controller('FlickrCtrl', function($scope, Flickr) {

  var doSearch = ionic.debounce(function(query, type) {
    Flickr.search(query, type).then(function(resp) {
      $scope.photos = resp;
    });
  }, 500);

  $scope.show = function(query, type) {
    doSearch(query, type);
  }

})

.directive('photo', function($window) {
  return {
    restrict: 'C',
    link: function($scope, $element, $attr) {
      var size = ($window.outerWidth / 3) - 2;
      //$element.css('width', size + 'px');
    }
  }
});