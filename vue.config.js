// see http://vuejs-templates.github.io/webpack for documentation.
//const path = require("path");
module.exports = {
  publicPath: process.env.NODE_ENV === 'production' ? '/' : '/',
  // from https://forum.vuejs.org/t/service-worker-not-registering-using-pwa-plugin/60451
  pwa: {
    name: 'Celebrate',
    themeColor: '#4DBA87',
    msTileColor: '#000000',
    appleMobileWebAppCapable: 'yes',
    appleMobileWebAppStatusBarStyle: 'black',
    workboxPluginMode: 'GenerateSW'
  }
}
