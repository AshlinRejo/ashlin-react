const defaults = require('@wordpress/scripts/config/webpack.config');
const {resolve} = require("path");

module.exports = {
    ...defaults,
    externals: {
        react: 'React',
        'react-dom': 'ReactDOM',
    },
    output: {
        filename: 'admin.js'
    },
};