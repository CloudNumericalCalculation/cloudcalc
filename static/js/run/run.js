app.run(['$rootScope', '$http', '$timeout', '$state', function($rootScope, $http, $timeout, $state){
	$rootScope.$state = $state;
	
	var fetchPluginList = function () {
		$http.get('/api/plugin/list').success(function (response) {
			if(response['code'] === '0000') {
				// console.log(response);
				$rootScope.plugins = response['response'];
			}
		}).error(function () {
			alert('Network Error!');
		});
	}
	var fetchUserData = function () {
		$http.get('/api/user/data').success(function (response) {
			if(response['code'] === '0000') {
				$rootScope.user = response['response'];
			}
			if(!$rootScope.user.signin && $state.includes('user.center')) {
				$state.go('index');
			}
			if($rootScope.user.level !== 9 && $state.includes('admin')) {
				$state.go('index');
			}
			if($rootScope.user.level !== 9 && $state.includes('article.edit')) {
				$state.go('index');
			}
		}).error(function () {
			alert('Network Error!');
		});
	}
	var fetchSiteData = function () {
		$http.get('/api/site/data').success(function (response) {
			if(response['code'] === '0000') {
				// console.log(response);
				$rootScope.site = response['response'];
			}
		}).error(function () {
			alert('Network Error!');
		});
	}

	$rootScope.$on('refreshPluginList', function () {
		$timeout(fetchPluginList, 0);
	});
	$rootScope.$on('refreshUserData', function () {
		$timeout(fetchUserData, 0);
	});
	$rootScope.$on('refreshSiteData', function () {
		$timeout(fetchSiteData, 0);
	});
	$rootScope.$on('refreshAll', function () {
		$timeout(fetchPluginList, 0);
		$timeout(fetchUserData, 0);
		$timeout(fetchSiteData, 0);
	})

	$rootScope.$broadcast('refreshAll');
}]);
