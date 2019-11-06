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
  appSetUpUpdateSuccess,
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

export function* handleSetUpUpdateRequest({ payload }) {
  try {
    const { plugin_isSetUp } = yield call([wp.setUpUpdate(), "create"], payload)

    yield put(appSetUpUpdateSuccess(plugin_isSetUp))
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
    takeLatest(APP.SET_UP_UPDATE_REQUEST, handleSetUpUpdateRequest),
  ])
}
