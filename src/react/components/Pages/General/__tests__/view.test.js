import React from "react"
import { shallow } from "enzyme"

import { checkProps } from "../../../../tests/utils/components"
import PageWrapper from "../../../PageWrapper"
import View from "../view"

const setUp = () => {
  const wrapper = shallow(<View />)

  return wrapper
    .first()
    .shallow()
}

describe("components/Pages/General/view", () => {
  describe("Prop Types", () => {
    it("should not throw a warning", () => {
      const props = {
        t: jest.fn(),
      }
      const error = checkProps(View, props)

      expect(error).toBeUndefined()
    })
  })
  it("should render the page wrapper", () => {
    const wrapper = setUp()
    const pageWrapper = wrapper.find(PageWrapper)

    expect(pageWrapper.length).toBe(1)
  })
})
