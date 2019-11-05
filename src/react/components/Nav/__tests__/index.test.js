import { mockStore, shallowWithStore } from "../../../tests/utils/components"
import Nav from "../index"

describe("components/Nav/index", () => {
  let expectedProps
  let store
  let wrapper
  let props

  beforeEach(() => {
    expectedProps = {
      isSetUp: true,
    }
    store = mockStore({
      app: {
        options: {
          plugin_isSetUp: expectedProps.isSetUp,
        },
      },
    })
    wrapper = shallowWithStore(Nav, store)
    props = wrapper.props()
  })
  it("should show expected props", () => {
    expect(props.isSetUp).toBe(expectedProps.isSetUp)
  })
  it("should return the wrapped component without errors", () => {
    expect(wrapper).toBeDefined()
  })
})
