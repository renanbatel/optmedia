import { bindActionCreators } from "redux"
import { connect } from "react-redux"

import { appOptionUpdateRequest } from "../../../actions/app"
import General from "./component"

const mapStateToProps = (state) => ({
  imageFormats: state.app.options.plugin_imageFormats,
})

const mapDispatchToProps = (dispatch) => bindActionCreators({
  appOptionUpdateRequest,
}, dispatch)

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(General)
