import { ERROR } from "../../constants"
import { mockWp, unmockWp } from "../../tests/utils"
import { runSagaFunction } from "../../tests/utils/saga"
import wp from "../../services/wp"
import watchApp, {
  handleAppOptionsRequest,
  handleSetUpUpdateRequest,
  handleOptionUpdateRequest,
} from "../app"
import {
  appOptionsSuccess,
  appUpdateError,
  appUpdateLoading,
  appSetUpUpdateRequest,
  appSetUpUpdateSuccess,
  appOptionUpdateRequest,
  appOptionUpdateSuccess,
} from "../../actions/app"

describe("saga/app", () => {
  beforeEach(() => {
    mockWp()
  })
  afterEach(() => {
    unmockWp()
  })
  it("should watch app actions", () => {
    const app = watchApp()
    const watcher = app.next().value
    const payload = 3 // equivalent to the number of sagas on all()

    expect(watcher).toBeDefined()
    expect(watcher.payload.length).toBe(payload)
  })
  it("should handle app options request and handle response in case of success", async () => {
    const options = {
      a: 1,
    }

    wp.pluginOptions.mockResolvedValue({ options })

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
  it("should handle set up update request and handle response in case of success", async () => {
    const plugin_isSetUp = true

    wp.setUpUpdate.mockImplementation(() => ({
      create: jest.fn().mockResolvedValue({ plugin_isSetUp }),
    }))

    const dispatchedActions = await runSagaFunction(
      handleSetUpUpdateRequest,
      appSetUpUpdateRequest({}),
    )

    expect(wp.setUpUpdate.mock.calls.length).toBe(1)
    expect(dispatchedActions).toContainEqual(appSetUpUpdateSuccess(plugin_isSetUp))
  })
  it("should handle set up update request errors", async () => {
    const error = {
      code: ERROR.PLUGIN_API_REQUEST,
      instance: expect.anything(),
    }

    const dispatchedActions = await runSagaFunction(
      handleSetUpUpdateRequest,
      appSetUpUpdateRequest({}),
    )

    expect(dispatchedActions).toContainEqual(appUpdateError(error))
  })
  it("should handle option update request and handle response in case of success", async () => {
    const option = {
      key: "foo",
      value: "bar",
    }

    wp.optionUpdate.mockImplementation(() => ({
      create: jest.fn().mockResolvedValue({ option }),
    }))

    const dispatchedActions = await runSagaFunction(
      handleOptionUpdateRequest,
      appOptionUpdateRequest({}),
    )

    expect(wp.optionUpdate.mock.calls.length).toBe(1)
    expect(dispatchedActions).toContainEqual(appOptionUpdateSuccess(option))
  })
  it("should handle option update request errors", async () => {
    const error = {
      code: ERROR.PLUGIN_API_REQUEST,
      instance: expect.anything(),
    }

    const dispatchedActions = await runSagaFunction(
      handleOptionUpdateRequest,
      appOptionUpdateRequest({}),
    )

    expect(dispatchedActions).toContainEqual(appUpdateError(error))
  })
})
