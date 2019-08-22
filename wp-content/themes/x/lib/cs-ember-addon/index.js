/* eslint-env node */
'use strict';

const react = require('broccoli-react');

module.exports = {
  name: 'cs-ember-addon',

  preprocessTree: function(type, tree) {
    debugger
    if (type === 'js') {

      tree = react(tree, { transform: { es6module: true } });
    }

    return tree;
  },

  options: {
    autoImport: {
      devtool: 'inline-source-map',
    },
  },
};
