(function() {
    'use strict';

    var controllerId = 'GameAccountController';

    angular.module('app').controller(controllerId, ['$scope', '$location', '$routeParams', 'common', 'authService', 'gameService', '$sce', GameAccount]);

    function GameAccount($scope, $location, $routeParams, common, authService, gameService) {
        var log = common.logger.getLogFn(controllerId);

        $scope.gameAccounts = {};
        $scope.formData = {};

        $scope.gameAccount = null;
        $scope.selectedAccount = null;

        $scope.title = '';

        $scope.changeAccount = changeAccount;
        $scope.submitUpdateForm = submitUpdateForm;
        $scope.submitCreateForm = submitCreateForm;

        activate();

        function activate() {
            var promises = [canAccess(), loadGameAccount()];
            common.activateController(promises, controllerId)
                .then(function() {
                    //log('Activated Account View');
                });
        }

        function canAccess() {
            if (!authService.isLogged())
                $location.path('/auth/login');
        }

        function loadGameAccount() {
            if ($routeParams.id == 'new') {
                $scope.title = 'Nouveau Compte';
                return;
            }

            return gameService.getProfile()
                .then(function(result) {
                    $scope.gameAccounts = result.profile.gameAccounts;

                    angular.forEach(result.profile.gameAccounts, function(value, key) {
                        if (value.id == $routeParams.id) {
                            $scope.gameAccount = value;
                            $scope.title = value.login;

                            gameService.getCharacters(value.id)
                                .then(function(result) {
                                    $scope.gameAccount.characters = result.characters;
                                }, function(error) {
                                    $scope.gameAccount.characters = [];
                                });
                        }
                    }, log);
                }, function(error) {

                });
        }

        /*function loadCharactersLook(index) {
            if ($scope.gameAccount.characters.length - 1 < index)
                return;

            var character = $scope.gameAccount.characters[index];

            gameService.getCharacterLook(character.id)
                .then(function (data) {
                    character.lookLink = data;
                    loadCharactersLook(index + 1);
                }, function (err) {
                });
        }*/

        function changeAccount() {
            $location.path('/account/game/' + $scope.selectedAccount.id);
        }

        function submitUpdateForm(isValid) {
            $scope.messages = {};

            if (!isValid) {
                $scope.messages.danger = "Attention, il y a des erreurs dans le formulaire !";
                return;
            }

            $scope.formData.id = $scope.gameAccount.id;

            return gameService.updateGameAccount($scope.formData)
                .then(function(result) {
                    $scope.messages.success = "Votre mot de passe a bien été modifié !";
                }, function(error) {
                    $scope.messages.danger = "Une erreur s'est produite lors de la modification";
                });
        }

        function submitCreateForm(isValid) {
            $scope.messages = {};

            if (!isValid) {
                $scope.messages.danger = "Attention, il y a des erreurs dans le formulaire !";
                return;
            }

            return gameService.createGameAccount($scope.formData)
                .then(function(result) {
                    common.$timeout(function() {
                        $location.path('/account/game/' + result.id);
                    }, 3000);

                    $scope.messages.success = "Votre compte a bien été crée !";
                }, function(error) {
                    $scope.messages.danger = "Une erreur s'est produite lors de la création du compte";
                });
        }
    }
})();
