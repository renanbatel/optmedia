import { runSaga } from "redux-saga"

export const runSagaFunction = async (saga) => {
  const dispatchedActions = []
  const fakeStore = {
    dispatch: (action) => dispatchedActions.push(action),
  }

  await runSaga(fakeStore, saga).toPromise()

  return dispatchedActions
}

export default {
  runSagaFunction,
}
