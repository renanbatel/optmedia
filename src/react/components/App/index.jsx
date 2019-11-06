import { bindActionCreators } from "redux"
import { connect } from "react-redux"

import { appOptionsRequest } from "../../actions/app"
import App from "./component"

const mapStateToProps = (state) => ({
  loading: state.app.loading,
})

const mapDispatchToProps = (dispatch) => bindActionCreators({
  appOptionsRequest,
}, dispatch)

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(App)
