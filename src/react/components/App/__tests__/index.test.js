import {
  mockStore,
  shallowWithStore,
} from "../../../tests/utils/components"
import { appOptionsRequest } from "../../../actions/app"
import App from "../index"

describe("components/App/index", () => {
  let expectedProps
  let store
  let wrapper
  let props

  beforeEach(() => {
    expectedProps = {
      loading: true,
    }
    store = mockStore({
      app: expectedProps,
    })
    wrapper = shallowWithStore(App, store)
    props = wrapper.props()
  })
  it("should show expected props", () => {
    expect(props.loading).toBe(expectedProps.loading)
  })
  it("should dispatch expected actions", () => {
    props.appOptionsRequest()

    expect(store.getActions()).toEqual([
      appOptionsRequest(),
    ])
  })
  it("should return the wrapped component without errors", () => {
    expect(wrapper).toBeDefined()
  })
})
