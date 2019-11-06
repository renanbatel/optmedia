import React from "react"
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome"
import { faBolt } from "@fortawesome/free-solid-svg-icons"

const BlockLoading = () => (
  <div className="om-block-loading">
    <FontAwesomeIcon
      className="om-block-loading__bolt"
      icon={faBolt}
    />
  </div>
)

export default BlockLoading
