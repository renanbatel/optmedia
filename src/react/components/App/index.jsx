import { bindActionCreators } from "redux"
import { connect } from "react-redux"

import { appOptionsRequest } from "../../actions/app"
import App from "./component"

const mapStateToProps = (state) => ({
  options: state.app.options,
})

const mapDispatchToProps = (dispatch) => bindActionCreators({
  appOptionsRequest,
}, dispatch)

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(App)
