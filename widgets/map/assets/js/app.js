angular.module('ngApp').requires.push('leaflet-directive');
var getRoute;
angular.module('ngApp').controller('MapCtrl', ['$scope', '$http', 'leafletData', function($scope, $http, leafletData) {

    angular.extend($scope, {
        markers: [],
        route: false,
        defaults: {
            scrollWheelZoom: true,
            doubleClickZoom: true,
            attributionControl: false,
            zoomControl: true
        }
    });

    // Wait until the var is init
    $scope.$watch('sl', function() {
        $scope.addMarkers();
    });

    $scope.addMarkers = function() {
        $http.get(url + '/api/v1/widget/get/map/getMarkers?sl=' + $scope.sl).then(function(resp) {
            var data = resp.data[0];

            if (data.latitude != '') {

                leafletData.getMap('map' + $scope.id).then(function(map) {
                    map.setView([parseFloat(data.latitude), parseFloat(data.longitude)], parseInt(data.zoom));
                });

                if (data.iconUrl != null) {
                    $scope.markers = [{
                        lat: parseFloat(data.latitude),
                        lng: parseFloat(data.longitude),
                        focus: data.open_marker,
                        message: data.marker,
                        icon: {
                            iconUrl: data.iconUrl,
                            iconSize: data.iconSize,
                            iconAnchor: data.iconAnchor,
                            popupAnchor: data.popupAnchor
                        }
                    }];
                } else {
                    $scope.markers = [{
                        lat: parseFloat(data.latitude),
                        lng: parseFloat(data.longitude),
                        focus: data.open_marker,
                        message: data.marker
                    }];
                }

            }

        }, function(err) {
            console.error('ERR', err.status);
        });
    };

    getRoute = function() {
        //if(! $scope.route)
        //{
            $scope.route = true;
            if (navigator.geolocation)
            {
                navigator.geolocation.getCurrentPosition(showRoute, showError);
            }
            else 
            {
                alert("Geolocation is not supported by this browser.");
            }
        //}
    };

    function showRoute (position)
    {
        if (typeof $scope.markers[0] !== 'undefined') {
            leafletData.getMap('map' + $scope.id).then(function(map) {
                L.Routing.control({
                    waypoints: [
                        L.latLng(position.coords.latitude, position.coords.longitude),
                        L.latLng($scope.markers[0].lat, $scope.markers[0].lng)
                    ]
                }).addTo(map);
            });
        } else {
            alert("No address found.");
        }
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                alert("User denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                alert("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
        }
    }
}]);

angular.module('ngApp').controller('MainCtrl', function($scope) {
    $scope.getRoute = function() {
        getRoute();
    }
});
