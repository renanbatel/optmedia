import React from "react"
import { shallow } from "enzyme"

import { mockWp, unmockWp } from "../../../tests/utils"
import { flushPromises } from "../../../tests/utils/components"
import wp from "../../../services/wp"
import ServerDiagnostic from "../component"

const setUp = (serverDiagnosticMock, props = {}) => {
  wp.serverDiagnostic.mockResolvedValue(serverDiagnosticMock)

  return shallow(
    <ServerDiagnostic
      appSetUpUpdateRequest={props.appSetUpUpdateRequest || jest.fn()}
    />,
  )
}

describe("components/ServerDiagnostic/component", () => {
  const defaultDiagnostic = { foo: "bar", bar: "baz" }
  const defaultDiagnosticMock = {
    success: true,
    diagnostic: defaultDiagnostic,
  }
  const defaultProps = {
    appSetUpUpdateRequest: jest.fn(),
  }

  beforeEach(() => {
    mockWp()
  })
  afterEach(() => {
    unmockWp()
  })
  it("should make the server diagnostic request once", async () => {
    const wrapper = setUp(defaultDiagnosticMock)

    await flushPromises()

    expect(wp.serverDiagnostic.mock.calls.length).toBe(1)
    expect(wrapper.state().diagnostic).toEqual(defaultDiagnostic)
  })
  it("should handle the set up finish", () => {
    const wrapper = setUp(
      defaultDiagnosticMock,
      defaultProps,
    )

    wrapper.instance().handleFinish()

    expect(defaultProps.appSetUpUpdateRequest.mock.calls.length).toBe(1)
    expect(defaultProps.appSetUpUpdateRequest.mock.calls[0][0]).toEqual({
      isSetUp: true,
    })
  })
  it("should disable finish when diagnostic didn't pass", async () => {
    const diagnosticMock = {
      success: true,
      diagnostic: {
        first: {
          type: "extension",
          passed: true,
        },
        second: {
          type: "extension",
          passed: false,
        },
      },
    }
    const wrapper = setUp(diagnosticMock, defaultProps)

    await flushPromises()

    expect(wrapper.props().finishDisabled).toBe(true)
  })
  it("should enable finish when diagnostic pass", async () => {
    const diagnosticMock = {
      success: true,
      diagnostic: {
        first: {
          type: "extension",
          passed: true,
        },
        second: {
          type: "extension",
          passed: false,
          equivalent: "first",
        },
      },
    }
    const wrapper = setUp(diagnosticMock, defaultProps)

    await flushPromises()

    expect(wrapper.props().finishDisabled).toBe(false)
  })
  it("should show expected props on view", async () => {
    const wrapper = setUp(
      defaultDiagnosticMock,
      defaultProps,
    )

    await flushPromises()

    expect(wrapper.props().diagnostic).toBeDefined()
    expect(wrapper.props().handleFinish).toBeDefined()
    expect(wrapper.props().finishDisabled).toBeDefined()
    expect(wrapper.props().diagnostic).toEqual(defaultDiagnostic)
    expect(wrapper.props().handleFinish).toEqual(wrapper.instance().handleFinish)
  })
})
