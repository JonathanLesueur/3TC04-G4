const path = require('path');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const SpriteLoaderPlugin = require("svg-sprite-loader/plugin");

module.exports = {
    plugins: [new MiniCssExtractPlugin()],
    entry: path.resolve(__dirname, 'dev') + "/index.js",
    output: {
        filename: "js/script.js",
        path: path.resolve('integration','static')
    },
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                use: [
                    {
                        loader: MiniCssExtractPlugin.loader
                    },
                    {
                        loader: "css-loader",
                        options: {
                            sourceMap: true
                        }
                    },
                    {
                        loader: "sass-loader",
                        options: {
                            sourceMap: true
                        }
                    }
                ]
            },
            {
                test: /\.(woff(2)?|ttf|eot)(\?v=\d+\.\d+\.\d+)?$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'fonts/',
                            publicPath: '../fonts'
                        }
                    }
                ]
            },
            {
                test: /\.(png|jpe?g|gif)$/i,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[name].[ext]',
                            outputPath: 'imgs/',
                            publicPath: '../imgs'
                        }
                    },
                ],
            },
            {
                test: /\.svg$/i, // your icons directory
                loader: 'svg-sprite-loader',
                options: {
                  extract: true,
                  spriteFilename: './imgs/sprite.svg', // this is the destination of your sprite sheet
                }
            }
        ]
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: "css/style.css",
            chunkFilename: "[id].css"
        }),
        new SpriteLoaderPlugin({
            plainSprite: true
        })
    ]
};
