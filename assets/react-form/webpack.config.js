var path = require('path')

module.exports = {
  mode: 'development',
  devtool: 'source-map',
  entry: [
      path.join(__dirname, 'src/Index.js'),
  ],
  output: {
    path: path.join(__dirname, '../js/'),
    filename: 'campaign-form.js'
  },
  module: {
    rules: [
      {
        test: /\.(js)$/,
        exclude: /node_modules/,
        use: { loader: 'babel-loader' }
      },
      {
        test: /\.(css)$/,
        use: [
          {
            loader: 'style-loader',
            options: {
              sourceMap: true,
              sourceMapEmbed: true
            }
          },
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
              sourceMapEmbed: true
            }
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true,
              sourceMapEmbed: true
            }
          }
        ]
      },
      {
        test: /\.scss$/,
        use: [
          {
            loader: 'style-loader',
            options: {
              sourceMap: true,
              sourceMapEmbed: true
            }
          },
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
              sourceMapEmbed: true
            }
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true,
              sourceMapEmbed: true
            }
          }
        ]
      }
    ]
  }
}
