import React from "react"
import PropTypes from "prop-types"

const SetUpWrapper = ({ children }) => (
  <div className="om-set-up-wrapper" data-test="set-up-wrapper">
    { children }
  </div>
)

SetUpWrapper.propTypes = {
  children: PropTypes.oneOfType([
    PropTypes.arrayOf(PropTypes.node),
    PropTypes.node,
  ]).isRequired,
}

export default SetUpWrapper
