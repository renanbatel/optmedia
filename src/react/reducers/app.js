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
    default:
      return state
  }
}
