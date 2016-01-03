(function() {
    'use strict';

    var controllerId = 'LoginController';

    angular.module('app').controller(controllerId, ['$scope', '$location', 'common', 'authService', Login]);

    function Login($scope, $location, common, authService) {
        var log = common.logger.getLogFn(controllerId);
        var logSuccess = common.logger.getLogFn(controllerId, 'success');

        $scope.title = 'Connexion';

        $scope.messages = {};
        $scope.formData = {};

        $scope.submitForm = submitForm;

        activate();

        function activate() {
            var promises = [canAccess()];
            common.activateController(promises, controllerId)
                .then(function() {
                    //log('Activated Login View');
                });
        }

        function canAccess() {
            if (authService.isLogged())
                $location.path('/');
        }

        function submitForm(isValid) {
            $scope.messages = {};

            if (!isValid) {
                $scope.messages.danger = "Attention, il y a des erreurs dans le formulaire !";
                return;
            }

            if (authService.isLogged()) {
                $scope.messages.warning = "Vous êtes déjà connecté";
                return;
            }

            return authService.login($scope.formData)
                .then(function(result) {
                    common.$timeout(function() {
                        $location.path('/account');
                    }, 3000);

                    $scope.messages.success = "Connexion en cours...";
                    logSuccess("Successfuly Logged In");
                }, function(error) {
                    $scope.messages.danger = "Connexion impossible, vérifiez vos informations d'identification.";
                });
        }
    }
})();
