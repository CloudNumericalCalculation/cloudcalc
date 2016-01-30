app.controller('calculationShow', ['$scope', '$rootScope', '$http', '$timeout', 'cid', '$state', function($scope, $rootScope, $http, $timeout, cid, $state){
	var needRefresh = function () {
		if($scope.current.status == 0) return true;
		if($scope.current.status == 1) return true;
		return false;
	}
	var fetchData = function (password) {
		var data = {
			cid: cid,
			password: $scope.password
		}
		$http.post('/api/calculation/show', data).success(function (response) {
			// console.log(response);
			if(response['code'] === '0000') {
				$scope.pageStatus = 0;
				$scope.current = response['response'];
				if(needRefresh() && $state.$current.name === 'calculation.show') {
					$timeout(fetchData, 3000);
				}
			}
			else if(response['code'] === '0202') {
				$scope.pageStatus = 1;
				$scope.errorMsg = response['errorMsg'];
				$scope.password = '';
			}
			else {
				$scope.pageStatus = 2;
				$scope.errorMsg = response['errorMsg'];
			}
		});
	}
	$scope.pageStatus = 2;
	$scope.errorMsg = '正在载入......';
	$scope.password = '';
	$timeout(fetchData, 0);

	$scope.show = function () {
		$timeout(fetchData, 0);
	}

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