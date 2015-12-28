(function () {
    'use strict';

    var app = angular.module('app', [
        // Angular modules
        'ngAnimate',        // animations
        'ngRoute',          // routing
        'ngSanitize',       // sanitizes html bindings (ex: sidebar.js)
        'ngMessages',

        // Custom modules
        'common',           // common functions, logger, spinner

        // 3rd Party Modules
        'ui.bootstrap',       // ui-bootstrap (ex: carousel, pagination, dialog)
        'angularLoad',
        'webStorageModule',
        'angular-loading-bar',
        'ui.gravatar',
        'vcRecaptcha'
    ]);

    // Handle routing errors and success events
    // Trigger breeze configuration
    app.run(['$route', 'routeMediator',
        function ($route, routeMediator) {
            // Include $route to kick start the router.

            routeMediator.setRoutingHandlers();
    }]);

    app.filter('trusted', ['$sce', function ($sce) {
        return function(url) {
            return $sce.trustAsResourceUrl(url);
        };
    }]);
})();
