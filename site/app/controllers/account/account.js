(function () {
    'use strict';
    var controllerId = 'account';
    angular.module('app').controller(controllerId, ['$location', 'common', 'authService', 'gameService', account]);

    function account($location, common, authService, gameService) {
        var getLogFn = common.logger.getLogFn;
        var log = getLogFn(controllerId);
        var logSuccess = getLogFn(controllerId, 'success');
        var logError = getLogFn(controllerId, 'error');
        var logWarning = getLogFn(controllerId, 'warning');

        var vm = this;

        vm.profile = {};
        vm.formData = {};
        vm.freeAccountsCount = {};

        vm.title = 'Compte';

        vm.submitForm = submitForm;

        activate();

        function activate() {
            var promises = [canAccess(), loadProfile()];
            common.activateController(promises, controllerId)
                .then(function () { log('Activated Account View'); });
        }

        function canAccess() {
            if (!authService.isLogged())
                $location.path('/auth/login');
        }

        function loadProfile() {
            return gameService.getProfile()
                .then(function (result) {
                    log("Profile Successful");
                    vm.profile = result.profile;
                    vm.formData.firstname = result.profile.firstname;
                    vm.formData.lastname = result.profile.lastname;

                    vm.freeAccountsCount = new Array(4 - vm.profile.gameAccounts.length);
                }, function (result) {
                    log("Profile error " + result.message);
                });
        }

        function submitForm(isValid) {
            vm.messages = {};

            if (!isValid) {
                vm.messages.danger = "Attention, il y a des erreurs dans le formulaire !";
                return;
            }

            return gameService.updateProfile(vm.formData)
                .then(function (result) {
                    vm.profile.firstname = vm.formData.firstname;
                    vm.profile.lastname = vm.formData.lastname;
                    vm.formData.password = '';
                    vm.formData.passwordConfirmation = '';

                    vm.messages.success = "Votre profile a bien été mis à jour !";
                }, function (result) {
                    vm.messages.danger = "Une erreur s'est produite lors de la mise à jour : " + result.message;
                });
        }
    }
})();
