var app = angular.module('cloudcalc', ['ngAnimate', 'ngRoute', 'ui.router', 'ui.bootstrap']);
app.config(['$urlRouterProvider', '$locationProvider', '$stateProvider', function ($urlRouterProvider, $locationProvider, $stateProvider) {
	$locationProvider.html5Mode(true);
	$stateProvider.
	state('index', {
		url: '/',
		templateUrl: '/template/index.html',
		controller: 'index'
	}).
	// state('tool', {
	// 	url: '/tools/:toolId',
	// 	templateUrl: '/template/tool.html',
	// 	controller: 'tool'
	// }).
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
