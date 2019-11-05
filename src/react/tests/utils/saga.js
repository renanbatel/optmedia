import { runSaga } from "redux-saga"

export const runSagaFunction = async (saga, ...args) => {
  const dispatchedActions = []
  const fakeStore = {
    dispatch: (action) => dispatchedActions.push(action),
  }

  await runSaga(fakeStore, saga, ...args).toPromise()

  return dispatchedActions
}

export default {
  runSagaFunction,
}
