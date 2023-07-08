// ! not used
// webpack.dev.js renamed webpack.config.js
const path = require("path");
const webpack  = require('webpack');
const dotenv = require('dotenv').config( {
  path: path.join(__dirname, '.env')
} );
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

const theme = process.env.WP_THEME;

const wpThemeJsDir = `./resources/js/`;
const wpThemeFontsDir = `./public/themes/${theme}/assets/fonts/`;

module.exports = {
  entry: {
    app: `${wpThemeJsDir}app.js`,
    vendor: `${wpThemeJsDir}vendor.js`,
  },
  plugins: [
    // Automatically load modules instead of having to import or require them everywhere
    new webpack.ProvidePlugin({
      $: 'jquery',
      jQuery: 'jquery',
      tailwindcss: 'tailwindcss',
  }),
  // replaces variables in your code with other values or expressions at compile time
    new webpack.DefinePlugin({
      "process.env": dotenv.parsed
    }),
    // By default, this plugin will remove all files inside webpack's output.path directory
    new CleanWebpackPlugin(
      // TODO options
    ),
  ],
  module: {
    rules: [
      {
        // put fonts in wpThemeFontsDir
          test: /\.(eot|woff|woff2|ttf|svg)(\?\S*)?$/,
          use:[
              {
                  loader: 'file-loader',
                  options: {
                      name: '[name].[ext]',
                      publicPath: wpThemeFontsDir,
                      // This should only be needed when custom 'publicPath' is specified to match the folder structure there
                      // outputPath: wpThemeFontsDir,
                  }
              }
          ]
      },
      {
        test: /\.(svg|png|jpe?g|gif)$/i,
        exclude: /(fonts)/,
        use: [
          {
            loader: "file-loader", // Image loader for webpack
            options: {
              name: "[name].[hash].[ext]",
              outputPath: "images",
            },
          },
          {
            loader: "image-webpack-loader", // Image optimize
            options: {
              mozjpeg: {
                progressive: true,
                quality: 65,
              },
              // optipng.enabled: false will disable optipng
              optipng: {
                enabled: false,
              },
              pngquant: {
                quality: [0.65, 0.9],
                speed: 4,
              },
              gifsicle: {
                interlaced: false,
              },
              // the webp option will enable WEBP
              webp: {
                quality: 75,
              },
            },
          },
        ],
      },
      {
        test: /\.m?js$/,
        exclude: /(node_modules)/,
        use: {
          loader: "babel-loader",
          options: {
            presets: ["@babel/preset-env"],
          },
        },
      },
    ],
  },
};
