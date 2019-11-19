import wp from "../../services/wp"

const wpMock = [
  "pluginOptions",
  "serverDiagnostic",
  "setUpUpdate",
  "optionUpdate",
]

export const mockWp = () => {
  wpMock.forEach((key) => {
    wp[key] = jest.fn()
  })
}

export const unmockWp = mockWp
