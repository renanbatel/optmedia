import React from "react"
import { shallow } from "enzyme"
import checkPropTypes from "check-prop-types"
import configureMockStore from "redux-mock-store"
import createSagaMiddleware from "redux-saga"

export const mockStore = (initialState = {}) => {
  const sagaMiddleware = createSagaMiddleware()

  return configureMockStore([sagaMiddleware])(initialState)
}

export const shallowWithStore = (Component, store) => (
  shallow(<Component store={store} />)
    .childAt(0)
)

export const findByTestAttr = (component, value) => component.find(
  `[data-test='${value}']`,
)

export const checkProps = (component, props) => checkPropTypes(
  component.propTypes,
  props,
  "props",
  component.name,
)
