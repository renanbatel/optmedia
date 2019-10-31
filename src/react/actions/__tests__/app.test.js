import {
  appUpdateLoading,
  appUpdateError,
  appOptionsRequest,
  appOptionsSuccess,
} from "../app"
import { APP } from "../../constants/actions"

describe("actions/app", () => {
  it("should return the update loading action", () => {
    const action = {
      type: APP.UPDATE_LOADING,
      loading: false,
    }

    expect(appUpdateLoading(false)).toEqual(action)
  })
  it("should return the update error action", () => {
    const error = {
      message: "Request Error",
      instance: new Error(),
    }
    const action = {
      type: APP.UPDATE_ERROR,
      error,
    }

    expect(appUpdateError(error)).toEqual(action)
  })
  it("should return the options request action", () => {
    const action = {
      type: APP.OPTIONS_REQUEST,
    }

    expect(appOptionsRequest(false)).toEqual(action)
  })

  it("should return the update loading action", () => {
    const options = {
      a: 1,
    }
    const action = {
      type: APP.OPTIONS_SUCCESS,
      options,
    }

    expect(appOptionsSuccess(options)).toEqual(action)
  })
})
