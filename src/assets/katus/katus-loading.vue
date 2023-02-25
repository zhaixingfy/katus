<template>
  <div class="katus-loading">
    <ul>
      <li
        v-for="n in len"
        :key="n"
        :style="{background: getColor(n), transform: 'rotate(' + (360 / len * n) + 'deg) translate(15px, 0)'}"
      ></li>
    </ul>
  </div>
</template>

<script>
export default {
  data() {
    return {
      len: 12
    }
  },
  methods: {
    getColor(idx) {
      const n = ~~((idx + 1) / (this.len + 2) * 255)
      return 'rgba(' + [n, n, n, 1].join(',') + ')'
    }
  },
}
</script>

<style lang="less" scoped>
@keyframes loading-rotate {
  0% {transform: rotate(0deg);}
  100% {transform: rotate(360deg);}
}
.katus-loading {
  width: 50px; height: 50px; vertical-align: middle; position: relative;
  display: inline-flex; align-items: center; justify-content: center;
  ul {
    width: 10px; height: 4px;
    position: relative;
    animation: loading-rotate 1s steps(12) infinite;
    li {
      width: 100%; height: 100%;
      position: absolute; left: 0; top: 0;
      background: rgba(0, 0, 0, .5);
      border-radius: 50%;
    }
  }
}
</style>