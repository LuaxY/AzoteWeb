var elixir    = require('laravel-elixir');
var stylus    = require('laravel-elixir-stylus');
var bootstrap = require('bootstrap-styl');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {

    //mix.sass('app.scss');

    mix.stylus('*.styl', 'public/css', {
        use: [ bootstrap() ],
        //'compress': true,
        /*style: "nested",
        sass: "resources/assets/sass",
        relative: true,
        comments: false,
        image: "public/imgs",
        javascript: "public/js",
        font: "public/fonts",*/
    });

});
