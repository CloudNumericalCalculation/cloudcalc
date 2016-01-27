app.controller('userCenter', ['$scope', '$rootScope', '$http', 'current', function($scope, $rootScope, $http, current){
	$scope.current = current;
	$scope.changepwd = function () {
		if($scope.password_new !== $scope.password_new_confirm) {
			alert('两次输入的密码不一样！');
			return;
		}
		var user = {
			uid: $rootScope.user.uid,
			password_old: md5($scope.password_old),
			password_new: md5($scope.password_new)
		}
		$http.post('/api/user/renew', user).success(function (response) {
			if(response['code'] === '0000') {
				alert('密码修改成功！');
				$scope.password_old = '';
				$scope.password_new = '';
				$scope.password_new_confirm = '';
			}
			else {
				alert(response['errorMsg']);
			}
		}).error(function () {
			alert('Network Error!');
		});
	}
}]);