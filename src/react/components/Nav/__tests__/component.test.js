import React from "react"
import { shallow } from "enzyme"

import { checkProps } from "../../../tests/utils/components"
import Nav from "../component"

const setUp = (props) => shallow(
  <Nav
    isSetUp={props.isSetUp}
    history={props.history}
  />,
)

const getDefaultProps = () => ({
  isSetUp: true,
  history: {
    location: {
      pathname: "/setup/server-diagnostic",
    },
    push: jest.fn(),
  },
})

describe("components/Nav/component", () => {

  describe("PropTypes", () => {
    it("should not throw a warning", () => {
      const error = checkProps(Nav, {
        isSetUp: false,
        history: {},
      })

      expect(error).toBeUndefined()
    })
  })
  it("should show expected props", () => {
    const props = getDefaultProps()
    const wrapper = setUp(props)

    expect(wrapper.props().isSetUp).toEqual(props.isSetUp)
  })
  it("should handle redirection to home on mount", () => {
    const props = getDefaultProps()

    setUp(props)

    expect(props.history.push.mock.calls.length).toBe(1)
    expect(props.history.push.mock.calls[0][0]).toBe("/")
  })
  it("should handle redirection to setup on mount", () => {
    const props = {
      isSetUp: false,
      history: {
        location: {
          pathname: "/",
        },
        push: jest.fn(),
      },
    }

    setUp(props)

    expect(props.history.push.mock.calls.length).toBe(1)
    expect(props.history.push.mock.calls[0][0]).toBe("/setup")
  })
  it("should handle redirection on props update", () => {
    const props = {
      isSetUp: false,
      history: {
        location: {
          pathname: "/setup/diagnostic-server",
        },
        push: jest.fn(),
      },
    }
    const wrapper = setUp(props)

    expect(props.history.push.mock.calls.length).toBe(0)

    wrapper.setProps({ isSetUp: true })

    expect(props.history.push.mock.calls.length).toBe(1)
    expect(props.history.push.mock.calls[0][0]).toBe("/")
  })
})
