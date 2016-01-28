app.controller('calculationList', ['$scope', '$rootScope', '$http', '$timeout', function($scope, $rootScope, $http, $timeout){
	$scope.calculations = [];
	var fetchCalculationList = function () {
		$http.post('/api/calculation/list', $scope.search).success(function (response) {
			if(response['code'] === '0000') {
				$scope.calculations = response['response'];
			}
		}).error(function () {
			alert('Network Error');
		});
	}
	
	$scope.search = {
		user: '0',
		public: '-1',
		status: '-1'
	}
	$scope.fetchDataFlag = 0;
	$scope.$watch('search.user', function () {
		$scope.fetchDataFlag = 1;
	});
	$scope.$watch('search.public', function () {
		$scope.fetchDataFlag = 1;
	});
	$scope.$watch('search.status', function () {
		$scope.fetchDataFlag = 1;
	});
	$scope.$watch('fetchDataFlag', function () {
		if($scope.fetchDataFlag == 0) return;
		$scope.fetchDataFlag = 0;
		$timeout(fetchCalculationList, 0);
	})
}]);