'use strict';
var path = require('path');

module.exports = {
	mode: 'development',
	entry: [path.join(__dirname, 'reactjs/src/index.js')],
	output: {
		path: path.join(__dirname, 'assets/js/'),
		filename: 'blocks.min.js',
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /(node_modules|bower_components)/,
				use: { loader: 'babel-loader' },
			},
			{
				test: /\.scss$/,
				use: ['style-loader', 'css-loader', 'sass-loader'],
			},
		],
	},
	devtool: 'source-map',
};
