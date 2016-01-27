app.controller('index', ['$scope', '$rootScope', '$http', function ($scope, $rootScope, $http) {
	$scope.signout = function () {
		$http.get('/api/user/signout').success(function (response) {
			if(response['code'] === '0000') {
				$rootScope.$broadcast('refreshUserData');
			}
		});
	}
}]);
