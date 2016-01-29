app.controller('adminArticle', ['$scope', '$rootScope', '$http', '$timeout', function($scope, $rootScope, $http, $timeout){
	var fetchData = function () {
		$http.get('/api/article/list').success(function (response) {
			if(response['code'] === '0000') {
				$scope.articles = response['response'];
			}
		});
	}
	$scope.articles = [];
	$timeout(fetchData, 0);
}]);