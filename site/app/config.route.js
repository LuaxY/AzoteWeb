(function () {
    'use strict';

    var app = angular.module('app');

    // Collect the routes
    app.constant('routes', getRoutes());

    // Configure the routes and route resolvers
    app.config(['$routeProvider', 'routes', routeConfigurator]);

    function routeConfigurator($routeProvider, routes) {
        routes.forEach(function (r) {
            setRoute(r.url, r.config);
        });

        $routeProvider.otherwise({redirectTo: '/404' });

        function setRoute(url, definition) {
            definition.resolve = angular.extend(definition.resolve || {}, {
                prime: prime
            });

            $routeProvider.when(url, definition);
        }
    }

    function prime() { }

    // Define the routes
    function getRoutes() {
        return [
            {
                url: '/',
                config: {
                    templateUrl: 'app/controllers/home/home.html',
                    controller: 'home',
                    controllerAs: 'vm',
                    title: 'Accueil',
                    settings: {
                        nav: 1,
                        content: '<i class="fa fa-home"></i> Accueil'
                    }
                }
            },
            {
                url: '/404',
                config: {
                    templateUrl: 'app/controllers/404/404.html',
                    title: 'Page Introuvable'
                }
            },
            {
                url: '/shop',
                config: {
                    templateUrl: 'app/controllers/shop/shop.html',
                    controller: 'shop',
                    controllerAs: 'vm',
                    title: 'Boutique',
                    settings: {
                        nav: 2,
                        content: '<i class="fa fa-shopping-cart"></i> Boutique'
                    }
                }
            },
            {
                url: '/support',
                config: {
                    templateUrl: 'app/controllers/support/support.html',
                    title: 'Support',
                    settings: {
                        nav: 3,
                        content: '<i class="fa fa-user-md"></i> Support'
                    }
                }
            },
            {
                url: '/vote',
                config: {
                    templateUrl: 'app/controllers/vote/vote.html',
                    title: 'Vote',
                    settings: {
                        nav: 4,
                        content: '<i class="fa fa-thumbs-o-up"></i> Vote'
                    }
                }
            },
            {
                url: '/auth/login',
                config: {
                    templateUrl: 'app/controllers/auth/login.html',
                    controller: 'login',
                    controllerAs: 'vm',
                    title: 'Connexion'
                }
            },
            {
                url: '/auth/register',
                config: {
                    templateUrl: 'app/controllers/auth/register.html',
                    controller: 'register',
                    controllerAs: 'vm',
                    title: 'Inscription'
                }
            },
            {
                url: '/account',
                config: {
                    templateUrl: 'app/controllers/account/account.html',
                    controller: 'account',
                    controllerAs: 'vm',
                    title: 'Compte'
                }
            },
            {
                url: '/account/game/:id',
                config: {
                    templateUrl: 'app/controllers/account/gameaccount.html',
                    controller: 'gameaccount',
                    controllerAs: 'vm',
                    title: 'Compte de Jeu'
                }
            }
        ];
    }
})();
