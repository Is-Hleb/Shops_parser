module.exports = {
  publicPath: "./",
  devServer: {
    proxy: 'http://parser:8888',
  }
};
