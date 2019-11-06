const path = require("path")
const webpackMerge = require("webpack-merge")
const CopyWebpackPlugin = require("copy-webpack-plugin")
const FileManagerPlugin = require("filemanager-webpack-plugin")
const TerserPlugin = require("terser-webpack-plugin")

const common = require("./webpack.common")

module.exports = webpackMerge(common, {
  mode: "production",
  optimization: {
    minimizer: [
      new TerserPlugin({
        cache: true,
        parallel: true,
        sourceMap: true,
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
