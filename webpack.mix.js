let mix = require('laravel-mix');
mix.disableNotifications();

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

mix
    .combine(
        [
            'node_modules/angular/angular.min.js',
            'node_modules/angular-ui-router/release/angular-ui-router.min.js',
            'node_modules/angular-animate/angular-animate.min.js',
            'node_modules/angular-ui-bootstrap/dist/ui-bootstrap-tpls.js',
            'node_modules/angular-ui-notification/dist/angular-ui-notification.min.js',
            'node_modules/angular-table/dist/angular-table.min.js',
            'node_modules/xss/dist/xss.min.js'
        ], 'public/js/library.js'
    )
    .sass('resources/assets/sass/app.scss', 'public/css')
    .combine(
        [
            'resources/assets/js/angular/**/*.js'
        ],
        'public/js/custom.js'
    )
    .copyDirectory('resources/views/angular', 'public/views/angular');
