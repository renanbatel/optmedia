import React from "react"
import { shallow } from "enzyme"

import { findByTestAttr } from "../../tests/utils/components"
import SetUpWrapper from "../SetUpWrapper"

describe("components/SetUpWrapper", () => {
  it("should render the set up wrapper with its children", () => {
    const wrapper = shallow(
      <SetUpWrapper>
        <span data-test="child" />
        <span data-test="child" />
      </SetUpWrapper>,
    )
    const setUpWrapper = findByTestAttr(wrapper, "set-up-wrapper")
    const children = findByTestAttr(wrapper, "child")

    expect(setUpWrapper.length).toBe(1)
    expect(children.length).toBe(2)
  })
})
