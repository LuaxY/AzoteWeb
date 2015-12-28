(function () {
    'use strict';
    var controllerId = 'gameaccount';
    angular.module('app').controller(controllerId, ['$location', '$routeParams', 'common', 'authService', 'gameService', '$sce', gameaccount]);

    function gameaccount($location, $routeParams, common, authService, gameService) {
        var getLogFn = common.logger.getLogFn;
        var log = getLogFn(controllerId);
        var logSuccess = getLogFn(controllerId, 'success');
        var logError = getLogFn(controllerId, 'error');
        var logWarning = getLogFn(controllerId, 'warning');

        var vm = this;

        vm.gameAccounts = {};
        vm.formData = {};

        vm.gameAccount = null;
        vm.selectedAccount = null;

        vm.title = '';

        vm.changeAccount = changeAccount;
        vm.submitUpdateForm = submitUpdateForm;
        vm.submitCreateForm = submitCreateForm;

        activate();

        function activate() {
            var promises = [canAccess(), loadGameAccount()];
            common.activateController(promises, controllerId)
                .then(function () { log('Activated Account View'); });
        }

        function canAccess() {
            if (!authService.isLogged())
                $location.path('/auth/login');
        }

        function loadGameAccount() {
            if ($routeParams.id == 'new') {
                vm.title = 'Nouveau Compte';
                return;
            }

            return gameService.getProfile()
                .then(function (result) {
                    vm.gameAccounts = result.profile.gameAccounts;

                    angular.forEach(result.profile.gameAccounts, function (value, key) {
                        if (value.id == $routeParams.id) {
                            vm.gameAccount = value;
                            vm.title = value.login;

                            gameService.getCharacters(value.id)
                                .then(function (result) {
                                    vm.gameAccount.characters = result.characters;
                                }, function (err) {
                                    vm.gameAccount.characters = [];
                                });
                        }
                    }, log);
                }, function (err) {
                });
        }

        /*function loadCharactersLook(index) {
            if (vm.gameAccount.characters.length - 1 < index)
                return;

            var character = vm.gameAccount.characters[index];

            gameService.getCharacterLook(character.id)
                .then(function (data) {
                    character.lookLink = data;
                    loadCharactersLook(index + 1);
                }, function (err) {
                });
        }*/

        function changeAccount() {
            $location.path('/account/game/' + vm.selectedAccount.id);
        }

        function submitUpdateForm(isValid) {
            vm.messages = {};

            if (!isValid) {
                vm.messages.danger = "Attention, il y a des erreurs dans le formulaire !";
                return;
            }

            vm.formData.id = vm.gameAccount.id;

            return gameService.updateGameAccount(vm.formData)
                .then(function (result) {
                    vm.messages.success = "Votre mot de passe a bien été modifié !";
                }, function (err) {
                    vm.messages.danger = "Une erreur s'est produite lors de la modification";
                });
        }

        function submitCreateForm(isValid) {
            vm.messages = {};

            if (!isValid) {
                vm.messages.danger = "Attention, il y a des erreurs dans le formulaire !";
                return;
            }

            return gameService.createGameAccount(vm.formData)
                .then(function (result) {
                    common.$timeout(function () {
                        $location.path('/account/game/' + result.id);
                    }, 3000);

                    vm.messages.success = "Votre compte a bien été crée !";
                }, function (err) {
                    vm.messages.danger = "Une erreur s'est produite lors de la création du compte";
                });
        }
    }
})();
