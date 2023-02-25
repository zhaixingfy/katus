<template>
  <div class="katus-resizer" ref="el"
    @mousedown="handleMouseDown"
    :style="{
      left: layout_.left + 'px',
      top: layout_.top + 'px',
      width: layout_.width + 'px',
      height: layout_.height + 'px',
      minWidth: layout_.minWidth + 'px',
      minHeight: layout_.minHeight + 'px',
    }"
  >
    <slot></slot>
    <div class="resize">
      <div class="line">
        <div class="l"></div>
        <div class="t"></div>
        <div class="r"></div>
        <div class="b"></div>
      </div>
      <div class="corner">
        <div class="lt"></div>
        <div class="rt"></div>
        <div class="rb"></div>
        <div class="lb"></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ['layout'],
  data() {
    return {}
  },
  computed: {
    layout_() {
      const layout = this.layout || {}
      
      return {
        left: layout.left || 0,
        top: layout.top || 0,
        width: layout.width || 0,
        height: layout.height || 0,
        minWidth: layout.minWidth || 400,
        minHeight: layout.minHeight || 300,
      }
    },
  },
  methods: {
    handleMouseDown(e) {
      const me = this
      const el = e.target.closest('.katus-resizer')
      const offsetParent = el.offsetParent
      const elTitle = e.target.closest('.handle-drag')
      const elResize = e.target.closest('.resize')
      const x1 = e.clientX
      const y1 = e.clientY

      const ol = el.offsetLeft
      const ot = el.offsetTop
      const ow = el.offsetWidth
      const oh = el.offsetHeight
      const osl = offsetParent.scrollLeft
      const ost = offsetParent.scrollTop

      const emitLayout = () => {
        const el = me.$refs.el
        const layout = {
          left: el.offsetLeft,
          top: el.offsetTop,
          width: el.offsetWidth,
          height: el.offsetHeight,
          minWidth: parseInt(getComputedStyle(el)['minWidth']),
          minHeight: parseInt(getComputedStyle(el)['minHeight']),
        }

        me.$emit('change-layout', layout)
      }

      if (e.target.closest('.glyphicon-remove')) return

      if (elTitle) {
        document.onmousemove = (e) => {
          const x2 = e.clientX
          const y2 = e.clientY

          let x = x2 - x1 + ol + (offsetParent.scrollLeft - osl)
          let y = y2 - y1 + ot + (offsetParent.scrollTop - ost)

          x < 0 && (x = 0)
          y < 0 && (y = 0)

          el.style.left = x + 'px'
          el.style.top = y + 'px'
        }

        document.onmouseup = () => {
          document.onmousemove = null
          document.onmouseup = null
          emitLayout()
        }
      } else if (elResize) {
        const isL = e.target.className.indexOf('l') > -1
        const isT = e.target.className.indexOf('t') > -1
        const minWidth = me.layout_.minWidth
        const minHeight = me.layout_.minHeight
        const attrs = ({
          'l': ['left', 'width'],
          't': ['top', 'height'],
          'r': ['width'],
          'b': ['height'],
          'lt': ['left', 'top', 'width', 'height'],
          'rt': ['top', 'width', 'height'],
          'rb': ['width', 'height'],
          'lb': ['left', 'width', 'height'],
        })[e.target.className]

        document.onmousemove = (e) => {
          const x2 = e.clientX
          const y2 = e.clientY
          const disX = isL ? x1 - x2 : x2 - x1
          const disY = isT ? y1 - y2 : y2 - y1
          const o = {
            left: -disX + ol,
            top: -disY + ot,
            width: disX + ow,
            height: disY + oh,
          }

          if (o.left < 0 && isL) {
            o.width += o.left
            o.left = 0
          }
          
          if (o.top < 0 && isT) {
            o.height += o.top
            o.top = 0
          }

          if (o.width < minWidth) {
            o.left = ol + ow - minWidth
            o.width = minWidth
          }

          if (o.height < minHeight) {
            o.top = ot + oh - minHeight
            o.height = minHeight
          }

          attrs.forEach((k) => {
            el.style[k] = o[k] + 'px'
          })
        }

        document.onmouseup = () => {
          document.onmousemove = null
          document.onmouseup = null
          emitLayout()
        }
      }

      return false
    },
  },
  mounted() {

  },
}
</script>

<style lang="less" scoped>
.katus-resizer {
  width: 400px; height: 400px;
  min-width: 400px; min-height: 300px;
  position: absolute; left: 0; top: 0; z-index: 10;
  border-radius: 4px; overflow: hidden;
  background: #fff;

  & > .flex-v {
    & > .glass-bar {
      cursor: move; user-select: none;
    }
  }

  .resize {
    user-select: none;
    & > .line {
      & > div {
        width: 6px; height: 6px;
        // background: rgba(255, 0, 0, .5);
        position: absolute; left: 0; top: 0;
        &.l {cursor: w-resize; height: 100%;}
        &.t {cursor: n-resize; width: 100%;}
        &.r {cursor: e-resize; left: auto; right: 0; height: 100%;}
        &.b {cursor: s-resize; top: auto; bottom: 0; width: 100%;}
      }
    }
    & > .corner {
      & > div {
        width: 12px; height: 12px;
        // background: rgba(255, 0, 0, .5);
        position: absolute; left: 0; top: 0;
        &.lt {cursor: nw-resize;}
        &.rt {cursor: ne-resize; left: auto; right: 0;}
        &.rb {cursor: se-resize; left: auto; right: 0; top: auto; bottom: 0;}
        &.lb {cursor: sw-resize; top: auto; bottom: 0;}
      }
    }
  }
}
</style>