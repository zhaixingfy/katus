<template>
  <div class="katus-theater">
    katus-theater
  </div>
</template>

<script>
export default {
  name: 'katus-theater',
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
.katus-theater {}
</style>