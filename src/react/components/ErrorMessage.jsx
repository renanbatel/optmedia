import React from "react"
import PropTypes from "prop-types"

const ErrorMessage = ({ message }) => (
  <div className="om-error-message">
    <p>{message}</p>
  </div>
)

ErrorMessage.propTypes = {
  message: PropTypes.string.isRequired,
}

export default ErrorMessage
