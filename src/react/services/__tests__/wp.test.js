import wp from "../wp"

describe("services/wp", () => {
  it("should create a proper wordpress api client", () => {
    expect(wp._options.endpoint).toBe(__OPTMEDIA__.endpoint)
    expect(wp._options.nonce).toBe(__OPTMEDIA__.nonce)
  })
})
