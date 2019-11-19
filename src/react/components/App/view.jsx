import React from "react"
import PropTypes from "prop-types"
import { HashRouter, Route } from "react-router-dom"

import Loading from "../Loading"
import Nav from "../Nav"
import SetUp from "../SetUp"
import ServerDiagnostic from "../ServerDiagnostic"
import General from "../Pages/General"

const View = ({ loading }) => (
  <div className="om-app-wrapper" data-test="app-wrapper">
    {
      loading
        ? (
          <Loading />
        ) : (
          <HashRouter>
            <Nav />
            <Route path="/" exact component={General} />
            <Route path="/setup" exact component={SetUp} />
            <Route path="/setup/server-diagnostic" exact component={ServerDiagnostic} />
          </HashRouter>
        )
    }
  </div>
)

View.propTypes = {
  loading: PropTypes.bool.isRequired,
}

export default View
