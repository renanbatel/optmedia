import WPAPI from "wpapi"

const wp = new WPAPI({
  endpoint: __OPTMEDIA__.endpoint,
  nonce: __OPTMEDIA__.nonce,
})

// Custom endpoints
wp.pluginOptions = wp.registerRoute(__OPTMEDIA__.namespace, "options", { methods: ["GET"] })

export default wp
