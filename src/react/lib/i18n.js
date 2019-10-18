import i18n from "i18next"
import { initReactI18next } from "react-i18next"

import enCommon from "../languages/en/common.json"
import ptCommon from "../languages/pt/common.json"

const resources = {
  en: {
    common: enCommon,
  },
  pt: {
    common: ptCommon,
  },
}

i18n
  .use(initReactI18next)
  .init({
    resources,
    lng: __OPTMEDIA__.language,
    fallbackLng: "en",
    interpolation: {
      escapeValue: false,
    },
  })

export default i18n
