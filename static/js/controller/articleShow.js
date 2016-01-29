app.controller('articleShow', ['$scope', '$rootScope', '$http', '$timeout', 'aid', function($scope, $rootScope, $http, $timeout, aid){
	var fetchData = function () {
		$http.post('/api/article/show', {aid: aid}).success(function (response) {
			if(response['code'] === '0000') {
				$scope.current = response['response'];
			}
			else {
				$scope.current = {
					title: '出错啦',
					content: response['errorMsg']
				}
			}
		})
	}
	$scope.current = {
		title: '',
		content: ''
	}
	$timeout(fetchData, 0);
}]);