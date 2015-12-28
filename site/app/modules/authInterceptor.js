(function () {
    'use strict';

    var serviceId = 'authInterceptor';

    angular.module('app').factory(serviceId, ['$rootScope', '$q', 'config', 'webStorage', function ($rootScope, $q, config, webStorage) {
        var authInterceptor = {
            request: function (config) {
                if (webStorage.session.has('authorizationTicket')) {
                    config.headers['authorizationTicket'] = webStorage.session.get('authorizationTicket');
                }
                return config;
            },
            responseError: function (response) {
                if (response.status === 401) {
                    $rootScope.$broadcast(config.events.authUnauthenticated,
                        response);
                }
                if (response.status === 403) {
                    $rootScope.$broadcast(config.events.authUnauthorized,
                        response);
                }
                if (response.status === 419 || response.status === 440) {
                    $rootScope.$broadcast(config.events.authSessionTimeout,
                        response);
                }
                return $q.reject(response);
            }
        };
        return authInterceptor;
    }]);

    angular.module('app').config(['$httpProvider', function ($httpProvider) {
        $httpProvider.interceptors.push(serviceId);
    }]);
})();