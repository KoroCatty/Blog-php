// webpack.mix.js

let mix = require('laravel-mix');

// versioning so that you don't have to super reload each time
mix.version();

mix.js('src/javascript/app.js', 'dist').setPublicPath('dist');

//sass
mix.sass('src/sass/app.scss', 'dist').version();

// config
mix.webpackConfig({
  stats: {
      children: true,
  },
});

//scssでbg-imgが表示されない時
mix.options({
  processCssUrls: false,
})