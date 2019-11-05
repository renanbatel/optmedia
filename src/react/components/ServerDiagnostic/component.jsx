import React, { Component } from "react"
import PropTypes from "prop-types"

import wp from "../../services/wp"
import View from "./view"

class ServerDiagnostic extends Component {

  static propTypes = {
    appSetUpUpdateRequest: PropTypes.func.isRequired,
  }

  constructor(props) {
    super(props)

    this.state = {
      diagnostic: null,
    }
  }

  componentDidMount() {
    this.serverDiagnosticRequest()
  }

  serverDiagnosticRequest = async () => {
    try {
      const response = await wp.serverDiagnostic()

      this.setState({
        diagnostic: response.diagnostic,
      })
    } catch (error) {
      // TODO: handle errors
    }
  }

  handleFinish = () => {
    const { appSetUpUpdateRequest } = this.props

    appSetUpUpdateRequest({
      isSetUp: true,
    })
  }

  render() {
    const { diagnostic } = this.state

    return (
      <View
        diagnostic={diagnostic}
        handleFinish={this.handleFinish}
      />
    )
  }

}

export default ServerDiagnostic
