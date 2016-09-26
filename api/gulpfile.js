var elixir    = require('laravel-elixir');
//var stylus    = require('laravel-elixir-stylus');
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

    //mix.stylus('*.styl', 'public/css', {
        //use: [ bootstrap() ],
        //'compress': true,
        /*style: "nested",
        sass: "resources/assets/sass",
        relative: true,
        comments: false,
        image: "public/imgs",
        javascript: "public/js",
        font: "public/fonts",*/
    //});

    mix.styles('404.css');
    mix.styles('common.css');
    mix.styles('download.css');
    mix.styles('encyclopedia.css');
    mix.styles('flags.css');
    mix.styles('homepage.css');
    mix.styles('play.css');
    mix.styles('shop.css');
    mix.styles('vote.css');
    mix.styles('ladder.css');
    mix.styles('ladder-tiny.css');
    mix.styles('server.css');

    mix.scripts('common.js');
    mix.scripts('jquery-2.1.4.min.js');
    mix.scripts('jquery-ui.min.js');

    /*
    |--------------------------------------------------------------------------
    | Admin Styles, Scripts and Other plugins
    |--------------------------------------------------------------------------
    */

    /* ---------------------STYLES----------------------- */

    // BOOTSTRAP //
    mix.styles('admin/bootstrap.css', 'public/css/bootstrap.min.css');

    // ADMIN THEME //
    mix.styles([
        'admin/core.css',
        'admin/components.css',
        'admin/icons.css',
        'admin/pages.css',
        'admin/menu.css',
        'admin/responsive.css'
    ], 'public/css/vendor_admin.min.css');
    // MY CSS FILE //
    mix.styles('admin/app.css', 'public/css/app_admin.min.css');

    /* ---------------------SCRIPTS----------------------- */

    // MODERNIZR //
    mix.scripts('admin/modernizr.min.js', 'public/js/admin');

    // PLUGINS THEME //
    mix.scripts([
        'admin/bootstrap.min.js',
        'admin/detect.js',
        'admin/fastclick.js',
        'admin/jquery.slimscroll.js',
        'admin/jquery.blockUI.js',
        'admin/waves.js',
        'admin/jquery.nicescroll.js',
        'admin/jquery.scrollTo.min.js'
    ], 'public/js/admin/vendor_admin.min.js');

    // ADMIN THEME //
    mix.scripts([
        'admin/jquery.app.js',
        'admin/jquery.core.js'
    ], 'public/js/admin/app_admin.min.js');

    /* ---------------------OTHER PLUGINS----------------------- */

    // TOASTR //
    mix.styles('admin/toastr/toastr.min.css', 'public/css');
    mix.scripts('admin/toastr/toastr.min.js', 'public/js/admin');

    // BROWSE SERVER //
    mix.scripts('admin/browseserver.js', 'public/js/admin/browseserver.min.js');

    // DATATABLES //
    mix.styles([
        'admin/datatables/jquery.dataTables.min.css',
        'admin/datatables/buttons.bootstrap.min.css',
        'admin/datatables/fixedHeader.bootstrap.min.css',
        'admin/datatables/scroller.bootstrap.min.css',
        'admin/datatables/responsive.bootstrap.min.css',
    ], 'public/css/vendor_admin_datatables.min.css');
    mix.scripts([
        'admin/datatables/jquery.dataTables.min.js',
        'admin/datatables/dataTables.bootstrap.js',
        'admin/datatables/dataTables.buttons.min.js',
        'admin/datatables/buttons.bootstrap.min.js',
        'admin/datatables/dataTables.fixedHeader.min.js',
        'admin/datatables/dataTables.keyTable.min.js',
        'admin/datatables/dataTables.responsive.min.js',
        'admin/datatables/responsive.bootstrap.min.js',
        'admin/datatables/dataTables.scroller.min.js',
    ], 'public/js/admin/vendor_admin_datatables.min.js');

    // SWEET ALERT //
    mix.styles('./vendor/bower_components/sweetalert/dist/sweetalert.css', 'public/css/sweetalert.min.css');
    mix.scripts('./vendor/bower_components/sweetalert/dist/sweetalert.min.js', 'public/js/admin');

    // DROPIFY //
    mix.styles('./vendor/bower_components/dropify/dist/css/dropify.css', 'public/css/dropify.min.css');
    mix.scripts('./vendor/bower_components/dropify/dist/js/dropify.min.js', 'public/js/admin');

    // DATETIME PICKER //
    mix.styles('./vendor/bower_components/datetimepicker/build/jquery.datetimepicker.min.css', 'public/css');
    mix.scripts('./vendor/bower_components/datetimepicker/build/jquery.datetimepicker.full.min.js', 'public/js/admin');

    // PICK A COLOR (DEPENDENCE TINYCOLOR) //
    mix.less('./vendor/bower_components/pick-a-color/src/less/pick-a-color.less', 'public/css/pick-a-color.min.css');
    mix.scripts([
        './vendor/bower_components/tinycolor/dist/tinycolor-min.js',
        './vendor/bower_components/pick-a-color/src/js/pick-a-color.js',
    ], 'public/js/admin/pick-a-color.min.js');

    // BOOTSTRAP TOUCHSPIN //
    mix.styles('./vendor/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css', 'public/css');
    mix.scripts('./vendor/bower_components/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.js', 'public/js/admin');

    // DRAGULA (DRAG AND DROP) //
    mix.styles('./vendor/bower_components/dragula/dist/dragula.css', 'public/css/dragula.min.css');
    mix.scripts('./vendor/bower_components/dragula/dist/dragula.min.js', 'public/js/admin');
});
