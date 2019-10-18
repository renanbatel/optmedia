import {
  all,
  call,
  takeLatest,
  put,
} from "redux-saga/effects"

import wp from "../services/wp"
import { APP } from "../constants/actions"
import { appOptionsSuccess } from "../actions/app"

export function* handleAppOptionsRequest() {
  try {
    const response = yield call([wp, "pluginOptions"])

    yield put(appOptionsSuccess(response.data))
  } catch (error) {
    // TODO: handle errors
    console.error(error)
  }
}

export default function* watchApp() {
  yield all([
    takeLatest(APP.OPTIONS_REQUEST, handleAppOptionsRequest),
  ])
}
