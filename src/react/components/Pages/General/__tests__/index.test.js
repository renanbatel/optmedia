import { mockStore, shallowWithStore } from "../../../../tests/utils/components"
import { appOptionUpdateRequest } from "../../../../actions/app"
import General from "../index"

describe("components/Pages/General/index", () => {
  let expectedProps
  let store
  let wrapper
  let props

  beforeEach(() => {
    expectedProps = {
      imageFormats: ["foo", "bar", "baz"],
    }
    store = mockStore({
      app: {
        options: {
          plugin_imageFormats: expectedProps.imageFormats,
        },
      },
    })
    wrapper = shallowWithStore(General, store)
    props = wrapper.props()
  })
  it("should return the wrapped component without errors", () => {
    expect(wrapper).toBeDefined()
  })
  it("should show expected props", () => {
    expect(props.imageFormats).toBe(expectedProps.imageFormats)
  })
  it("should dispatch expected actions", () => {
    const option = {
      key: "foo",
      value: "bar",
    }

    props.appOptionUpdateRequest(option)

    expect(store.getActions()).toEqual([
      appOptionUpdateRequest(option),
    ])
  })
})
