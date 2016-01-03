(function() {
    'use strict';

    var app = angular.module('app');

    // Collect the routes
    app.constant('routes', getRoutes());

    // Configure the routes and route resolvers
    app.config(['$routeProvider', 'routes', routeConfigurator]);

    function routeConfigurator($routeProvider, routes) {
        routes.forEach(function(r) {
            setRoute(r.url, r.config);
        });

        $routeProvider.otherwise({
            redirectTo: '/404'
        });

        function setRoute(url, definition) {
            definition.resolve = angular.extend(definition.resolve || {}, {
                prime: prime
            });

            $routeProvider.when(url, definition);
        }
    }

    function prime() {}

    // Define the routes
    function getRoutes() {
        return [{
            url: '/',
            config: {
                templateUrl: 'app/views/home/home.html',
                controller: 'HomeController',
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
                templateUrl: 'app/views/errors/404.html',
                title: 'Page Introuvable'
            }
        },
        {
            url: '/shop',
            config: {
                templateUrl: 'app/views/shop/shop.html',
                controller: 'ShopController',
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
                templateUrl: 'app/views/support/support.html',
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
                templateUrl: 'app/views/vote/vote.html',
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
                templateUrl: 'app/views/auth/login.html',
                controller: 'LoginController',
                title: 'Connexion'
            }
        },
        {
            url: '/auth/register',
            config: {
                templateUrl: 'app/views/auth/register.html',
                controller: 'RegisterController',
                title: 'Inscription'
            }
        },
        {
            url: '/account',
            config: {
                templateUrl: 'app/views/account/account.html',
                controller: 'AccountController',
                title: 'Compte'
            }
        },
        {
            url: '/account/game/:id',
            config: {
                templateUrl: 'app/views/account/gameaccount.html',
                controller: 'GameAccountController',
                title: 'Compte de Jeu'
            }
        }];
    }
})();
