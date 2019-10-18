import { APP } from "../constants/actions"

export const appUpdateLoading = (loading) => ({
  type: APP.UPDATE_LOADING,
  loading,
})

export const appOptionsRequest = () => ({
  type: APP.OPTIONS_REQUEST,
})

export const appOptionsSuccess = (options) => ({
  type: APP.OPTIONS_SUCCESS,
  options,
})
