var app = angular.module('cloudcalc', ['ngAnimate', 'ngRoute', 'ui.router', 'ui.bootstrap']);
app.config(['$urlRouterProvider', '$locationProvider', '$stateProvider', function ($urlRouterProvider, $locationProvider, $stateProvider) {
	$locationProvider.html5Mode(true);
	$stateProvider.
	state('index', {
		url: '/',
		templateUrl: '/template/index.html',
		controller: 'index'
	}).
	state('user', {
		url: '/user'
	}).
	state('user.signin', {
		url: '/signin',
		views: {'@': {
			templateUrl: '/template/user/signin.html',
			controller: 'userSignin'
		}}
	}).
	state('user.signup', {
		url: '/signup',
		views: {'@': {
			templateUrl: '/template/user/signup.html',
			controller: 'userSignup'
		}}
	}).
	state('user.center', {
		url: '/center/:userId',
		views: {'@': {
			templateUrl: '/template/user/center.html',
			controller: 'userCenter',
			resolve: {
				current: ['$http', '$stateParams', '$state', function ($http, $stateParams, $state) {
					// console.log($stateParams.userId);
					return $http.post('/api/user/show', {uid: $stateParams.userId}).then(function (response) {
						// console.log(response);
						if(response['data']['code'] === '0000') {
							return response['data']['response'];
						}
						else {
							$state.go('error.404');
						}
					});
				}]
			}
		}}
	}).
	state('tool', {
		url: '/tool/:toolId',
		templateUrl: '/template/tool.html',
		controller: 'tool'
	}).
	state('calculation', {
		url: '/calculation',
		templateUrl: '/template/calculation/list.html',
		controller: 'calculationList'
	}).
	state('calculation.show', {
		url: '/:calcId',
		views: {'@': {
			templateUrl: '/template/calculation/show.html',
			controller: 'calculationShow',
			resolve: {
				current: ['$http', '$stateParams', '$state', function ($http, $stateParams, $state) {
					// console.log($stateParams.calcId);
					return $http.post('/api/calculation/show', {cid: $stateParams.calcId}).then(function (response) {
						// console.log(response);
						if(response['data']['code'] === '0000') {
							return response['data']['response'];
						}
						else {
							$state.go('error.404');
						}
					});
				}]
			}
		}}
	}).
	state('error', {
		url: '/error',
		templateUrl: '/template/error/frame.html'
	}).
	state('error.404', {
		url: '/404',
		templateUrl: '/template/error/404.html'
	}).
	state('error.message', {
		url: '/message/:pageErrorMessage',
		templateUrl: '/template/error/message.html'
	});
	$urlRouterProvider.otherwise('/error/404');
}]);
