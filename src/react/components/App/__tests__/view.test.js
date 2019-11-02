import React from "react"
import { shallow } from "enzyme"
import { HashRouter } from "react-router-dom"

import { findByTestAttr } from "../../../tests/utils/components"
import View from "../view"
import Loading from "../../Loading"
import Nav from "../../Nav"

const render = (props) => shallow(
  <View
    loading={props.loading}
  />,
)

describe("components/App/view", () => {
  it("should render the app wrapper", () => {
    const wrapper = render({
      loading: true,
    })
    const appWrapper = findByTestAttr(wrapper, "app-wrapper")

    expect(appWrapper.length).toBe(1)
  })
  it("should render the loading component", () => {
    const wrapper = render({
      loading: true,
    })
    const loading = wrapper.find(Loading)
    const hashRouter = wrapper.find(HashRouter)
    const nav = wrapper.find(Nav)

    expect(loading.length).toBe(1)
    expect(hashRouter.length).toBe(0)
    expect(nav.length).toBe(0)
  })
  it("should render the hash router and the nav component", () => {
    const wrapper = render({
      loading: false,
    })
    const loading = wrapper.find(Loading)
    const hashRouter = wrapper.find(HashRouter)
    const nav = wrapper.find(Nav)

    expect(loading.length).toBe(0)
    expect(hashRouter.length).toBe(1)
    expect(nav.length).toBe(1)
  })
})
