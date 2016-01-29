app.controller('adminUser', ['$scope', '$rootScope', '$http', '$timeout', function($scope, $rootScope, $http, $timeout){
	var fetchData = function () {
		$http.post('/api/user/list', $scope.search).success(function (response) {
			if(response['code'] === '0000') {
				$scope.users = response['response'];
			}
		});
	}
	$scope.users = [];
	$scope.pwd = [];
	$timeout(fetchData, 0);
	$scope.search = {
	}

	$scope.resetPassword = function (uid) {
		if(!confirm('Sure?')) return;
		$http.post('/api/user/resetPassword', {uid: uid}).success(function (response) {
			if(response['code'] === '0000') {
				prompt('修改成功，重置的新密码如下', response['response']['password']);
			}
			else {
				alert(response['errorMsg']);
			}
			$timeout(fetchData, 0);
		}).error(function () {
			alert('Network Error.')
		});
	}
	$scope.changeLevel = function (uid, level) {
		var data = {
			uid: uid,
			level: level
		}
		$http.post('/api/user/changeLevel', data).success(function (response) {
			if(response['code'] === '0000') {
				alert('修改成功');
			}
			else {
				alert(response['errorMsg']);
			}
			$timeout(fetchData, 0);
		}).error(function () {
			alert('Network Error.')
		});
	}
}]);