app.controller('calculationShow', ['$scope', '$rootScope', '$http', 'current', function($scope, $rootScope, $http, current){
	$scope.current = current;
	$scope.save = function () {
		var data = {
			cid: $scope.current.cid,
			public: $scope.current.public,
			password: $scope.current.password,
			priority: $scope.current.priority
		}
		$http.post('/api/calculation/renew', data).success(function (response) {
			if(response['code'] === '0000') {
				alert('修改成功！');
			}
			else {
				alert(response['errorMsg']);
			}
		}).error(function () {
			alert('Network Error!');
		});
	}
}]);