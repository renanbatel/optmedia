const path = require("path")
const ExtractTextPlugin = require("extract-text-webpack-plugin")
const FileManagerPlugin = require("filemanager-webpack-plugin")
const CopyWebpackPlugin = require("copy-webpack-plugin")

const buildDir = path.resolve(__dirname, "../dist")

const extractTextPlugin = new ExtractTextPlugin({
  filename: "css/[name].css",
})

module.exports = {
  entry: {
    "admin.bundle.js": [
      path.resolve(__dirname, "../src/react/admin.jsx"),
    ],
    "theme.bundle.js": [
      path.resolve(__dirname, "../src/wordpress/optmedia/Theme/js/theme.js"),
    ],
    "admin.styles": [
      path.resolve(__dirname, "../src/react/styles/admin.scss"),
    ],
    "theme.styles": [
      path.resolve(__dirname, "../src/wordpress/optmedia/Theme/styles/theme.scss"),
    ],
  },
  output: {
    filename: "[name]",
    path: path.join(__dirname, "../dist-build"),
  },
  resolve: {
    extensions: [".js", ".jsx", ".json", ".css", ".scss"],
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        include: path.resolve(__dirname, "../src"),
        exclude: /(node_modules|bower_components)/,
        use: {
          loader: "babel-loader",
        },
      },
      {
        test: /\.scss$/,
        use: extractTextPlugin.extract({
          use: [
            {
              loader: "css-loader",
              options: {
                minimize: process.env.NODE_ENV === "production",
                sourceMap: true,
              },
            },
            {
              loader: "sass-loader",
              options: {
                sourceMap: true,
              },
            },
          ],
        }),
      },
    ],
  },
  plugins: [
    extractTextPlugin,
    // Note: use CopyWebpackPlugin for wp-file changes, as FileManagerPlugin does
    // not copy them properly in watch-mode
    new CopyWebpackPlugin(
      [
        {
          context: "./src/wordpress/languages",
          from: `${path.resolve(__dirname, "../src/wordpress")}/languages/*.pot`,
          to: `${buildDir}/languages`,
        },
        {
          context: "./src/wordpress",
          from: `${path.resolve(__dirname, "../src/wordpress")}/*.txt`,
          to: buildDir,
        },
        {
          context: "./src/static",
          from: `${path.resolve(__dirname, "../src/static")}/**/*.+(png|jpg)`,
          to: `${buildDir}/static`,
        },
      ],
      {
        copyUnmodified: true,
      },
    ),
    new FileManagerPlugin({
      onEnd: {
        copy: [
          {
            source: path.resolve(__dirname, "../dist-build/css/admin.styles.css"),
            destination: path.resolve(__dirname, "../dist/optmedia/Admin/css"),
          },
          {
            source: path.resolve(__dirname, "../dist-build/admin.bundle.js"),
            destination: path.resolve(__dirname, "../dist/optmedia/Admin/js"),
          },
          {
            source: path.resolve(__dirname, "../dist-build/css/theme.styles.css"),
            destination: path.resolve(__dirname, "../dist/optmedia/Theme/css"),
          },
          {
            source: path.resolve(__dirname, "../dist-build/theme.bundle.js"),
            destination: path.resolve(__dirname, "../dist/optmedia/Theme/js"),
          },
        ],
      },
    }),
  ],
}
