import { bindActionCreators } from "redux"
import { connect } from "react-redux"

import { appSetUpUpdateRequest } from "../../actions/app"
import ServerDiagnostic from "./component"

const mapDispatchToProps = (dispatch) => bindActionCreators({
  appSetUpUpdateRequest,
}, dispatch)

export default connect(
  null,
  mapDispatchToProps,
)(ServerDiagnostic)
