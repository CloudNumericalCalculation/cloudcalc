app.controller('calculationList', ['$scope', '$rootScope', '$http', '$timeout', '$state', function($scope, $rootScope, $http, $timeout, $state){
	$scope.calculations = [];
	var needRefresh = function () {
		for (var i = $scope.calculations.length - 1; i >= 0; i--) {
			if($scope.calculations[i].status == 0) return true;
			if($scope.calculations[i].status == 1) return true;
		}
		return false;
	}
	var fetchCalculationList = function () {
		$http.post('/api/calculation/list', $scope.search).success(function (response) {
			if(response['code'] === '0000') {
				$scope.calculations = response['response'];
				if(needRefresh() && $state.$current.name === 'calculation') {
					$timeout(fetchCalculationList, 3000);
				}
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
	});
}]);