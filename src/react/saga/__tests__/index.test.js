import rootSaga from "../index"

describe("saga", () => {
  it("should create the root saga", () => {
    const saga = rootSaga()

    expect(saga.next().value).toBeDefined()
  })
})
