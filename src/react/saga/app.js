import {
  all,
  call,
  takeLatest,
  put,
} from "redux-saga/effects"

import wp from "../services/wp"
import { ERROR } from "../constants"
import { APP } from "../constants/actions"
import {
  appOptionsSuccess,
  appUpdateLoading,
  appUpdateError,
} from "../actions/app"

export function* handleAppOptionsRequest() {
  try {
    const { options } = yield call([wp, "pluginOptions"])

    yield put(appOptionsSuccess(options))
    yield put(appUpdateLoading(false))
  } catch (error) {
    yield put(appUpdateError({
      code: ERROR.PLUGIN_API_REQUEST,
      instance: error,
    }))
  }
}

export default function* watchApp() {
  yield all([
    takeLatest(APP.OPTIONS_REQUEST, handleAppOptionsRequest),
  ])
}
