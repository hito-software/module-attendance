const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('resources/scss/style.scss', 'public/css/style.css')
    .js('resources/js/script.js', 'public/js/script.js')
    .react()
    .webpackConfig({
        optimization: {
            runtimeChunk: true
        }
    })
    .options({
        processCssUrls: false,
        postCss: [require('tailwindcss')],
    });
