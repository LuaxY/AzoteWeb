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
