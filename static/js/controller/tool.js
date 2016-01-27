app.controller('tool', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http){
	$http.post('/api/plugin/show', {pid: $rootScope.$state.params.toolId}).success(function (response) {
		if(response['code'] === '0000') {
			$scope.current = response['response'];
		}
		else {
			alert(response['errorMsg']);
		}
	}).error(function () {
		alert('Network Error!');
	});
}]);