import React from "react"
import { shallow } from "enzyme"
import { Menu, Icon } from "antd"

import { checkProps, findByTestAttr } from "../../../tests/utils/components"
import View from "../view"

const setUp = (props) => {
  const wrapper = shallow(
    <View
      isSetUp={props.isSetUp}
      selectedKeys={props.selectedKeys || ["/"]}
      handleMenuItemClick={props.handleMenuItemClick || jest.fn()}
    />,
  )

  return wrapper
    .first()
    .shallow()
}

describe("components/Nav/view", () => {
  describe("Prop Types", () => {
    it("should not throw a warning", () => {
      const props = {
        t: jest.fn(),
        isSetUp: false,
        selectedKeys: ["/"],
        handleMenuItemClick: jest.fn(),
      }
      const error = checkProps(View, props)

      expect(error).toBeUndefined()
    })
  })
  it("should not render the nav", () => {
    const wrapper = setUp({ isSetUp: false })
    const nav = findByTestAttr(wrapper, "om-nav")

    expect(nav.length).toBe(0)
  })
  it("should render the nav", () => {
    const wrapper = setUp({ isSetUp: true })
    const nav = findByTestAttr(wrapper, "om-nav")

    expect(nav.length).toBe(1)
  })
  it("should render the nav with right menu items in right order", () => {
    const props = {
      isSetUp: true,
      selectedKeys: ["/"],
    }
    const wrapper = setUp(props)
    const menu = wrapper.find(Menu)

    expect(menu.length).toBe(1)
    expect(menu.props().mode).toEqual("inline")
    expect(menu.props().selectedKeys).toEqual(props.selectedKeys)

    // Menu items
    const items = menu.find(Menu.Item)
    const general = items.at(0)

    expect(general.key()).toBe("/")

    // Menu items icons
    const generalIcon = general.find(Icon)

    expect(generalIcon.length).toBe(1)
    expect(generalIcon.props().type).toBe("setting")

    // Menu sub menus
    const subMenus = menu.find(Menu.SubMenu)
    const compression = subMenus.at(0)

    expect(compression.key()).toBe("compression")

    // Menu sub menus icons
    const compressionTitle = shallow(compression.props().title)
    const compressionIcon = compressionTitle.find(Icon)

    expect(compressionIcon.length).toBe(1)
    expect(compressionIcon.props().type).toBe("vertical-align-middle")

    // Menu sub menus items
    const compressionItems = compression.find(Menu.Item)

    expect(compressionItems.at(0).key()).toBe("/compression/jpeg")
    expect(compressionItems.at(1).key()).toBe("/compression/png")
    expect(compressionItems.at(2).key()).toBe("/compression/webp")
  })
})
