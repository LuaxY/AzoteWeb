(function () {
    'use strict';

    var controllerId = 'header';

    angular.module('app').controller(controllerId,
        ['$scope', '$route', '$location', 'common', 'config', 'routes', 'authService', 'gameService', header]);

    function header($scope, $route, $location, common, config, routes, authService, gameService) {
        var getLogFn = common.logger.getLogFn;
        var log = getLogFn(controllerId);
        var logSuccess = getLogFn(controllerId, 'success');
        var logError = getLogFn(controllerId, 'error');
        var logWarning = getLogFn(controllerId, 'warning');

        var vm = this;

        vm.isCurrent = isCurrent;
        vm.routes = routes;
        vm.navRoutes = [];
        vm.isLogged = authService.isLogged();
        vm.disconnect = disconnect;
        vm.profile = [];

        activate();

        function activate() {
            getNavRoutes();

            if (vm.isLogged)
                loadProfile();
        }

        function authListener(event, args) {
            loadProfile();
            vm.isLogged = authService.isLogged();
        }

        function timeoutListener(event, args) {
            disconnect();

            logWarning('Votre session est arrivée à expiration, veuillez vous reconnecter.');
        }

        function unauthenticatedListener(event, args) {
            logWarning('Requête non autorisée !');
        }

        $scope.$on(config.events.authLogged, authListener);
        $scope.$on(config.events.authDisconnected, authListener);
        $scope.$on(config.events.authSessionTimeout, timeoutListener);
        $scope.$on(config.events.authUnauthenticated, unauthenticatedListener);
        $scope.$on(config.events.authUnauthorized, unauthenticatedListener);

        function getNavRoutes() {
            vm.navRoutes = routes.filter(function (r) {
                return r.config.settings && r.config.settings.nav;
            }).sort(function (r1, r2) {
                return r1.config.settings.nav > r2.config.settings.nav;
            });
        }

        function isCurrent(route) {
            if (!route.config.title || !$route.current || !$route.current.title) {
                return '';
            }

            var menuName = route.config.title;

            return $route.current.title.substr(0, menuName.length) === menuName ? 'active' : '';
        }

        function loadProfile() {
            return gameService.getProfile()
                .then(function (result) {
                    vm.profile = result.profile;
                }, function (result) {
                    log("Profile error: " + result.message);
                });
        }

        function disconnect() {
            authService.disconnect();
            $location.path('/');
        }
    };
})();
