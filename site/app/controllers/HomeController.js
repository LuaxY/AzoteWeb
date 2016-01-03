(function() {
    'use strict';

    var controllerId = 'HomeController';

    angular.module('app').controller(controllerId, ['$scope', 'common', Home]);

    function Home($scope, common) {
        var log = common.logger.getLogFn(controllerId);

        activate();

        function activate() {
            var promises = [];
            common.activateController(promises, controllerId)
                .then(function() {
                    //log('Activated Home View');
                });
        }
    }
})();
