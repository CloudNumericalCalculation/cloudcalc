app.run(['$rootScope', '$http', '$timeout', '$window', '$state', function($rootScope, $http, $timeout, $window, $state){
	$rootScope.$state = $state;
}]);
