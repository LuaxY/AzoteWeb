(function () {
    'use strict';

    var app = angular.module('app');

    // Configure Toastr
    toastr.options.timeOut = 4000;
    toastr.options.positionClass = 'toast-bottom-right';

    var keyCodes = {
        backspace: 8,
        tab: 9,
        enter: 13,
        esc: 27,
        space: 32,
        pageup: 33,
        pagedown: 34,
        end: 35,
        home: 36,
        left: 37,
        up: 38,
        right: 39,
        down: 40,
        insert: 45,
        del: 46
    };

    var lazyLoadingScripts = [
        'Assets/js/theme.js',
        'Assets/js/theme.init.js'
    ];

    var remoteServiceName = 'http://api.local.dev';

    var events = {
        controllerActivateSuccess: 'controller.activateSuccess',
        authLogged: 'auth.logged',
        authDisconnected: 'auth.disconnected',
        authUnauthenticated: 'auth.unauthenticated',
        authUnauthorized: 'auth.unauthorized',
        authSessionTimeout: 'auth.sessionTimeout'
    };

    var config = {
        appErrorPrefix: '[Error] ', //Configure the exceptionHandler decorator
        docTitle: 'ARKALYS | ',
        events: events,
        keyCodes: keyCodes,
        lazyLoadingScripts: lazyLoadingScripts,
        remoteServiceName: remoteServiceName,
        version: '0.0.1'
    };

    app.value('config', config);

    app.config(['$logProvider', function ($logProvider) {
        // turn debugging off/on (no info or warn)
        if ($logProvider.debugEnabled) {
            $logProvider.debugEnabled(true);
        }
    }]);

    //#region Configure the common services via commonConfig
    app.config(['commonConfigProvider', function (cfg) {
        cfg.config.controllerActivateSuccessEvent = config.events.controllerActivateSuccess;
    }]);
    //#endregion

    app.config(['gravatarServiceProvider', function (gravatarServiceProvider) {
        gravatarServiceProvider.secure = true;
    }]);

    app.config(['$locationProvider', function ($locationProvider) {
        $locationProvider.html5Mode(true);
    }]);
})();
