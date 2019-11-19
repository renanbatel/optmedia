import React from "react"
import { withTranslation } from "react-i18next"
import PropTypes from "prop-types"

import PageWrapper from "../../PageWrapper"

const View = ({ t }) => (
  <PageWrapper>
    <h1>{ t("label.image_formats") }</h1>
  </PageWrapper>
)

View.propTypes = {
  t: PropTypes.func.isRequired,
}

export default withTranslation("common")(View)
