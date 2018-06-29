(function () {
    angular
        .module('domain', [
            'ui.router',
            'ngAnimate',
            'ui.bootstrap',
            'ui-notification',
            'domain.list',
            'domain.new',
            'domain.factory'
        ])
        .config(['$locationProvider', function ($locationProvider) {
            $locationProvider.html5Mode({
                enabled: true,
                requireBase: false
            });
        }])
        .config(function ($urlRouterProvider, $stateProvider) {
            $urlRouterProvider.otherwise("/");

            $stateProvider
                .state('list', {
                    url: '/',
                    templateUrl: 'views/angular/list.html'
                })
                .state('new', {
                    url: '/new',
                    templateUrl: 'views/angular/new.html'
                })

        })
})();