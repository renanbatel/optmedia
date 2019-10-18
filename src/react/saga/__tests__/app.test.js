import { runSaga } from "redux-saga"

import wp from "../../services/wp"
import { handleAppOptionsRequest } from "../app"
import { appOptionsSuccess } from "../../actions/app"
import { initialState } from "../../store"

describe("saga/app", () => {
  it("should handle app options request and handle then in case of success", async () => {
    const options = {
      a: 1,
    }
    const dispatchedActions = []
    const fakeStore = {
      getState: () => initialState,
      dispatch: (action) => dispatchedActions.push(action),
    }

    wp.pluginOptions = jest.fn(() => Promise.resolve({ data: options }))

    await runSaga(fakeStore, handleAppOptionsRequest).toPromise()

    expect(wp.pluginOptions.mock.calls.length).toBe(1)
    expect(dispatchedActions).toContainEqual(appOptionsSuccess(options))
  })
})
