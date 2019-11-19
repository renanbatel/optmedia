import { APP } from "../constants/actions"

export const initialState = {
  loading: true,
  error: null,
  options: {},
}

export default (state = initialState, action) => {
  switch (action.type) {
    case APP.UPDATE_LOADING: {
      const { loading } = action

      return {
        ...state,
        loading,
      }
    }
    case APP.UPDATE_ERROR: {
      const { error } = action

      return {
        ...state,
        error,
      }
    }
    case APP.OPTIONS_SUCCESS: {
      const { options } = action

      return {
        ...state,
        options,
      }
    }
    case APP.SET_UP_UPDATE_SUCCESS: {
      const { plugin_isSetUp } = action

      return {
        ...state,
        options: {
          ...state.options,
          plugin_isSetUp,
        },
      }
    }
    case APP.OPTION_UPDATE_SUCCESS: {
      const { key, value } = action.option

      return {
        ...state,
        options: {
          ...state.options,
          [key]: value,
        },
      }
    }
    default:
      return state
  }
}
