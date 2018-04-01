const Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where all compiled assets will be stored
    .setOutputPath('web/build/')

    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')

    // will create public/build/app.js and public/build/app.css
    .addEntry('app', './assets/js/app.js')

    // allow sass/scss files to be processed
    .enableSassLoader()

    .addStyleEntry('global', './assets/css/global.scss')

    .enableSourceMaps(!Encore.isProduction())

    // empty the outputPath dir before each build
    .cleanupOutputBeforeBuild()
;

Encore.enableSassLoader(function(sassOptions) {}, {
    resolveUrlLoader: false
});

// export the final configuration
module.exports = Encore.getWebpackConfig();
