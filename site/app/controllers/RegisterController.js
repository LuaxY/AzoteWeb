(function() {
    'use strict';

    var controllerId = 'RegisterController';

    angular.module('app').controller(controllerId, ['$scope', '$location', 'common', 'authService', 'vcRecaptchaService', Register]);

    function Register($scope, $location, common, authService, vcRecaptchaService) {
        var log = common.logger.getLogFn(controllerId);

        $scope.formData = {};

        $scope.title = 'Inscription';

        $scope.submitForm = submitForm;

        activate();

        function activate() {
            var promises = [canAccess()];
            common.activateController(promises, controllerId)
                .then(function() {
                    //log('Activated Register View');
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
                $scope.messages.warning = "Vous êtes déjà inscrit";
                return;
            }

            $scope.formData.captchaResponse = vcRecaptchaService.getResponse();

            if ($scope.formData.captcha == "") {
                $scope.messages.captcha = "Le captcha est invalide, merci de réessayer !";
                return;
            }

            return authService.register($scope.formData)
                .then(function(result) {
                    common.$timeout(function() {
                        $location.path('/auth/login');
                    }, 3000);

                    $scope.messages.success = "Félicitation champion, tu es maintenant inscrit !";
                }, function(error) {
                    $scope.messages.danger = "Une erreur s'est produite lors de l'inscription : " + error.message;
                });
        }
    }
})();
