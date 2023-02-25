/*global rand*/

window.rand = (min, max) => {
  return Math.floor(Math.random() * (max - min + 1))
}

window.sleep = (time) => {
  return time > 0 && new Promise((next) => {
    setTimeout(next, time)
  })
}

Array.prototype.first = function() {
  return this[0]
}

Array.prototype.last = function() {
  return this[this.length - 1]
}

Array.prototype.swap = function(a, b) {
  const t = this[a]
  this[a] = this[b]
  this[b] = t
  return this
}

Array.prototype.shuffle = function() {
  for (let i = this.length - 1; i > 0; i--) {
    this.swap(i, Math.floor(Math.random() * (i + 1)))
  }
  
  return this
}

Array.prototype.getOne = function() {
  return this[rand(0, this.length - 1)]
}