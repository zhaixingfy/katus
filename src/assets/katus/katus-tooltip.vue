<template>
  <transition name="fade">
    <div class="tooltip-wrapper" @click="showToolTip()">
      <slot></slot>
      <div
        :class="['katus-tooltip tooltip ' + (direction || 'top') + ' in']"
        v-if="isShow"
      >
        <div class="tooltip-arrow" style="left: 50%;"></div>
        <div class="tooltip-inner">{{msg}}</div>
      </div>
    </div>
  </transition>
</template>

<script>
export default {
  props: ['msg', 'direction'],
  data() {
    return {
      isShow: false,
    }
  },
  methods: {
    showToolTip() {
      const me = this

      me.isShow = true
      clearTimeout(me.timerShowToolTip)
      me.timerShowToolTip = setTimeout(() => {
        me.isShow = false
      }, 1500)
    },
  },
}
</script>

<style lang="less" scoped>
.tooltip-wrapper {
  position: relative; display: inline-block;
  .tooltip.top {}
  .tooltip.bottom {}

  .tooltip {
    position: absolute; left: 50%;
    white-space: nowrap; transform: translateX(-50%);
    display: block;
    &.top {bottom: 101%;}
    &.bottom {top: 101%;}
  }
}
</style>