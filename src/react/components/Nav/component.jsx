import React, { Component } from "react"
import PropTypes from "prop-types"

import View from "./view"

class Nav extends Component {

  static propTypes = {
    history: PropTypes.instanceOf(Object).isRequired,
    isSetUp: PropTypes.bool.isRequired,
  }

  componentDidMount() {
    this.handleRedirection()
  }

  componentDidUpdate(prevProps) {
    const { isSetUp } = this.props

    if (isSetUp !== prevProps.isSetUp) {
      this.handleRedirection()
    }
  }

  handleRedirection() {
    const { history, isSetUp } = this.props
    const isSetUpPath = /^\/setup\/?/.test(history.location.pathname)

    if (!isSetUp && !isSetUpPath) {
      history.push("/setup")
    } else if (isSetUp && isSetUpPath) {
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
