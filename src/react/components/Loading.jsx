import React from "react"
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome"
import { faBolt } from "@fortawesome/free-solid-svg-icons"

const Loading = () => (
  <div className="om-loading">
    <FontAwesomeIcon
      className="om-loading__bolt"
      icon={faBolt}
    />
  </div>
)

export default Loading
