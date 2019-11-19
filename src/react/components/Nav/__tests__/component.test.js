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
  it("should show expected props on view", () => {
    const props = getDefaultProps()
    const wrapper = setUp(props)

    expect(wrapper.props().isSetUp).toBeDefined()
    expect(wrapper.props().selectedKeys).toBeDefined()
    expect(wrapper.props().handleMenuItemClick).toBeDefined()
    expect(wrapper.props().isSetUp).toEqual(props.isSetUp)
    expect(wrapper.props().handleMenuItemClick).toEqual(wrapper.instance().handleMenuItemClick)
  })
  it("should handle redirection to home on mount", () => {
    const props = getDefaultProps()
    const pathname = "/"
    const wrapper = setUp(props)

    expect(props.history.push.mock.calls.length).toBe(1)
    expect(props.history.push.mock.calls[0][0]).toBe(pathname)
    expect(wrapper.state().selectedKeys).toEqual([pathname])
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
    const pathname = "/setup"
    const wrapper = setUp(props)

    expect(props.history.push.mock.calls.length).toBe(1)
    expect(props.history.push.mock.calls[0][0]).toBe(pathname)
    expect(wrapper.state().selectedKeys).toEqual([pathname])
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
    const pathname = "/"
    const wrapper = setUp(props)

    expect(props.history.push.mock.calls.length).toBe(0)

    wrapper.setProps({ isSetUp: true })

    expect(props.history.push.mock.calls.length).toBe(1)
    expect(props.history.push.mock.calls[0][0]).toBe(pathname)
    expect(wrapper.state().selectedKeys).toEqual([pathname])
  })
  it("should handle menu item click", () => {
    const props = {
      isSetUp: true,
      history: {
        location: {
          pathname: "/",
        },
        push: jest.fn(),
      },
    }
    const wrapper = setUp(props)
    const key = "/page"

    wrapper.instance().handleMenuItemClick({ key: props.history.location.pathname })
    wrapper.instance().handleMenuItemClick({ key })

    expect(props.history.push.mock.calls.length).toBe(1)
    expect(props.history.push.mock.calls[0][0]).toBe(key)
    expect(wrapper.state().selectedKeys).toEqual([key])
  })
})
