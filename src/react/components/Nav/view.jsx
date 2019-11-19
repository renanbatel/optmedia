import React from "react"
import { withTranslation } from "react-i18next"
import PropTypes from "prop-types"
import { Menu, Icon } from "antd"

const View = ({
  t,
  isSetUp,
  selectedKeys,
  handleMenuItemClick,
}) => (isSetUp
  ? (
    <div className="om-nav" data-test="nav">
      <Menu
        mode="inline"
        selectedKeys={selectedKeys}
        onClick={handleMenuItemClick}
      >
        <Menu.Item key="/">
          <Icon type="setting" />
          <span>{ t("menu.item.general") }</span>
        </Menu.Item>
        <Menu.SubMenu
          key="compression"
          title={(
            <span>
              <Icon type="vertical-align-middle" />
              <span>{ t("menu.item.compression") }</span>
            </span>
          )}
        >
          <Menu.Item key="/compression/jpeg">{ t("menu.item.jpeg") }</Menu.Item>
          <Menu.Item key="/compression/png">{ t("menu.item.png") }</Menu.Item>
          <Menu.Item key="/compression/webp">{ t("menu.item.webp") }</Menu.Item>
        </Menu.SubMenu>
      </Menu>
    </div>
  ) : "")

View.propTypes = {
  t: PropTypes.func.isRequired,
  isSetUp: PropTypes.bool.isRequired,
  selectedKeys: PropTypes.arrayOf(PropTypes.string).isRequired,
  handleMenuItemClick: PropTypes.func.isRequired,
}

export default withTranslation("common")(View)
