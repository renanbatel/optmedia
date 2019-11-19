import React from "react"
import PropTypes from "prop-types"

const PageWrapper = ({ children }) => (
  <div className="om-page-wrapper" data-test="page-wrapper">
    { children }
  </div>
)

PageWrapper.propTypes = {
  children: PropTypes.oneOfType([
    PropTypes.arrayOf(PropTypes.node),
    PropTypes.node,
  ]).isRequired,
}

export default PageWrapper
