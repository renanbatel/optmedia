import React from "react"
import { withTranslation } from "react-i18next"
import { Link } from "react-router-dom"
import {
  Typography, List, Icon, Button,
} from "antd"
import PropTypes from "prop-types"
import cn from "classnames"

import SetUpWrapper from "../SetUpWrapper"
import BlockLoading from "../BlockLoading"

export const diagnosticToArray = (diagnostic) => {
  const keys = Object.keys(diagnostic)

  return keys.reduce((carry, key) => {
    const item = diagnostic[key]

    item.name = key

    carry.push(item)

    return carry
  }, [])
}

const renderItem = (t, diagnostic) => (item) => {
  const itemClass = `diagnostic-list__item_${item.type}`
  let state
  let stateClass
  let stateIcon

  if (item.passed) {
    state = "success"
    stateClass = "diagnostic-list__item_success"
    stateIcon = "check-circle"
  } else if (!item.passed && (item.equivalent && diagnostic[item.equivalent].passed)) {
    state = "warning"
    stateClass = "diagnostic-list__item_warning"
    stateIcon = "exclamation-circle"
  } else {
    state = "error"
    stateClass = "diagnostic-list__item_error"
    stateIcon = "close-circle"
  }

  return (
    <List.Item className={cn("diagnostic-list__item", itemClass, stateClass)}>
      <div className="diagnostic-list__item_icon">
        <Icon
          theme="twoTone"
          type={stateIcon}
        />
      </div>
      <div className="diagnostic-list__item_text">
        <Typography.Text strong>
          { item.name }
        </Typography.Text>
        {
          state === "warning"
            ? (
              <Typography.Text
                type="secondary"
                data-test="diagnostic-equivalent-warning"
              >
                {
                  t("caption.server_diagnostic_equivalent", {
                    target: item.name,
                    equivalent: item.equivalent,
                  })
                }
              </Typography.Text>
            ) : ""
        }
      </div>
    </List.Item>
  )
}

const View = ({ t, diagnostic, handleFinish }) => (
  <SetUpWrapper>
    <div className="server-diagnostic">
      <Typography.Title level={3}>
        { t("title.server_diagnostic") }
      </Typography.Title>
      <Typography.Text>
        { t("subtitle.server_diagnostic") }
      </Typography.Text>
      {
        diagnostic
          ? (
            <List
              bordered
              className="diagnostic-list"
              dataSource={diagnosticToArray(diagnostic)}
              renderItem={renderItem(t, diagnostic)}
            />
          ) : (
            <BlockLoading />
          )
      }
    </div>
    <div className="set-up-wrapper__actions">
      <Link to="/setup">
        <Button>
          { t("button.back") }
        </Button>
      </Link>
      <Button
        type="primary"
        data-test="finish-button"
        onClick={handleFinish}
      >
        { t("button.finish") }
      </Button>
    </div>
  </SetUpWrapper>
)

View.propTypes = {
  t: PropTypes.func.isRequired,
  diagnostic: PropTypes.instanceOf(Object),
  handleFinish: PropTypes.func.isRequired,
}

View.defaultProps = {
  diagnostic: null,
}

export default withTranslation(
  "common",
)(View)
