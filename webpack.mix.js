const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    
    .combine([
        'node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css',
        'node_modules/admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css',
        'node_modules/admin-lte/dist/css/adminlte.min.css',
    ], 'public/css/login.css')

    .combine([
        'node_modules/admin-lte/plugins/jquery/jquery.min.js',
        'node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js',
        'node_modules/admin-lte/dist/js/adminlte.min.js',
    ], 'public/js/login.js')

    .combine([
        'node_modules/admin-lte/plugins/fontawesome-free/css/all.min.css',
        'node_modules/admin-lte/dist/css/adminlte.min.css',
        'node_modules/admin-lte/plugins/toastr/toastr.min.css',
    ], 'public/css/consultant.css')
    .copy('node_modules/admin-lte/plugins/fontawesome-free/webfonts', 'public/webfonts')

    .combine([
        'node_modules/admin-lte/plugins/jquery/jquery.min.js',
        'node_modules/admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js',
        'node_modules/admin-lte/dist/js/adminlte.min.js',
        'node_modules/admin-lte/plugins/toastr/toastr.min.js',
        'resources/assets/global.js'
    ], 'public/js/consultant.js')
    
    .version();
