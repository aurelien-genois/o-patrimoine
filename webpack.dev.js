const path = require("path");
const common = require("./webpack.common");
const { merge } = require("webpack-merge");
const BrowserSyncPlugin = require("browser-sync-webpack-plugin")
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

const theme = process.env.WP_THEME;


const wpThemeDir = `./public/themes/${theme}/`;
const wpThemeDistDir = `./public/themes/${theme}/assets/`;

// TODO to change
const pubThemeDir = `http://localhost:3000/themes/${theme}/assets/`;

// TODO verify tailwind config

module.exports = merge(common, {
  mode: "development",
  watchOptions: {
    ignored: [
      "/node_modules/**", "/vendor/**", "/.docker/**", "/documentation/**"
    ],
    poll:1000, // Check for changes every second
    aggregateTimeout: 300
  },
  devServer: {
    allowedHosts: 'all',
    host: '0.0.0.0',
    port: 8089,
    proxy: {
      '*': 'http://nginx',
      autoRewrite: true,
      changeOrigin: true,
      ignorePath: false,
      secure: false,
    },
    static: {
      directory: path.resolve(__dirname, wpThemeDir),
    },
    devMiddleware : {
      writeToDisk: true,
    },
    compress: true,
    hot: true,
    headers: {
      'Access-Control-Allow-Origin': '*',
      'Access-Control-Allow-Methods': 'GET , POST, PUT, DELETE, PATCH; OPTIONS',
      'Access-Control-Allow-Headers': 'X-Requested-With, content-type, Author',
    },
    client: {
// todo test opatrimoine.test (localhost:80)
    },
  },
  devtool: 'eval',
  output: {
    publicPath: pubThemeDir,
    filename: "[name].bundle.js",
    path: path.resolve(__dirname, wpThemeDistDir),
    hotUpdateChunkFilename: '.[runtime].hot-update.js',
    hotUpdateMainFilename: '.[runtime].hot-update.json',
  },
  optimization: {
    moduleIds: 'named'
  },
  plugins: [
    new BrowserSyncPlugin({
      host: '0.0.0.0',
      port: 3000,
      proxy: 'localhost:8089',
      open: false,
      files: [
        `./public/themes/${theme}/**/*.php`,
      ],
      reloadThrottle: 2000,
      reloadOnRestart: true,
    }, {
      reload: false,
      // injectCss: true,
    }),
    new MiniCssExtractPlugin({
      filename: "[name].css",
    }),
  ],
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          "style-loader", // 4th inject styles into DOM
          "css-loader", // 3rd turns css into commonjs
          {
              loader: 'postcss-loader',
              options: {
                  postcssOptions: {
                      plugins: [
                          'autoprefixer' // 2nd turns CSS and add vendor prefixes to CSS rules
                      ],
                  }
              }
          },
          "sass-loader", // 1st turns sass into css
        ],
      },
    ],
  },
});
