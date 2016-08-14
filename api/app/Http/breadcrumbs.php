<?php

// Help: http://laravel-breadcrumbs.davejamesmiller.com/en/latest/start.html#install-laravel-breadcrumbs

// Accueil
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('Accueil', route('home'));
});

// Accueil > [page]
Breadcrumbs::register('page', function($breadcrumbs, $page)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push($page);
});

// Accueil > Boutique
Breadcrumbs::register('shop', function($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Boutique', route('shop.payment.country'));
});

// Accueil > Boutique > [page]
Breadcrumbs::register('shop.page', function($breadcrumbs, $page)
{
    $breadcrumbs->parent('shop');
    $breadcrumbs->push($page);
});
