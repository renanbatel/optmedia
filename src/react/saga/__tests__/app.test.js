import { ERROR } from "../../constants"
import { runSagaFunction } from "../../tests/utils/saga"
import wp from "../../services/wp"
import watchApp, { handleAppOptionsRequest } from "../app"
import { appOptionsSuccess, appUpdateError, appUpdateLoading } from "../../actions/app"

describe("saga/app", () => {
  let pluginOptions

  beforeAll(() => {
    pluginOptions = wp.pluginOptions
  })
  afterEach(() => {
    wp.pluginOptions = pluginOptions
  })
  it("should watch app actions", () => {
    const app = watchApp()

    expect(app.next().value).toBeDefined()
  })
  it("should handle app options request and handle response in case of success", async () => {
    const options = {
      a: 1,
    }

    wp.pluginOptions = jest.fn(() => Promise.resolve({ options }))

    const dispatchedActions = await runSagaFunction(handleAppOptionsRequest)

    expect(wp.pluginOptions.mock.calls.length).toBe(1)
    expect(dispatchedActions).toContainEqual(appOptionsSuccess(options))
    expect(dispatchedActions).toContainEqual(appUpdateLoading(false))
  })
  it("should handle app options request errors", async () => {
    const error = {
      code: ERROR.PLUGIN_API_REQUEST,
      instance: expect.anything(),
    }
    const dispatchedActions = await runSagaFunction(handleAppOptionsRequest)

    expect(dispatchedActions).toContainEqual(appUpdateError(error))
  })
})
