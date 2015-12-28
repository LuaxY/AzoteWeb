(function () {
    'use strict';
    var controllerId = 'shop';
    angular.module('app').controller(controllerId, ['$location', '$filter', 'common', 'authService', 'gameService', shop]);

    function shop($location, $filter, common, authService, gameService) {
        var getLogFn = common.logger.getLogFn;
        var log = getLogFn(controllerId);
        var logSuccess = getLogFn(controllerId, 'success');
        var logError = getLogFn(controllerId, 'error');
        var logWarning = getLogFn(controllerId, 'warning');

        var vm = this;

        vm.profile = {};
        vm.rates = {};
        vm.countries = {};
        vm.filteredRates = [];
        vm.paymentMethod = null;
        vm.paymentMethodTitle = "Non défini";
        vm.rate = null;
        vm.country = null;

        vm.title = 'Boutique';

        vm.selectPaymentMethod = selectPaymentMethod;
        vm.selectCountry = selectCountry;

        activate();

        function activate() {
            var promises = [canAccess(), loadProfile(), loadRates()];
            common.activateController(promises, controllerId)
                .then(function () { log('Activated Shop View'); });
        }

        function canAccess() {
            if (!authService.isLogged())
                $location.path('/auth/login');
        }

        function loadProfile() {
            return gameService.getProfile()
                .then(function (data) {
                    log("Profile Successful");
                    vm.profile = data;
                }, function (err) {
                    log("Profile Error");
                });
        }

        function loadRates() {
            return gameService.getRates()
                .then(function (data) {
                    log("Rates Successful");
                    vm.rates = data;
                    selectPaymentMethod('audiotel');
                }, function (err) {
                    log("Rates Error");
                });
        }

        function selectPaymentMethod(payment) {
            vm.paymentMethod = payment;

            switch(payment) {
                case 'audiotel':
                    vm.paymentMethodTitle = "Audiotel";
                    break;
                case 'sms':
                    vm.paymentMethodTitle = "SMS";
                    break;
                case 'creditcard':
                    vm.paymentMethodTitle = "Carte Bancaire";
                    break;
                case 'paypal':
                    vm.paymentMethodTitle = "Paypal";
                    break;
                case 'neosurf':
                    vm.paymentMethodTitle = "Neosurf";
                    break;
            }
            
            vm.filteredRates = $filter('filter')(vm.rates, { solution: vm.paymentMethodTitle });
            vm.countries = [];
            angular.forEach(vm.filteredRates, function (value, key) {
                value.title = value.user_earns + ' Jetons (' + value.user_price + ' ' + value.user_currency + ')';
                value.instructions = getInstructions(value);

                if (!in_array(vm.countries, value.country.id))
                    vm.countries.push(value.country);
            });

            selectCountry(vm.countries[0]);
        }

        function selectCountry(selectedCountry) {
            vm.country = selectedCountry;
            vm.filteredRates = $filter('filter')(vm.rates, { solution: vm.paymentMethodTitle, countryId: selectedCountry.id });
            vm.rate = vm.filteredRates[0];

            console.log('Change Country');
        }

        function getInstructions(rate) {
            var instructions = "";

            switch (rate.solution)
            {
                case 'Audiotel':
                    instructions = "Téléphonez au: <b>" + rate.phone + "</b> - " + rate.mention;
                    break;
                case 'SMS':
                    instructions = "Envoi: <b>" + rate.keyword + "</b> au <b>" + rate.shortcode + "</b>";
                    instructions += "<span class='legal_graphic'>" + rate.legal_graphic.shortcode + "</span><br>" + rate.mention;
                    instructions += "<br><br>" + rate.legal_graphic.footer;
                    break;
                case 'Carte Bancaire':
                    break;
                case 'Paypal':
                    break;
                case 'Neosurf':
                    break;
            }

            return instructions;
        }

        function in_array(array, id) {
            for (var i = 0; i < array.length; i++) {
                if (angular.equals(array[i].id, id)) {
                    return true;
                }
            }
            return false;
        }
    }
})();