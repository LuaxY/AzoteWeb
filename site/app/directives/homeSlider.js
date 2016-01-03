(function() {
    'use strict';

    angular.module('app').directive('homeslider', function() {
        return {
            restrict: 'EA',
            templateUrl: 'app/views/home/slider.html'
        };
    });
})();
