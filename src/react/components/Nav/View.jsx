import React from "react"
import PropTypes from "prop-types"

const View = ({ isSetUp }) => (isSetUp
  ? (
    <>
      {
        // TODO: plugin page navigation
      }
    </>
  ) : "")

View.propTypes = {
  isSetUp: PropTypes.bool.isRequired,
}

export default View
