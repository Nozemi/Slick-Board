var webpack             = require('webpack');
var ExtractTextPlugin   = require('extract-text-webpack-plugin');

if (typeof(theme) === 'undefined') {
    var theme = 'slickboard';
}

module.exports = theme = {
    entry: [
        './themes/' + theme + '/_assets/src/js/index.js'
    ],
    output: {
        path: __dirname + '/themes/' + theme + '/_assets/dist',
        filename: 'slickboard.min.js'
    },
    module: {
        loaders: [
            {
                test: /\.scss$/,
                //loader: 'style-loader!css-loader!sass-loader!font-loader?format[]=truetype&format[]=woff&format[]=embedded-opentype',
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: ['css-loader?minimize&sourceMap', 'sass-loader']
                })
            }
        ]
    },
    plugins: [
        new webpack.ProvidePlugin({
            $               : 'jquery',
            jQuery          : 'jquery',
            "window.jQuery" : 'jquery'
        }),
        new ExtractTextPlugin('slickboard.min.css'),
        new webpack.optimize.UglifyJsPlugin({
            compress: { warnings: false }
        })
    ]
};