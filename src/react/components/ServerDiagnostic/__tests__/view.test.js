import React from "react"
import { shallow } from "enzyme"
import { Link } from "react-router-dom"
import { Typography, List, Icon } from "antd"

import { checkProps, findByTestAttr } from "../../../tests/utils/components"
import SetUpWrapper from "../../SetUpWrapper"
import BlockLoading from "../../BlockLoading"
import View, { diagnosticToArray } from "../view"

const setUp = (props) => {
  const wrapper = shallow(
    <View
      diagnostic={props.diagnostic}
      finishDisabled={props.finishDisabled}
      handleFinish={props.handleFinish || jest.fn()}
    />,
  )

  return wrapper
    .first()
    .shallow()
}

describe("components/ServerDiagnostic/view", () => {
  const defaultDiagnostic = {
    first: {
      type: "php",
      passed: true,
    },
    second: {
      type: "extension",
      passed: false,
    },
    third: {
      type: "command",
      equivalent: "first",
      passed: false,
    },
  }

  describe("Prop Types", () => {
    it("should not throw a warning", () => {
      const props = {
        t: jest.fn(),
        diagnostic: { a: 1, b: 2 },
        finishDisabled: true,
        handleFinish: jest.fn(),
      }
      const error = checkProps(View, props)

      expect(error).toBeUndefined()
    })
  })
  it("should transform diagnostic to array", () => {
    const diagnostic = diagnosticToArray(defaultDiagnostic)

    expect(diagnostic.length).toBe(3)
    expect(diagnostic[0].name).toBeDefined()
    expect(diagnostic[1].name).toBeDefined()
    expect(diagnostic[2].name).toBeDefined()
  })
  it("should render de set up wrapper", () => {
    const wrapper = setUp({ finishDisabled: true })
    const setUpWrapper = wrapper.find(SetUpWrapper)

    expect(setUpWrapper.length).toBe(1)
  })
  it("should render the h3 title and the subtitle", () => {
    const wrapper = setUp({ finishDisabled: true })
    const title = wrapper.find(Typography.Title)
    const subtitle = wrapper.find(Typography.Text)

    expect(title.length).toBe(1)
    expect(subtitle.length).toBe(1)
    expect(title.props().level).toBe(3)
  })
  it("should render the block loading when there's no diagnostic", () => {
    const wrapper = setUp({ finishDisabled: true })
    const blockLoading = wrapper.find(BlockLoading)
    const list = wrapper.find(List)

    expect(blockLoading.length).toBe(1)
    expect(list.length).toBe(0)
  })
  it("should render the list items with correct classes and icon when there's a diagnostic", () => {
    const wrapper = setUp({
      diagnostic: defaultDiagnostic,
      finishDisabled: true,
    })
    const blockLoading = wrapper.find(BlockLoading)
    const list = wrapper.find(List)

    expect(blockLoading.length).toBe(0)
    expect(list.length).toBe(1)

    const listShallow = list
      .shallow()
      .first()
      .shallow()
    const listItems = listShallow.find(List.Item)
    const first = listItems.at(0)
    const second = listItems.at(1)
    const third = listItems.at(2)
    const firstIcon = first.find(Icon)
    const secondIcon = second.find(Icon)
    const thirdIcon = third.find(Icon)
    const thirdWarning = findByTestAttr(third, "diagnostic-equivalent-warning")

    expect(listItems.length).toBe(3)
    expect(first.props().className).toContain("diagnostic-list__item_php")
    expect(first.props().className).toContain("diagnostic-list__item_success")
    expect(second.props().className).toContain("diagnostic-list__item_extension")
    expect(second.props().className).toContain("diagnostic-list__item_error")
    expect(third.props().className).toContain("diagnostic-list__item_command")
    expect(third.props().className).toContain("diagnostic-list__item_warning")
    expect(firstIcon.length).toBe(1)
    expect(secondIcon.length).toBe(1)
    expect(thirdIcon.length).toBe(1)
    expect(firstIcon.props().type).toBe("check-circle")
    expect(secondIcon.props().type).toBe("close-circle")
    expect(thirdIcon.props().type).toBe("exclamation-circle")
    expect(thirdWarning.length).toBe(1)
  })
  it("should render a link to go back to set up page", () => {
    const wrapper = setUp({ finishDisabled: true })
    const link = wrapper.find(Link)

    expect(link.length).toBe(1)
    expect(link.props().to).toBe("/setup")
  })
  it("should render a button to finish set up", () => {
    const props = {
      handleFinish: jest.fn(),
      finishDisabled: true,
    }
    const wrapper = setUp(props)
    const button = findByTestAttr(wrapper, "finish-button")

    expect(button.length).toBe(1)

    button.props().onClick()

    expect(props.handleFinish.mock.calls.length).toBe(1)
  })
  it("should disable finish button", () => {
    const props = {
      finishDisabled: true,
    }
    const wrapper = setUp(props)
    const button = findByTestAttr(wrapper, "finish-button")

    expect(button.props().disabled).toBeTruthy()
  })
  it("should enable finish button", () => {
    const props = {
      finishDisabled: false,
    }
    const wrapper = setUp(props)
    const button = findByTestAttr(wrapper, "finish-button")

    expect(button.props().disabled).toBeFalsy()
  })
})
