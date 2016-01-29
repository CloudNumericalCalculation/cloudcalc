app.controller('adminPlugin', ['$scope', '$rootScope', '$http', '$timeout', function($scope, $rootScope, $http, $timeout){
	var fetchData = function () {
		$http.get('/api/plugin/list').success(function (response) {
			if(response['code'] === '0000') {
				$scope.plugins = response['response'];
			}
		});
	}
	$scope.plugins = [];
	$timeout(fetchData, 0);

	$scope.modify = function (item) {
		$http.post('/api/plugin/renew', item).success(function (response) {
			if(response['code'] === '0000') {
				alert('修改成功！');
			}
			else {
				alert(response['errorMsg']);
			}
			$timeout(fetchData, 0);
		});
	}
}]);