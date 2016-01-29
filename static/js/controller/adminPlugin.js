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
	$scope.gitclone = function (item) {
		if(!confirm('确认执行git clone操作？\n若文件夹不存在将自动创建')) return;
		alert('还没写呢。。。');
	}

	$scope.newItem = {
		uid: '',
		folder: '',
		cover: '',
		name: '',
		author: '',
		git: '',
		available: false
	}
	$scope.new = function () {
		var str = '部分信息创建后不能修改，确认？\n'
			+ 'uid: ' + $scope.newItem.uid + '\n'
			+ 'folder: ' + $scope.newItem.folder + '\n'
			+ 'cover: ' + $scope.newItem.cover + '\n'
			+ 'name: ' + $scope.newItem.name + '\n'
			+ 'author: ' + $scope.newItem.author + '\n'
			+ 'git: ' + $scope.newItem.git + '\n'
			+ 'available: ' + $scope.newItem.available + '\n';
		if(!confirm(str)) return;
		$http.post('/api/plugin/new', $scope.newItem).success(function (response) {
			if(response['code'] === '0000') {
				alert('创建成功！');
				$scope.newItem.uid = '';
				$scope.newItem.folder = '';
				$scope.newItem.cover = '';
				$scope.newItem.name = '';
				$scope.newItem.author = '';
				$scope.newItem.git = '';
				$scope.newItem.available = false;
			}
			else {
				alert(response['errorMsg']);
			}
			$timeout(fetchData, 0);
		}).error(function () {
			alert('Network Error!');
		});
	}
}]);