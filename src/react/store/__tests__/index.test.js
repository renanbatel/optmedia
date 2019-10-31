import makeStore from "../index"

describe("store", () => {
  it("should create the store", () => {
    const store = makeStore()

    expect(store).toBeDefined()
  })
})
