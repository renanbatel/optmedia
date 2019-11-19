import React from "react"
import { shallow } from "enzyme"

import { checkProps } from "../../../../tests/utils/components"
import General from "../component"

const setUp = (props) => shallow(
  <General
    imageFormats={props.imageFormats}
    appOptionUpdateRequest={props.appOptionUpdateRequest}
  />,
)

const getDefaultProps = () => ({
  imageFormats: ["jpeg", "png", "webp"],
  appOptionUpdateRequest: jest.fn(),
})

describe("components/Pages/General/component", () => {

  describe("PropTypes", () => {
    it("should not throw a warning", () => {
      const error = checkProps(General, getDefaultProps())

      expect(error).toBeUndefined()
    })
  })
  it("should show expected props on view", () => {
    const props = getDefaultProps()
    const wrapper = setUp(props)

    expect(wrapper.props().imageFormats).toBeDefined()
    expect(wrapper.props().handleImageFormatsChange).toBeDefined()
    expect(wrapper.props().imageFormats).toEqual(props.imageFormats)
    expect(wrapper.props().handleImageFormatsChange)
      .toEqual(wrapper.instance().handleImageFormatsChange)
  })
  it("should handle image formats change", () => {
    const props = getDefaultProps()
    const wrapper = setUp(props)
    const value = ["foo", "bar"]

    wrapper.instance().handleImageFormatsChange(value)

    expect(props.appOptionUpdateRequest.mock.calls.length).toBe(1)
    expect(props.appOptionUpdateRequest.mock.calls[0][0])
      .toEqual({
        key: "plugin_imageFormats",
        value,
      })
  })
})
