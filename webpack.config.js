const path = require("path");
const webpack = require('webpack');
const dotenv = require('dotenv').config({
    path: path.join(__dirname, '.env')
});
const BrowserSyncPlugin = require("browser-sync-webpack-plugin")
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const TerserWebpackPlugin = require("terser-webpack-plugin");
const { CleanWebpackPlugin } = require("clean-webpack-plugin");

const theme = process.env.WP_THEME;
// NODE_ENV defined in npm script command, can also use WP_ENV defined in .env
const devmode = process.env.NODE_ENV !== "production";

const resourcesJsDir = `./resources/js`;
const wpThemeFontsDir = `./themes/${theme}/assets/fonts`;
const wpThemeDir = `./themes/${theme}/`;
const wpThemeDistDir = `./themes/${theme}/assets/`;

const pubThemeDir = `http://localhost:3000/themes/${theme}/assets/`;

// TODO verify tailwind config

module.exports = {
    mode: devmode ? "development" : "production",
    entry: {
        app: `${resourcesJsDir}/app.js`,
        vendor: `${resourcesJsDir}/vendor.js`,
    },
    watchOptions: {
        ignored: [
            "/node_modules/**", "/vendor/**", "/.docker/**", "/documentation/**"
        ],
        poll: 1000, // Check for changes every second
        aggregateTimeout: 300
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
        minimizer: [
            new TerserWebpackPlugin(),
        ],
    },
    devServer: {
        allowedHosts: 'all',
        host: 'localhost',
        port: 8089,
        liveReload: true,
        hot: true,
        // proxy: {
        //   '*': 'http://nginx',
        //   autoRewrite: true,
        //   changeOrigin: true,
        //   ignorePath: false,
        //   secure: false,
        // },
        static: {
            directory: path.resolve(__dirname, wpThemeDir),
        },
        devMiddleware: {
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
        },
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
        new BrowserSyncPlugin({
            host: 'localhost',
            port: 3000,
            https: {
                key: process.env.SSL_KEY_PATH,
                cert: process.env.SSL_CERT_PATH,
            },
            proxy: 'localhost:8089',
            open: false,
            files: [
                `./themes/${theme}/**/*.php`,
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
                // put fonts in wpThemeFontsDir
                test: /\.(eot|woff|woff2|ttf|svg)(\?\S*)?$/,
                use: [
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
            {
                test: /\.scss$/,
                use: [
                    devmode ? "style-loader" : MiniCssExtractPlugin.loader,
                    // MiniCssExtractPlugin.loader = 4th extract css into files
                    // "style-loader = 4th inject styles into DOM
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
};
