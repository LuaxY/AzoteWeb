(function () {
    'use strict';

    angular.module('app').directive('compareTo', function () {
        return {
            require: "ngModel",
            scope: {
                otherModelValue: "=compareTo"
            },
            link: function (scope, element, attributes, ngModel) {

                ngModel.$validators.compareTo = function (modelValue) {
                    return modelValue == scope.otherModelValue;
                };

                scope.$watch("otherModelValue", function () {
                    ngModel.$validate();
                });
            }
        };
    });

    angular.module('app').directive('inequalTo', function () {
        return {
            require: "ngModel",
            scope: {
                otherModelValue: "=inequalTo"
            },
            link: function (scope, element, attributes, ngModel) {

                ngModel.$validators.inequalTo = function (modelValue) {
                    return modelValue != scope.otherModelValue;
                };

                scope.$watch("otherModelValue", function () {
                    ngModel.$validate();
                });
            }
        };
    });
})();