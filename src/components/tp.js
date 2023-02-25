const fs = require('fs')

const arr = [
  // 'app-header',
  // 'katus-db',
  // 'katus-fs',
  // 'katus-theater',
  // 'katus-algo',
  // 'katus-forumn',
  // 'sys-alert',
  // 'sys-confirm',
]

arr.forEach((name) => {
  let path = './' + name

  try {
    fs.mkdirSync(path)
  } catch (e) {
    console.log(e)
  }

  path += '/index.vue'

  fs.writeFileSync(path, `
<template>
  <div class="${name}">
    ${name}
  </div>
</template>

<script>
export default {
  data() {
    return {}
  },
  computed: {
    vm() {
      return this.$root
    },
    r() {
      return this.$root.router
    },
  },
  methods: {},
  async mounted() {
    
  },
}
</script>

<style lang="less" scoped>
.${name} {}
</style>
  `.trim())
})