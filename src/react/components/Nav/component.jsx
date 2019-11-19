import React, { Component } from "react"
import PropTypes from "prop-types"

import View from "./view"

class Nav extends Component {

  static propTypes = {
    history: PropTypes.instanceOf(Object).isRequired,
    isSetUp: PropTypes.bool.isRequired,
  }

  constructor(props) {
    super(props)

    this.state = {
      selectedKeys: [],
    }
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

  handleMenuItemClick = ({ key }) => {
    const { history } = this.props

    if (history.location.pathname !== key) {
      history.push(key)
      this.setState({ selectedKeys: [key] })
    }
  }

  handleRedirection() {
    const { history, isSetUp } = this.props
    const isSetUpPath = /^\/setup\/?/.test(history.location.pathname)
    const selectedKeys = [history.location.pathname]
    let redirect = null

    if (!isSetUp && !isSetUpPath) {
      redirect = "/setup"
    } else if (isSetUp && isSetUpPath) {
      redirect = "/"
    }

    if (redirect) {
      history.push(redirect)
      selectedKeys.pop()
      selectedKeys.push(redirect)
    }

    this.setState({ selectedKeys })
  }

  render() {
    const { selectedKeys } = this.state
    const { isSetUp } = this.props

    return (
      <View
        isSetUp={isSetUp}
        selectedKeys={selectedKeys}
        handleMenuItemClick={this.handleMenuItemClick}
      />
    )
  }

}

export default Nav
