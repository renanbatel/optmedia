import app, { initialState } from "../app"
import {
  appUpdateLoading,
  appUpdateError,
  appOptionsSuccess,
  appSetUpUpdateSuccess,
} from "../../actions/app"

describe("reducers/app", () => {
  it("should return the default state", () => {
    const state = app(undefined, {})

    expect(state).toEqual(initialState)
  })
  it("should update the loading state", () => {
    const state = app(undefined, appUpdateLoading(false))

    expect(state.loading).toBeFalsy()
  })
  it("should update the error state", () => {
    const error = {
      code: "code",
      instance: new Error(),
    }

    const state = app(undefined, appUpdateError(error))

    expect(state.error).toEqual(error)
  })
  it("should update the options state", () => {
    const options = { a: 1 }
    const state = app(undefined, appOptionsSuccess(options))

    expect(state.options).toEqual(options)
  })
  it("should update the is set up state", () => {
    const plugin_isSetUp = true
    const state = app(undefined, appSetUpUpdateSuccess(plugin_isSetUp))

    expect(state.options.plugin_isSetUp).toBe(plugin_isSetUp)
  })
})
