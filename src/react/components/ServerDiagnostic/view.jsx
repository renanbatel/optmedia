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
  const itemClass = `om-diagnostic-list__item_${item.type}`
  let state
  let stateClass
  let stateIcon
  let colorIcon

  if (item.passed) {
    state = "success"
    stateClass = "om-diagnostic-list__item_success"
    stateIcon = "check-circle"
    colorIcon = "#52c41a" // color(success)
  } else if (!item.passed && (item.equivalent && diagnostic[item.equivalent].passed)) {
    state = "warning"
    stateClass = "om-diagnostic-list__item_warning"
    stateIcon = "exclamation-circle"
    colorIcon = "#faad14" // color(warning)
  } else {
    state = "error"
    stateClass = "om-diagnostic-list__item_error"
    stateIcon = "close-circle"
    colorIcon = "#f5222d" // color(error)
  }

  return (
    <List.Item className={cn("om-diagnostic-list__item", itemClass, stateClass)}>
      <div className="om-diagnostic-list__item__icon">
        <Icon
          theme="twoTone"
          type={stateIcon}
          twoToneColor={colorIcon}
        />
      </div>
      <div className="om-diagnostic-list__item__text">
        <Typography.Text strong>
          { item.name }
          { item.required ? ` ${item.required}` : "" }
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

const View = ({
  t,
  diagnostic,
  finishDisabled,
  handleFinish,
}) => (
  <SetUpWrapper>
    <div className="om-server-diagnostic">
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
              className="om-diagnostic-list"
              dataSource={diagnosticToArray(diagnostic)}
              renderItem={renderItem(t, diagnostic)}
            />
          ) : (
            <BlockLoading />
          )
      }
    </div>
    <div className="om-set-up-wrapper__actions">
      <Link to="/setup">
        <Button>
          { t("button.back") }
        </Button>
      </Link>
      <Button
        type="primary"
        data-test="finish-button"
        disabled={finishDisabled}
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
  finishDisabled: PropTypes.bool.isRequired,
  handleFinish: PropTypes.func.isRequired,
}

View.defaultProps = {
  diagnostic: null,
}

export default withTranslation(
  "common",
)(View)
