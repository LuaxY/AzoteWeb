(function () {
    'use strict';

    var controllerId = 'shell';
    angular.module('app').controller(controllerId,
        ['$window', '$q', 'common', shell]);

    function shell($window, $q, common) {
        var vm = this;

        var logSuccess = common.logger.getLogFn(controllerId, 'success');

        activate();

        function activate() {
            var promises = [];
            common.activateController(promises, controllerId);

            logSuccess('Arkalys Website loaded!', null, true);
        }
    };
})();