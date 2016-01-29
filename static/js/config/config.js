var app = angular.module('cloudcalc', ['ngAnimate', 'ngRoute', 'ui.router', 'ui.bootstrap', 'toggle-switch']);
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
				cid: ['$stateParams', function ($stateParams) {
					return $stateParams.calcId;
				}]
			}
		}}
	}).
	state('admin', {
		url: '/admin',
		onEnter: ['$rootScope', function ($rootScope) {
			$rootScope.$broadcast('refreshUserData');
		}]
	}).
	state('admin.user', {
		url: '/user',
		views: {'@': {
			templateUrl: '/template/admin/user.html',
			controller: 'adminUser'
		}}
	}).
	state('admin.article', {
		url: '/notice',
		views: {'@': {
			templateUrl: '/template/admin/article.html',
			controller: 'adminArticle'
		}}
	}).
	state('article', {
		url: '/notice'
	}).
	state('article.show', {
		url: '/show/:articleId',
		views: {'@': {
			templateUrl: '/template/article/show.html',
			controller: 'articleShow',
			resolve: {
				aid: ['$stateParams', function ($stateParams) {
					return $stateParams.articleId;
				}]
			}
		}},

	}).
	state('article.edit', {
		url: '/edit/:articleId',
		views: {'@': {
			templateUrl: '/template/article/edit.html',
			controller: 'articleEdit',
			resolve: {
				aid: ['$stateParams', function ($stateParams) {
					return $stateParams.articleId;
				}]
			}
		}},
		onEnter: ['$rootScope', function ($rootScope) {
			$rootScope.$broadcast('refreshUserData');
		}]
	}).
	state('article.new', {
		url: '/new',
		views: {'@': {
			templateUrl: '/template/article/edit.html',
			controller: 'articleNew'
		}},
		onEnter: ['$rootScope', function ($rootScope) {
			$rootScope.$broadcast('refreshUserData');
		}]
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
