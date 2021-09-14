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

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
        require('autoprefixer'),
    ]);

if (mix.inProduction()) {
    mix.version();
}
   mix.js('node_modules/bootstrap4-toggle/js/bootstrap4-toggle.min.js','public/assets/js')
      .css('node_modules/bootstrap4-toggle/css/bootstrap4-toggle.min.css','public/assets/css');

  mix.js('node_modules/echarts/dist/echarts.min.js','public/assets/js')
    .js('node_modules/echarts/dist/echarts.common.min.js','public/assets/js');
