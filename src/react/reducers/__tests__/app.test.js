import app, { initialState } from "../app"
import { appUpdateLoading, appOptionsSuccess } from "../../actions/app"

describe("reducers/app", () => {
  it("should return the default state", () => {
    const state = app(undefined, {})

    expect(state).toEqual(initialState)
  })
  it("should update the loading state", () => {
    const state = app(undefined, appUpdateLoading(false))

    expect(state.loading).toBeFalsy()
  })
  it("should update the options state", () => {
    const options = { a: 1 }
    const state = app(undefined, appOptionsSuccess(options))

    expect(state.options).toEqual(options)
  })
})
