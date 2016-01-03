(function () {
    'use strict';

    var controllerId = 'MasterController';

    angular.module('app').controller(controllerId, ['$scope', '$window', '$q', 'common', Master]);

    function Master($scope, $window, $q, common) {
        var log = common.logger.getLogFn(controllerId);

        activate();

        function activate() {
            var promises = [];
            common.activateController(promises, controllerId)
                .then(function() {
                    //log('Arkalys Website loaded!');
                });
        }
    };
})();
