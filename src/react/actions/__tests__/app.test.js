import {
  appUpdateLoading,
  appUpdateError,
  appOptionsRequest,
  appOptionsSuccess,
  appSetUpUpdateRequest,
  appSetUpUpdateSuccess,
  appOptionUpdateRequest,
  appOptionUpdateSuccess,
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
  it("should return the options success action", () => {
    const options = {
      a: 1,
    }
    const action = {
      type: APP.OPTIONS_SUCCESS,
      options,
    }

    expect(appOptionsSuccess(options)).toEqual(action)
  })
  it("should return the set up update request action", () => {
    const payload = "foo"
    const action = {
      type: APP.SET_UP_UPDATE_REQUEST,
      payload,
    }

    expect(appSetUpUpdateRequest(payload)).toEqual(action)
  })
  it("should return the set up update success action", () => {
    const plugin_isSetUp = true
    const action = {
      type: APP.SET_UP_UPDATE_SUCCESS,
      plugin_isSetUp,
    }

    expect(appSetUpUpdateSuccess(plugin_isSetUp)).toEqual(action)
  })
  it("should return the option update request action", () => {
    const payload = {
      key: "foo",
      value: "bar",
    }
    const action = {
      type: APP.OPTION_UPDATE_REQUEST,
      payload,
    }

    expect(appOptionUpdateRequest(payload)).toEqual(action)
  })
  it("should return the option update success action", () => {
    const option = {
      key: "foo",
      value: "bar",
    }
    const action = {
      type: APP.OPTION_UPDATE_SUCCESS,
      option,
    }

    expect(appOptionUpdateSuccess(option)).toEqual(action)
  })
})
