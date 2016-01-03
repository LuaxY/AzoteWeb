(function() {
    'use strict';

    var controllerId = 'ShopController';

    angular.module('app').controller(controllerId, ['$scope', '$location', '$filter', 'common', 'authService', 'gameService', Shop]);

    function Shop($scope, $location, $filter, common, authService, gameService) {
        var log = common.logger.getLogFn(controllerId);

        $scope.profile = {};
        $scope.rates = {};
        $scope.countries = {};
        $scope.filteredRates = [];
        $scope.paymentMethod = null;
        $scope.paymentMethodTitle = "Non défini";
        $scope.rate = null;
        $scope.country = null;

        $scope.title = 'Boutique';

        $scope.selectPaymentMethod = selectPaymentMethod;
        $scope.selectCountry = selectCountry;

        activate();

        function activate() {
            var promises = [canAccess(), loadProfile(), loadRates()];
            common.activateController(promises, controllerId)
                .then(function() {
                    //log('Activated Shop View');
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
                }, function(error) {
                    //log("Profile Error");
                });
        }

        function loadRates() {
            return gameService.getRates()
                .then(function(result) {
                    //log("Rates Successful");
                    $scope.rates = result;
                    selectPaymentMethod('audiotel');
                }, function(error) {
                    //log("Rates Error");
                });
        }

        function selectPaymentMethod(payment) {
            $scope.paymentMethod = payment;

            switch (payment) {
                case 'audiotel':
                    $scope.paymentMethodTitle = "Audiotel";
                    break;
                case 'sms':
                    $scope.paymentMethodTitle = "SMS";
                    break;
                case 'creditcard':
                    $scope.paymentMethodTitle = "Carte Bancaire";
                    break;
                case 'paypal':
                    $scope.paymentMethodTitle = "Paypal";
                    break;
                case 'neosurf':
                    $scope.paymentMethodTitle = "Neosurf";
                    break;
            }

            $scope.filteredRates = $filter('filter')($scope.rates, {
                solution: $scope.paymentMethodTitle
            });
            $scope.countries = [];
            angular.forEach($scope.filteredRates, function(value, key) {
                value.title = value.user_earns + ' Jetons (' + value.user_price + ' ' + value.user_currency + ')';
                value.instructions = getInstructions(value);

                if (!in_array($scope.countries, value.country.id))
                    $scope.countries.push(value.country);
            });

            selectCountry($scope.countries[0]);
        }

        function selectCountry(selectedCountry) {
            $scope.country = selectedCountry;
            $scope.filteredRates = $filter('filter')($scope.rates, {
                solution: $scope.paymentMethodTitle,
                countryId: selectedCountry.id
            });
            $scope.rate = $scope.filteredRates[0];

            console.log('Change Country');
        }

        function getInstructions(rate) {
            var instructions = "";

            switch (rate.solution) {
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
