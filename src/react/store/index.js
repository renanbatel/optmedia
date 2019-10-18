import { createStore, applyMiddleware } from "redux"
import { composeWithDevTools } from "redux-devtools-extension"
import createSagaMiddleware from "redux-saga"

import { initialState as appInitialState } from "../reducers/app"
import reducers from "../reducers"
import rootSaga from "../saga"

export const initialState = {
  app: appInitialState,
}

export default (state = initialState) => {
  const sagaMiddleware = createSagaMiddleware()
  const store = createStore(
    reducers,
    state,
    composeWithDevTools(applyMiddleware(sagaMiddleware)),
  )

  sagaMiddleware.run(rootSaga)

  return store
}
