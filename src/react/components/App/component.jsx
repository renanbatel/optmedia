import React, { Component } from "react"
import PropTypes from "prop-types"

import View from "./view"

class App extends Component {

  static propTypes = {
    appOptionsRequest: PropTypes.func.isRequired,
  }

  componentDidMount() {
    const { appOptionsRequest } = this.props

    appOptionsRequest()
  }

  render() {
    return (
      <View />
    )
  }

}

export default App
