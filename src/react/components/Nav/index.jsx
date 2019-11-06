import { connect } from "react-redux"
import { withRouter } from "react-router-dom"

import Nav from "./component"

const mapStateToProps = (state) => ({
  isSetUp: state.app.options.plugin_isSetUp,
})

export default connect(
  mapStateToProps,
)(withRouter(Nav))
