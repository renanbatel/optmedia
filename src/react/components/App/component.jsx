import React, { Component } from "react"
import PropTypes from "prop-types"

import View from "./view"

class App extends Component {

  static propTypes = {
    appOptionsRequest: PropTypes.func.isRequired,
    loading: PropTypes.bool.isRequired,
  }

  componentDidMount() {
    const { appOptionsRequest } = this.props

    appOptionsRequest()
  }

  render() {
    const { loading } = this.props

    return (
      <View
        loading={loading}
      />
    )
  }

}

export default App
