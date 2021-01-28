'use strict';

var path = require('path');
var webpack = require('webpack');

module.exports = {
    mode: 'production',
    entry: {
        'blocks.min': path.join( __dirname, 'reactjs/src/index.js')
    },
    output: {
        path: path.join( __dirname, 'assets/js'),
        filename: '[name].js'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /(node_modules|bower_components)/,
                use: { loader: 'babel-loader' }
            },
            {
                test: /\.scss$/,
                use: [ 'style-loader', 'css-loader', 'sass-loader' ],
            }
        ]
    }
};