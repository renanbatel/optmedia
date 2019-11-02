import React from "react"
import { withTranslation } from "react-i18next"
import { Link } from "react-router-dom"
import { Button, Typography } from "antd"
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome"
import { faBolt } from "@fortawesome/free-solid-svg-icons"
import PropTypes from "prop-types"

import SetUpWrapper from "./SetUpWrapper"

const SetUp = ({ t }) => (
  <SetUpWrapper>
    <div className="set-up">
      <FontAwesomeIcon icon={faBolt} />
      <Typography.Title>
        { t("title.set_up") }
      </Typography.Title>
      <Typography.Text strong>
        { t("subtitle.set_up") }
      </Typography.Text>
    </div>
    <div className="set-up-wrapper__actions">
      <Link to="/setup/server-diagnostic">
        <Button
          type="primary"
        >
          { t("button.begin") }
        </Button>
      </Link>
    </div>
  </SetUpWrapper>
)

SetUp.propTypes = {
  t: PropTypes.func.isRequired,
}

export default withTranslation(
  "common",
)(SetUp)
