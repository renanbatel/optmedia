import { mockStore, shallowWithStore } from "../../../tests/utils/components"
import { appSetUpUpdateRequest } from "../../../actions/app"
import ServerDiagnostic from "../index"

describe("components/ServerDiagnostic/index", () => {
  let store
  let wrapper
  let props

  beforeEach(() => {
    store = mockStore()
    wrapper = shallowWithStore(ServerDiagnostic, store)
    props = wrapper.props()
  })
  it("should return the wrapped component without errors", () => {
    expect(wrapper).toBeDefined()
  })
  it("should dispatch expected actions", () => {
    props.appSetUpUpdateRequest({ foo: "bar" })

    expect(store.getActions()).toEqual([
      appSetUpUpdateRequest({ foo: "bar" }),
    ])
  })
})
