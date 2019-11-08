// add theme js-code here

import ModuleLoader from "./lib/ModuleLoader"

import lazyLoad from "./common/lazy-load"

const moduleLoader = new ModuleLoader([
  lazyLoad,
])

moduleLoader.load()
