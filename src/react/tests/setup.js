import Enzyme from "enzyme"
import EnzymeAdapter from "enzyme-adapter-react-16"

Enzyme.configure({
  adapter: new EnzymeAdapter(),
})

// usually injected by WordPress (see wp_localize_script in \OptMedia\Admin\Admin)
global.__OPTMEDIA__ = {
  namespace: "optmedia/v1",
  nonce: "abcd1234",
  endpoint: "http://example.com/wp-json/",
}
