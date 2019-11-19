import React from "react"
import { shallow } from "enzyme"
import { Checkbox } from "antd"

import { checkProps, findByTestAttr } from "../../../../tests/utils/components"
import PageWrapper from "../../../PageWrapper"
import View from "../view"

const setUp = (props) => {
  const wrapper = shallow(
    <View
      imageFormats={props.imageFormats}
      handleImageFormatsChange={props.handleImageFormatsChange}
    />
  )

  return wrapper
    .first()
    .shallow()
}

const getDefaultProps = () => ({
  imageFormats: ["jpeg", "png", "webp"],
  handleImageFormatsChange: jest.fn(),
})

describe("components/Pages/General/view", () => {
  describe("Prop Types", () => {
    it("should not throw a warning", () => {
      const props = {
        t: jest.fn(),
        imageFormats: ["foo", "bar"],
      }
      const error = checkProps(View, props)

      expect(error).toBeUndefined()
    })
  })
  it("should render the page wrapper", () => {
    const wrapper = setUp(getDefaultProps())
    const pageWrapper = wrapper.find(PageWrapper)

    expect(pageWrapper.length).toBe(1)
  })
  it("should render the image formats form with all its components", () => {
    const props = getDefaultProps()
    const wrapper = setUp(props)
    const imageFormatsForm = findByTestAttr(wrapper, "image-formats-form")
    const imageFormatsLabel = findByTestAttr(wrapper, "image-formats-label")
    const imageFormatsCheckboxes = imageFormatsForm.find(Checkbox.Group)

    expect(imageFormatsForm.length).toBe(1)
    expect(imageFormatsLabel.length).toBe(1)
    expect(imageFormatsCheckboxes.length).toBe(1)
    expect(imageFormatsCheckboxes.props().value).toEqual(props.imageFormats)
    expect(imageFormatsCheckboxes.props().onChange).toEqual(props.handleImageFormatsChange)
  })
})
