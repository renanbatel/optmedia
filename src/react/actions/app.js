import { APP } from "../constants/actions"

export const appUpdateLoading = (loading) => ({
  type: APP.UPDATE_LOADING,
  loading,
})

export const appUpdateError = (error) => ({
  type: APP.UPDATE_ERROR,
  error,
})

export const appOptionsRequest = () => ({
  type: APP.OPTIONS_REQUEST,
})

export const appOptionsSuccess = (options) => ({
  type: APP.OPTIONS_SUCCESS,
  options,
})

export const appSetUpUpdateRequest = (payload) => ({
  type: APP.SET_UP_UPDATE_REQUEST,
  payload,
})

export const appSetUpUpdateSuccess = (plugin_isSetUp) => ({
  type: APP.SET_UP_UPDATE_SUCCESS,
  plugin_isSetUp,
})

export const appOptionUpdateRequest = (payload) => ({
  type: APP.OPTION_UPDATE_REQUEST,
  payload,
})

export const appOptionUpdateSuccess = (option) => ({
  type: APP.OPTION_UPDATE_SUCCESS,
  option,
})
