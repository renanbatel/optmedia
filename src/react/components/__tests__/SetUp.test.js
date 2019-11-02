import React from "react"
import { shallow } from "enzyme"
import { Typography } from "antd"
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome"
import { faBolt } from "@fortawesome/free-solid-svg-icons"
import { Link } from "react-router-dom"

import SetUp from "../SetUp"
import SetUpWrapper from "../SetUpWrapper"

describe("components/SetUp", () => {
  let wrapper

  beforeEach(() => {
    wrapper = shallow(<SetUp />)
      .first()
      .shallow()
  })
  it("should render the set up wrapper", () => {
    const setUpWrapper = wrapper.find(SetUpWrapper)

    expect(setUpWrapper.length).toBe(1)
  })
  it("should render the lightning icon with the title and subtitle", () => {
    const title = wrapper.find(Typography.Title)
    const icon = wrapper.find(FontAwesomeIcon)
    const subtitle = wrapper.find(Typography.Text)

    expect(title.length).toBe(1)
    expect(icon.length).toBe(1)
    expect(subtitle.length).toBe(1)
    expect(icon.props().icon).toEqual(faBolt)
    expect(subtitle.props().strong).toBeTruthy()
  })
  it("should render a link to the server diagnostic page", () => {
    const link = wrapper.find(Link)

    expect(link.length).toBe(1)
    expect(link.props().to).toBe("/setup/server-diagnostic")
  })
})
