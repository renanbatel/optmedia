import WPAPI from "wpapi"

const wp = new WPAPI({
  endpoint: __OPTMEDIA__.endpoint,
  nonce: __OPTMEDIA__.nonce,
})

// Custom endpoints
wp.pluginOptions = wp.registerRoute(__OPTMEDIA__.namespace, "options", { methods: ["GET"] })
wp.serverDiagnostic = wp.registerRoute(__OPTMEDIA__.namespace, "serverDiagnostic", { methods: ["GET"] })
wp.setUpUpdate = wp.registerRoute(__OPTMEDIA__.namespace, "setUpUpdate", { methods: ["POST"] })

export default wp
