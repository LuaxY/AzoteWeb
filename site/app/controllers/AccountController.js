(function() {
    'use strict';

    var controllerId = 'AccountController';

    angular.module('app').controller(controllerId, ['$scope', '$location', 'common', 'authService', 'gameService', Account]);

    function Account($scope, $location, common, authService, gameService) {
        var log = common.logger.getLogFn(controllerId);

        $scope.profile = {};
        $scope.formData = {};
        $scope.freeAccountsCount = {};

        $scope.title = 'Compte';

        $scope.submitForm = submitForm;

        activate();

        function activate() {
            var promises = [canAccess(), loadProfile()];
            common.activateController(promises, controllerId)
                .then(function() {
                    //log('Activated Account View');
                });
        }

        function canAccess() {
            if (!authService.isLogged())
                $location.path('/auth/login');
        }

        function loadProfile() {
            return gameService.getProfile()
                .then(function(result) {
                    //log("Profile Successful");
                    $scope.profile = result.profile;
                    $scope.formData.firstname = result.profile.firstname;
                    $scope.formData.lastname = result.profile.lastname;

                    $scope.freeAccountsCount = new Array(4 - $scope.profile.gameAccounts.length);
                }, function(error) {
                    log("Profile error " + error.message);
                });
        }

        function submitForm(isValid) {
            $scope.messages = {};

            if (!isValid) {
                $scope.messages.danger = "Attention, il y a des erreurs dans le formulaire !";
                return;
            }

            return gameService.updateProfile($scope.formData)
                .then(function(result) {
                    $scope.profile.firstname = $scope.formData.firstname;
                    $scope.profile.lastname = $scope.formData.lastname;
                    $scope.formData.password = '';
                    $scope.formData.passwordConfirmation = '';

                    $scope.messages.success = "Votre profile a bien été mis à jour !";
                }, function(error) {
                    $scope.messages.danger = "Une erreur s'est produite lors de la mise à jour : " + error.message;
                });
        }
    }
})();
