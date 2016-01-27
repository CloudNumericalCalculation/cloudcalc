app.controller('userSignup', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
	$scope.user = {
		username: '',
		password: '',
		password_confirm: '',
		email: ''
	}
	$scope.signup = function () {
		var user = angular.copy($scope.user);
		if(user.password != user.password_confirm) {
			alert('两次输入的密码不一样！');
			return;
		}
		delete(user.password_confirm);
		user.password = md5(user.password);
		$http.post('/api/user/signup', user).success(function (response) {
			if(response['code'] === '0000') {
				alert('注册成功，请登陆');
				$state.go('user.signin');
			}
			else {
				alert(response['errorMsg']);
			}
		}).error(function () {
			alert('Network Error!');
		});
	}
}]);