app.controller('calculationList', ['$scope', '$rootScope', '$http', 'currentUid', function($scope, $rootScope, $http, currentUid){
	$rootScope.$broadcast('refreshUserData');
	$scope.calculations = [];
	$http.post('/api/calculation/list', {uid: currentUid}).success(function (response) {
		if(response['code'] === '0000') {
			$scope.calculations = response['response'];
		}
	}).error(function () {
		alert('Network Error');
	});
}]);