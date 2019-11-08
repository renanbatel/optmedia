import LazyLoad from "vanilla-lazyload"

// TODO: make lazy load enable/disable option
// TODO: make threshold value settable

export default {
  init: () => {
    const lazyLoadInstance = new LazyLoad({
      elements_selector: ".om-lazy-load",
      threshold: 150,
    })

    return lazyLoadInstance
  },
  load: (lazyLoadInstance) => {
    lazyLoadInstance.update()
  },
}
