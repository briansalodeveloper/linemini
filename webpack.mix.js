const mix = require('laravel-mix');
const { CleanWebpackPlugin } = require('clean-webpack-plugin');
require('dotenv').config()

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
const cleanPatterns = [
   '**assets/*',
   '**fonts/vendor/*'
];

mix.webpackConfig({
   plugins: [
      new CleanWebpackPlugin({
         cleanOnceBeforeBuildPatterns: cleanPatterns
      })
   ],
});

mix.copyDirectory('node_modules/prismjs', 'public/assets/prismjs');
mix.copyDirectory('node_modules/trumbowyg/dist', 'public/assets/trumbowyg');
mix.copyDirectory('node_modules/admin-lte/plugins/ekko-lightbox', 'public/assets/admin-lte/plugins/ekko-lightbox');
mix.copyDirectory('node_modules/admin-lte/plugins/moment', 'public/assets/admin-lte/plugins/moment');
mix.copyDirectory('node_modules/admin-lte/plugins/icheck-bootstrap', 'public/assets/admin-lte/plugins/icheck-bootstrap');
mix.copyDirectory('node_modules/admin-lte/plugins/select2', 'public/assets/admin-lte/plugins/select2');
mix.copyDirectory('node_modules/admin-lte/plugins/select2-bootstrap4-theme', 'public/assets/admin-lte/plugins/select2-bootstrap4-theme');
mix.copyDirectory('node_modules/admin-lte/plugins/bootstrap-switch', 'public/assets/admin-lte/plugins/bootstrap-switch');
mix.copyDirectory('node_modules/tempusdominus-bootstrap-4/build', 'public/assets/tempusdominus-bootstrap-4');

mix.js('resources/js/compiled.js', 'public/js')
   .postCss('resources/css/compiled.css', 'public/css')
   .options({
      processCssUrls: true
   })
   .setResourceRoot('/' + process.env.APP_DIR)
   .version();
