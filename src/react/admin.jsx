import React from "react"
import { render } from "react-dom"
import { Provider } from "react-redux"
import Debug from "debug"

import "./lib/i18n"
import makeStore from "./store"
import App from "./components/App"
import ErrorMessage from "./components/ErrorMessage"

const ROOT_ELEM_ID = "optmedia-app"

const debug = Debug("Admin")
const store = makeStore()

function renderApp() {
  try {
    const wrappedApp = (
      <Provider store={store}>
        <App />
      </Provider>
    )

    render(wrappedApp, document.getElementById(ROOT_ELEM_ID))
  } catch (error) {
    const message = "An error occurred, please try it again."
    const errorMessage = (
      <ErrorMessage
        message={message}
      />
    )

    debug(error)
    render(errorMessage, document.getElementById(ROOT_ELEM_ID))
  }
}

renderApp()
