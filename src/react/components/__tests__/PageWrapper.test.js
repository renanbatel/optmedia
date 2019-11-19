import React from "react"
import { shallow } from "enzyme"

import { findByTestAttr } from "../../tests/utils/components"
import PageWrapper from "../PageWrapper"

describe("components/PageWrapper", () => {
  it("should render the set up wrapper with its children", () => {
    const wrapper = shallow(
      <PageWrapper>
        <span data-test="child" />
        <span data-test="child" />
      </PageWrapper>,
    )
    const pageWrapper = findByTestAttr(wrapper, "page-wrapper")
    const children = findByTestAttr(wrapper, "child")

    expect(pageWrapper.length).toBe(1)
    expect(children.length).toBe(2)
  })
})
