const path = require("path")
const webpackMerge = require("webpack-merge")
const CopyWebpackPlugin = require("copy-webpack-plugin")
const FileManagerPlugin = require("filemanager-webpack-plugin")
const UglifyJsPlugin = require("uglifyjs-webpack-plugin")

const common = require("./webpack.common")

module.exports = webpackMerge(common, {
  mode: "production",
  optimization: {
    minimizer: [
      new UglifyJsPlugin({
        parallel: true,
        sourceMap: false,
        uglifyOptions: {
          compress: {
            drop_console: true,
            dead_code: true,
          },
          output: {
            beautify: false,
            comments: false,
          },
        },
      }),
    ],
  },
  plugins: [
    new CopyWebpackPlugin(
      [
        {
          context: "./src/wordpress",
          from: `${path.resolve(__dirname, "../src/wordpress")}/**/*.php`,
          to: path.resolve(__dirname, "../dist"),
          ignore: ["tests/**/*.php"],
        },
      ],
      {
        copyUnmodified: true, // copy w/ every watch
      },
    ),
    new FileManagerPlugin({
      onStart: {
        delete: [
          path.resolve(__dirname, "../dist"),
          path.resolve(__dirname, "../dist-build"),
        ],
      },
    }),
  ],
})
