app.controller('articleNew', ['$scope', '$rootScope', '$http', '$state', function($scope, $rootScope, $http, $state){
	$scope.current = {
		aid: -1,
		title: '',
		content: '',
		visibility: false,
		notice: false
	}

	$scope.save = function () {
		$http.post('/api/article/new', $scope.current).success(function (response) {
			if(response['code'] === '0000') {
				$state.go('article.show', {articleId: response['response']['aid']});
			}
			else {
				alert(response['errorMsg']);
			}
			$rootScope.$broadcast('refreshSiteData');
		}).error(function () {
			alert('Network Error');
		});
	}
}]);