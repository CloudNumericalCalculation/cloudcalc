app.controller('userSignin', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
	$scope.user = {
		username: '',
		password: ''
	}
	$scope.signin = function () {
		var user = angular.copy($scope.user);
		user.password = md5(user.password);
		$http.post('/api/user/signin', user).success(function (response) {
			if(response['code'] === '0000') {
				$rootScope.$broadcast('refreshUserData');
				$state.go('index');
			}
			else {
				alert(response['errorMsg']);
			}
		}).error(function () {
			alert('Network Error!');
		});
	}
}]);