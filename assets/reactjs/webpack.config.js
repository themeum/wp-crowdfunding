var path = require('path')

module.exports = {
  mode: 'development',
  entry: [
    path.join(__dirname, 'src/index.js')
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
      }
    ]
  }
}
