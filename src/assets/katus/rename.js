const fs = require('fs')

;[
  // 'catus-loading-cxy.vue',
  // 'catus-loading.vue',
  // 'catus-pagin.vue',
  // 'catus-resizer.vue',
  // 'catus-switch.vue',
].forEach((name) => {
  const path = './' + name
  fs.renameSync(path, path.replace('catus', 'katus'))
})