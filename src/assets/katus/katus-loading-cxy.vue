<template>
  <transition name="fade">
    <div class="katus-loading-cxy"
      v-if="isShow"
    >
      <katus-loading></katus-loading>
    </div>
  </transition>
</template>

<script>
export default {
  props: ['isShow']
}
</script>

<style lang="less" scoped>
.katus-loading-cxy {
  width: 100%; height: 100%;
  position: absolute; left: 0; top: 0; z-index: 10;
  display: flex; align-items: center; justify-content: center;
  background: rgba(255, 255, 255, .9);
}
</style>