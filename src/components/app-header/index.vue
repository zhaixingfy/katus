<template>
  <div class="flex-h app-header">
    <div class="logo">
      <img src="../../../img/logo.png" alt="" class="logo-img">
      <strong>Katus Admin</strong>
    </div>
    <div class="auto-flex">
      <div class="list-nav">
        <section
          v-for="(item, idx) in $root.nav.list"
          :key="idx"
          :class="{on: item.com === r.com}"
          @click="$root.updateRouter({com: item.com})"
        >{{item.name}}</section>
      </div>
    </div>
    <div class="window-ctrl space-items">
      <i class="bi-dash-lg"></i>
      <i class="bi-fullscreen"></i>
      <i class="bi-front"></i>
      <i class="bi-x-lg"></i>
    </div>
  </div>
</template>

<script>
export default {
  name: 'app-header',
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
.app-header {
  -webkit-app-region: drag;
  background: rgba(40, 40, 40, 1.0);
  color: rgba(180, 180, 180, 1.0);
  line-height: 3.2em; padding: 0 15px;
  font-size: 16px;
  white-space: nowrap; user-select: none;
  .logo {
    padding-left: 30px;
    .logo-img {
      width: 24px;
      position: absolute; left: 0; top: 10px;
    }
  }
  .list-nav {
    display: flex;
    & > section {
      padding: 0 15px; cursor: pointer;
      cursor: pointer;
      -webkit-app-region: no-drag;
      &.on {color: #fff;}
    }
  }
  .window-ctrl {
    -webkit-app-region: no-drag;
  }
}
</style>