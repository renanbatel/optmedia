import React, { Component } from "react"
import PropTypes from "prop-types"

import View from "./View"

class Nav extends Component {

  static propTypes = {
    history: PropTypes.instanceOf(Object).isRequired,
    isSetUp: PropTypes.bool.isRequired,
  }

  componentDidMount() {
    const { history, isSetUp } = this.props

    if (!isSetUp && history.location.pathname !== "/setup") {
      history.push("/setup")
    } else if (isSetUp && history.location.pathname === "/setup") {
      history.push("/")
    }
  }

  render() {
    const { isSetUp } = this.props

    return (
      <View
        isSetUp={isSetUp}
      />
    )
  }

}

export default Nav
