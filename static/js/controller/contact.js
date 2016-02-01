app.controller('contact', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http){
	$scope.chooseType = '0';
	$scope.basic = {
		name: '',
		contact: ''
	}
	$scope.feedback = '';
	$scope.plugin = {
		username: '',
		name: '',
		author: '',
		git: ''
	}
	$scope.sendFeedback = function () {
		var data = {
			basic: angular.copy($scope.basic),
			feedback: angular.copy($scope.feedback)
		}
		// console.log(data);
		$http.post('/api/mail/feedback', data).success(function (response) {
			// console.log(response);
			if(response['code'] === '0000') {
				alert('反馈成功');
			}
			else {
				alert('反馈失败');
			}
		}).error(function () {
			alert('Network Error.');
		});
	}
	$scope.sendPlugin = function () {
		var data = {
			basic: angular.copy($scope.basic),
			plugin: angular.copy($scope.plugin)
		}
		// console.log(data);
		$http.post('/api/mail/plugin', data).success(function (response) {
			// console.log(response);
			if(response['code'] === '0000') {
				alert('提交成功');
			}
			else {
				alert('提交失败');
			}
		}).error(function () {
			alert('Network Error.');
		});
	}

}]);