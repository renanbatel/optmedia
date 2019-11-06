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
      finishDisabled: true,
    }
  }

  componentDidMount() {
    this.serverDiagnosticRequest()
  }

  verifyDiagnostic = (diagnostic) => {
    const keys = Object.keys(diagnostic)

    // Return false if all requirements passed
    return !keys.reduce((carry, key) => {
      const passed = diagnostic[key].passed
        || (diagnostic[key].equivalent && diagnostic[diagnostic[key].equivalent].passed)

      return carry && passed
    }, true)
  }

  serverDiagnosticRequest = async () => {
    try {
      const { diagnostic } = await wp.serverDiagnostic()

      this.setState({
        diagnostic,
        finishDisabled: this.verifyDiagnostic(diagnostic),
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
    const { diagnostic, finishDisabled } = this.state

    return (
      <View
        diagnostic={diagnostic}
        finishDisabled={finishDisabled}
        handleFinish={this.handleFinish}
      />
    )
  }

}

export default ServerDiagnostic
