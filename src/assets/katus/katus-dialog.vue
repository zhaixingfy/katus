<template>
  <transition name="fade">
    <div class="katus-dialog" v-if="isShow">
      <slot></slot>
    </div>
  </transition>
</template>

<script>
export default {
  props: ['isShow'],
  data() {
    return {}
  },
}
</script>

<style lang="less" scoped>
.katus-dialog {
  width: 100%; height: 100%;
  position: fixed; left: 0; top: 0; z-index: 100;
  background: rgba(0, 0, 0, .5); padding: 30px;
  display: flex; align-items: center; justify-content: center;
}
</style>

<style lang="less">
.katus-dialog {
  & > * {
    min-width: 300px; min-height: 100px;
    background: #fff; overflow: hidden; border-radius: 4px;
    box-shadow: 0 0 0 6px rgba(0, 0, 0, .25);

    .dialog-body {
      max-height: calc(100vh - 150px); overflow: auto;
    }
    .dialog-footer {
      padding: 8px 15px; border-top: 1px solid #ccc;
      text-align: right; background: #f3f6f9;
    }
  }
}
</style>