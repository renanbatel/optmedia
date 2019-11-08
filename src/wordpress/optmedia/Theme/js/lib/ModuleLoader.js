export default class ModuleLoader {

  constructor(modules, parameters) {
    this.modules = modules
    this.parameters = {
      init: parameters,
    }
  }

  fire(target, key, event = "init") {

    if (target[event] && typeof target[event] === "function") {
      this.parameters[key] = target[event](
        event === "init" ? this.parameters.init : this.parameters[key]
      )
    }
  }

  load() {
    document.addEventListener("DOMContentLoaded", () => {
      this.modules.forEach((target, key) => this.fire(target, key))
    })
    window.addEventListener("load", () => {
      this.modules.forEach((target, key) => this.fire(target, key, "load"))
      this.modules.forEach((target, key) => this.fire(target, key, "finalize"))
    })
  }

}
