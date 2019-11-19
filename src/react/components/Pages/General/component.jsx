import React, { Component } from "react"
import PropTypes from "prop-types"

import View from "./view"

class General extends Component {

  static propTypes = {
    imageFormats: PropTypes.arrayOf(PropTypes.string).isRequired,
    appOptionUpdateRequest: PropTypes.func.isRequired,
  }

  handleImageFormatsChange = (value) => {
    const { appOptionUpdateRequest } = this.props

    appOptionUpdateRequest({
      key: "plugin_imageFormats",
      value,
    })
  }

  render() {
    const { imageFormats } = this.props

    return (
      <View
        imageFormats={imageFormats}
        handleImageFormatsChange={this.handleImageFormatsChange}
      />
    )
  }

}

export default General
