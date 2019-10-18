const webpackMerge = require("webpack-merge")
const Jarvis = require("webpack-jarvis")

const production = require("./webpack.prod")

module.exports = webpackMerge(production, {
  plugins: [
    new Jarvis({
      port: 3333,
      watchOnly: false,
    }),
  ],
})
