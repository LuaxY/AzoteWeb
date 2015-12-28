(function () {
    'use strict';
    var controllerId = 'login';
    angular.module('app').controller(controllerId, ['$location', 'common', 'authService', login]);

    function login($location, common, authService) {
        var getLogFn = common.logger.getLogFn;
        var log = getLogFn(controllerId);
        var logSuccess = getLogFn(controllerId, 'success');
        var logError = getLogFn(controllerId, 'error');
        var logWarning = getLogFn(controllerId, 'warning');

        var vm = this;

        vm.title = 'Connexion';

        vm.messages = {};
        vm.formData = {};

        vm.submitForm = submitForm;

        activate();

        function activate() {
            var promises = [canAccess()];
            common.activateController(promises, controllerId)
                .then(function () { log('Activated Login View'); });
        }

        function canAccess() {
            if (authService.isLogged())
                $location.path('/');
        }

        function submitForm(isValid) {
            vm.messages = {};

            if (!isValid) {
                vm.messages.danger = "Attention, il y a des erreurs dans le formulaire !";
                return;
            }

            if (authService.isLogged()) {
                vm.messages.warning = "Vous êtes déjà connecté";
                return;
            }  

            return authService.login(vm.formData)
                .then(function (data) {
                    common.$timeout(function () {
                        $location.path('/account');
                    }, 3000);
                    
                    vm.messages.success = "Connexion en cours...";
                    logSuccess("Successfuly Logged In");         
                }, function (err) {
                    vm.messages.danger = "Connexion impossible, vérifiez vos informations d'identification.";
                });
        }
    }
})();