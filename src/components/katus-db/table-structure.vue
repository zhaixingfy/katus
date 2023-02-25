<template>
  <div class="table-structure">
    table-structure
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
.table-structure {}
</style>