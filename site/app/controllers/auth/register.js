(function () {
    'use strict';
    var controllerId = 'register';
    angular.module('app').controller(controllerId, ['$location', 'common', 'authService', 'vcRecaptchaService', register]);

    function register($location, common, authService, vcRecaptchaService) {
        var getLogFn = common.logger.getLogFn;
        var log = getLogFn(controllerId);

        var vm = this;

        vm.formData = {};

        vm.title = 'Inscription';

        vm.submitForm = submitForm;

        activate();

        function activate() {
            var promises = [canAccess()];
            common.activateController(promises, controllerId)
                .then(function () { log('Activated Register View'); });
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
                vm.messages.warning = "Vous êtes déjà inscrit";
                return;
            }

            vm.formData.captchaResponse = vcRecaptchaService.getResponse();

            if (vm.formData.captcha == "") {
                vm.messages.captcha = "Le captcha est invalide, merci de réessayer !";
                return;
            }

            return authService.register(vm.formData)
                .then(function (result) {
                    common.$timeout(function () {
                        $location.path('/auth/login');
                    }, 3000);

                    vm.messages.success = "Félicitation champion, tu es maintenant inscrit !";
                }, function (result) {
                    vm.messages.danger = "Une erreur s'est produite lors de l'inscription : " + result.message;
                });
        }
    }
})();
