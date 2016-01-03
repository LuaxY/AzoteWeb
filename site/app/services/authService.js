(function() {
    'use strict';

    var serviceId = 'authService';

    angular.module('app').factory(serviceId, ['common', 'config', 'webStorage', authService]);

    function authService(common, config, webStorage) {
        // Define the functions and properties to reveal.
        var service = {
            isLogged: isLogged,
            login: login,
            disconnect: disconnect,
            register: register,
            getTicket: getTicket
        };

        return service;

        function isLogged() {
            return webStorage.session.has('authorizationTicket');
        }

        function login(form) {
            var url = config.remoteServiceName + 'account/login';
            var deferred = common.$q.defer();

            common.$http.post(url, form)
                .success(function(result) {
                    webStorage.session.add('authorizationTicket', result.authorizationTicket);
                    common.$broadcast(config.events.authLogged);

                    deferred.resolve(result);
                })
                .error(function(result) {
                    deferred.reject(result);
                    common.logger.logError(result.message);
                });

            return deferred.promise;
        }

        function disconnect() {
            webStorage.session.remove('authorizationTicket');
            common.$broadcast(config.events.authDisconnected);
        }

        function register(form) {
            var url = config.remoteServiceName + 'account/register';
            var deferred = common.$q.defer();

            common.$http.post(url, form)
                .success(function(result) {
                    deferred.resolve(result);
                })
                .error(function(result) {
                    deferred.reject(result);
                    common.logger.logError(result.message);
                });

            return deferred.promise;
        }

        function getTicket() {
            return webStorage.session.get('authorizationTicket');
        }
    }
})();
