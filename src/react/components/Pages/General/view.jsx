import React from "react"
import { withTranslation } from "react-i18next"
import PropTypes from "prop-types"
import { Checkbox } from "antd"

import PageWrapper from "../../PageWrapper"

const getImageFormatsCheckboxes = (t) => [
  { label: t("label.jpeg"), value: "jpeg" },
  { label: t("label.png"), value: "png" },
  { label: t("label.webp"), value: "webp" },
]

const View = ({
  t,
  imageFormats,
  handleImageFormatsChange,
}) => (
  <PageWrapper>
    <div className="om-options-form" data-test="image-formats-form">
      <p
        className="om-options-form__label"
        data-test="image-formats-label"
      >
        { t("label.image_formats") }
      </p>
      <div className="om-options-form__inner">
        <Checkbox.Group
          options={getImageFormatsCheckboxes(t)}
          value={imageFormats}
          onChange={handleImageFormatsChange}
        />
      </div>
    </div>
  </PageWrapper>
)

View.propTypes = {
  t: PropTypes.func.isRequired,
  imageFormats: PropTypes.arrayOf(PropTypes.string).isRequired,
  handleImageFormatsChange: PropTypes.func.isRequired,
}

export default withTranslation("common")(View)
