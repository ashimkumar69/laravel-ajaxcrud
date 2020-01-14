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
    .styles(
        [
            'resources/assets/css/libs/bootstrap.min.css',
            'resources/assets/css/libs/all.min.css',
            'resources/assets/css/libs/dataTables.bootstrap4.min.css',
        ],
        'public/css/libs.css'
    )
    .scripts(
        [
            'resources/assets/js/libs/jquery-3.4.1.min.js',
            'resources/assets/js/libs/popper.min.js',
            'resources/assets/js/libs/bootstrap.min.js',
            'resources/assets/js/libs/jquery.dataTables.min.js',
            'resources/assets/js/libs/dataTables.bootstrap4.min.js',
        ],
        'public/js/libs.js'
    );
