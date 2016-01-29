app.controller('tool', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
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
	$scope.current = {
		pid: '', uid: '', folder: '', cover: '', name: '',
		author: '', git: '', gitStatus: '', available: '',
		file: {
			readme: '',
			input: []
		}
	}
	$scope.submit = function () {
		// var data = JSON.stringify($scope.current.file.input);
		var data = {
			pid: $scope.current.pid,
			input: angular.toJson($scope.current.file.input)
		}
		// console.log(data);
		$http.post('/api/calculation/new', data).success(function (response) {
			if(response['code'] === '0000') {
				$state.go('calculation.show', {calcId: response['response']['cid']});
			}
		}).error(function () {
			alert('Network Error.');
		});
	}
}]);