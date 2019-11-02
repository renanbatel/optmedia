import React from "react"
import { shallow } from "enzyme"

import { checkProps } from "../../../tests/utils/components"
import App from "../component"

describe("components/App/component", () => {
  let props
  let wrapper

  beforeEach(() => {
    props = {
      appOptionsRequest: jest.fn(),
      loading: false,
    }

    wrapper = shallow(
      <App
        appOptionsRequest={props.appOptionsRequest}
        loading={props.loading}
      />,
    )
  })

  describe("PropTypes", () => {
    it("should not throw a warning", () => {
      const error = checkProps(App, props)

      expect(error).toBeUndefined()
    })
  })
  it("should show expected props", () => {
    const expectedProps = {
      loading: props.loading,
    }

    expect(wrapper.props()).toEqual(expectedProps)
  })
  it("should call plugin options request once", () => {
    expect(props.appOptionsRequest.mock.calls.length).toBe(1)
  })
})
