const path = require("path")
const webpack = require("webpack")
const webpackMerge = require("webpack-merge")
const CopyWebpackPlugin = require("copy-webpack-plugin")
const ErrorOverlayPlugin = require("error-overlay-webpack-plugin")
const FileManagerPlugin = require("filemanager-webpack-plugin")

const baseConfig = require("./webpack.common")

module.exports = webpackMerge(baseConfig, {
  mode: "development",
  devtool: "source-map",
  plugins: [
    new ErrorOverlayPlugin(),
    new webpack.NamedModulesPlugin(),
    new CopyWebpackPlugin(
      [
        {
          context: "./src/wordpress",
          from: `${path.resolve(__dirname, "../src/wordpress")}/**/*.php`,
          to: path.resolve(__dirname, "../dist"),
        },
      ],
      {
        copyUnmodified: true, // copy w/ every watch
      },
    ),
    new FileManagerPlugin({
      onEnd: {
        copy: [
          {
            source: path.resolve(__dirname, "../dist-build/css/admin.styles.css.map"),
            destination: path.resolve(__dirname, "../dist/optmedia/Admin/css"),
          },
          {
            source: path.resolve(__dirname, "../dist-build/admin.bundle.js.map"),
            destination: path.resolve(__dirname, "../dist/optmedia/Admin/js"),
          },
          {
            source: path.resolve(__dirname, "../src/wordpress/phpunit.xml"),
            destination: path.resolve(__dirname, "../dist"),
          },
          {
            source: path.resolve(__dirname, "../src/wordpress/tests/resources/*"),
            destination: path.resolve(__dirname, "../dist/tests/resources"),
          },
        ],
      },
    }),
  ],
})
