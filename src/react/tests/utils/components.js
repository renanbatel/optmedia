import React from "react"
import { shallow } from "enzyme"
import { shallowEqualObjects } from "shallow-equal"
import checkPropTypes from "check-prop-types"
import configureMockStore from "redux-mock-store"
import createSagaMiddleware from "redux-saga"

export const mockStore = (initialState = {}) => {
  const sagaMiddleware = createSagaMiddleware()

  return configureMockStore([sagaMiddleware])(initialState)
}

export const shallowWithStore = (Component, store) => {
  const wrapper = shallow(<Component store={store} />)

  // Somehow the App component is rendered with an ContextProvider component wrapping it,
  // so we check if the rendered root component is equal to the Component wrapped by connect(),
  // if so, we just return it.
  if (shallowEqualObjects(wrapper.type(), Component.WrappedComponent)) {
    return wrapper
  }

  // Return the first child
  return wrapper
    .first()
    .shallow()
}

export const findByTestAttr = (component, value) => component.find(
  `[data-test='${value}']`,
)

export const checkProps = (component, props) => checkPropTypes(
  component.propTypes,
  props,
  "props",
  component.name,
)

export const flushPromises = () => new Promise((resolve) => setImmediate(resolve))
