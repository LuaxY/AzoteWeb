(function () {
    'use strict';

    var serviceId = 'gameService';

    angular.module('app').factory(serviceId, ['common', 'config', gameService]);

    function gameService(common, config) {
        // Define the functions and properties to reveal.
        var service = {
            getProfile: getProfile,
            updateProfile: updateProfile,
            updateGameAccount: updateGameAccount,
            createGameAccount: createGameAccount,
            getCharacters: getCharacters,
            getCharacterLook: getCharacterLook,
            getRates: getRates
        };

        return service;

        function getProfile() {
            var url = config.remoteServiceName + 'account/profile';
            var deferred = common.$q.defer();

            common.$http.get(url)
                .success(function (result) {
                    deferred.resolve(result);
                })
                .error(function (result) {
                    deferred.reject(result);
                    common.logger.logError(result.message);
                });

            return deferred.promise;
        }

        function updateProfile(form) {
            var url = config.remoteServiceName + 'account/update';

            var deferred = common.$q.defer();

            common.$http.post(url, form)
                .success(function (result) {
                    deferred.resolve(result);
                })
                .error(function (result) {
                    deferred.reject(result);
                    common.logger.logError(result.message);
                });

            return deferred.promise;
        }

        function updateGameAccount(form) {
            var url = config.remoteServiceName + 'account/game/update';

            var deferred = common.$q.defer();

            common.$http.post(url, form)
                .success(function (result) {
                    deferred.resolve(result);
                })
                .error(function (result) {
                    deferred.reject(result);
                    common.logger.logError(result);
                });

            return deferred.promise;
        }


        function createGameAccount(form) {
            var url = config.remoteServiceName + 'account/game/create';

            var deferred = common.$q.defer();

            common.$http.post(url, form)
                .success(function (result) {
                    deferred.resolve(result);
                })
                .error(function (result) {
                    deferred.reject(result);
                    common.logger.logError(result);
                });

            return deferred.promise;
        }

        function getCharacters(accountId) {
            var url = config.remoteServiceName + 'account/game/characters/' + accountId;
            var deferred = common.$q.defer();

            common.$http.get(url)
                .success(function (result) {
                    deferred.resolve(result);
                })
                .error(function (result) {
                    deferred.reject(result);
                    common.logger.logError(result.message);
                });

            return deferred.promise;
        }

        function getCharacterLook(characterId) {
            var url = config.remoteServiceName + 'Game/Look/' + characterId;
            var deferred = common.$q.defer();

            common.$http.get(url)
                .success(function (result) {
                    deferred.resolve(result);
                })
                .error(function (result) {
                    deferred.reject(result);
                    common.logger.logError(result);
                });

            return deferred.promise;
        }

        function getRates() {
            var url = config.remoteServiceName + 'Shop/Rates';
            var deferred = common.$q.defer();

            common.$http.get(url)
                .success(function (result) {
                    deferred.resolve(result);
                })
                .error(function (result) {
                    deferred.reject(result);
                    common.logger.logError(result);
                });

            return deferred.promise;
        }
    }
})();
