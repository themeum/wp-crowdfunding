var path = require('path')

module.exports = {
  mode: 'development',
  entry: [
    path.join(__dirname, 'src/Index.js')
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
        use: [ 'style-loader', 'css-loader', 'sass-loader' ],
      },
      {
        test: /\.scss$/,
        use: [ 'style-loader', 'css-loader', 'sass-loader' ],
      }
    ]
  }
}
