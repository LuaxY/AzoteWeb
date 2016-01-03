(function() {
    'use strict';

    var serviceId = 'routeMediator';

    angular.module('app').factory(serviceId, ['$http', '$rootScope', '$location', 'config', 'logger', 'angularLoad', routeMediator]);

    function routeMediator($http, $rootScope, $location, config, logger, angularLoad) {
        var handleRouteChangeError = true;
        var isContentLoaded = false;

        // Define the functions and properties to reveal.
        var service = {
            setRoutingHandlers: setRoutingHandlers
        };

        return service;

        function setRoutingHandlers() {
            updateDocTitle();
            handleRoutingErrors();
            contentLoaded();
        }

        function handleRoutingErrors() {
            $rootScope.$on('$routeChangeError', function(event, current, previous, rejection) {
                if (handleRouteChangeError) {
                    return;
                }

                handleRouteChangeError = true;

                var msg = 'Error routing: ' + (current && current.name) + '. ' + (rejection.msg || '');

                logger.logWarning(msg, current, serviceId, true);

                $location.path('/404');
            });
        }

        function updateDocTitle() {
            $rootScope.$on('$routeChangeSuccess',
                function(event, current, previous) {
                    handleRouteChangeError = false;

                    var title = config.docTitle + (current.title || '');

                    $rootScope.title = title;
                }
            );
        }

        function contentLoaded() {
            $rootScope.$on('$viewContentLoaded', function() {
                if (isContentLoaded)
                    return;

                var scripts = config.lazyLoadingScripts;
                loadScript(scripts, 0);

                isContentLoaded = true;
            });
        }

        function loadScript(scripts, index) {
            var script = scripts[index];

            angularLoad.loadScript(script).then(function() {
                logger.logSuccess('Succes load: ' + script);

                if (scripts.length - 1 > index) {
                    loadScript(scripts, index + 1);
                }
            }).catch(function() {
                var msg = 'Error loading script: ' + script;
                logger.logError(msg, script, serviceId, true);
            });
        }
    }
})();
