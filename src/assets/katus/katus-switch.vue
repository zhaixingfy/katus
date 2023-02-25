<template>
  <div :class="['katus-switch', {on: active}]"
    @click="!disabled && $emit('change', !active)"
    :style="{cursor: disabled ? 'not-allowed' : ''}"
  >
    <div class="circle"></div>
  </div>
</template>

<script>
export default {
  model: {
    prop: 'active',
    event: 'change'
  },
  props: {
    active: [String, Number, Boolean],
    disabled: [Boolean],
  },
}
</script>

<style lang="less" scoped>
.katus-switch {
  width: 58px; height: 34px; border-radius: 34px;
  background: #ddd; cursor: pointer; display: inline-block; vertical-align: middle;
  position: relative; user-select: none;
  transition: .2s all;
  & > .circle {
    width: 30px; height: 30px; background: #fff; border-radius: 50%;
    position: absolute; left: 2px; top: 2px;
    transition: .2s all;
  }
  &.on {
    background: #0c0;
    .circle {
      left: calc(100% - 32px);
    }
  }
}
</style>