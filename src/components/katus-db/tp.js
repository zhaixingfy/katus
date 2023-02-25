const fs = require('fs')

const arr = [
  'table-data',
  'table-structure',
  'info-db',
  'info-server',
]

arr.forEach((name) => {
  let path = `./${name}.vue`

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