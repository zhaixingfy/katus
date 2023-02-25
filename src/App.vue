<template>
  <div id="app-main">
    <div class="flex-v app-main">
      <app-header></app-header>
      <div class="auto-flex app-body">
        <Transition
          v-for="(item, idx) in nav.list"
          :key="idx"
          :enter-active-class="'animate__' + vm.animateCss.cur.enter"
          :leave-active-class="'animate__' + vm.animateCss.cur.leave"
        >
          <component
            :class="['animate__animated']"
            :is="item.com"
            v-if="item.com === r.com"
          ></component>
        </Transition>
      </div>
    </div>

    <sys-confirm></sys-confirm>
    <sys-alert></sys-alert>
    
    <textarea id="cpInput"></textarea>
  </div>
</template>

<script>
import animateCss from './assets/js/animate-css.js'
import AppHeader from './components/app-header/index.vue'
import KatusDb from './components/katus-db/index.vue'
import KatusFs from './components/katus-fs/index.vue'
import KatusTheater from './components/katus-theater/index.vue'
import KatusAlgo from './components/katus-algo/index.vue'
import KatusForumn from './components/katus-forumn/index.vue'

import SysAlert from './components/sys-alert/index.vue'
import SysConfirm from './components/sys-confirm/index.vue'

export default {
  data() {
    const is = {
      local: location.port === '3377'
    }

    return {
      is,
      animateCss,
      router: {},
      dataAlert: {
        isShow: false,
        title: '',
        msg: '',
      },
      dataConfirm: {
        isShow: false,
        title: '',
        msg: '',
      },
      nav: {
        list: [
          {name: '数据库管理', com: 'katus-db'},
          {name: '文件管理', com: 'katus-fs'},
          {name: '家庭影院', com: 'katus-theater'},
          {name: '算法与数据结构', com: 'katus-algo'},
          {name: '论坛', com: 'katus-forumn'},
        ]
      }
    }
  },
  computed: {
    vm() {
      return this.$root
    },
    r() {
      return this.$root.router
    },
  },
  watch: {
    router: {
      deep: true,
      handler(newVal) {
        const vm = this.$root
        const url = location.origin + location.pathname + location.search + '#' + JSON.stringify(newVal)

        console.warn('%crouter changed', 'color: red', vm.isRouterPush, vm.isRouterPush ? 'pushState' : 'replaceState', JSON.stringify(newVal))
        console.log('------------------------------------------------------------------------')
        history[vm.isRouterPush ? 'pushState' : 'replaceState']({}, '', url)
        delete vm.isRouterPush
      },
    },
    'r.com'() {
      const vm = this.$root
      vm.animateCss.cur.enter = vm.animateCss.enter.getOne()
      vm.animateCss.cur.leave = vm.animateCss.leave.getOne()
    },
  },
  methods: {
    alert(msg, title) {
      const vm = this.$root
      vm.dataAlert.isShow = true
      vm.dataAlert.title = title || ''
      vm.dataAlert.msg = msg
    },
    confirm(msg, title) {
      const vm = this.$root
      vm.dataConfirm.isShow = true
      vm.dataConfirm.title = title || ''
      vm.dataConfirm.msg = msg
    },
    async copy(str) {
      str = typeof str === 'string' ? str : JSON.stringify(str)
      
      try {
        await navigator.clipboard.writeText(str)
      } catch (e) {
        const cpInput = document.getElementById('cpInput')
        cpInput.value = str
        cpInput.focus()
        cpInput.select()
        document.execCommand('copy')
      }
    },
    post(url, data) {
      return new Promise((resolve, reject) => {
        const xhr = new XMLHttpRequest()
        const fm = new FormData()

        for (let key in data) {
          if (data[key] === undefined) continue
          fm.append(key, data[key])
        }

        xhr.open('POST', url, true)
        xhr.send(fm)
        xhr.onreadystatechange = () => {
          if (xhr.readyState === 4) {
            if (xhr.status === 200) {
              let data

              try {
                data = JSON.parse(xhr.responseText)
                if (data.errCode) {
                  reject(data)
                }
              } catch (e) {
                data = xhr.responseText
              }

              resolve(data)
            } else {
              console.log('后台错误')
            }
          }
        }
      })
    },
    updateRouter(r, isRouterPush = true) {
      console.warn('updateRouter', JSON.stringify(r))
      const vm = this.$root

      vm.isRouterPush = isRouterPush
      vm.router = {
        ...vm.router,
        ...r
      }

      setTimeout(() => {delete vm.isRouterPush}, 1)
    },
    initRouter() {
      const vm = this.$root
      let r

      try {
        r = JSON.parse(decodeURIComponent(location.hash.substring(1)))
      } catch (e) {
        r = {}
      }

      vm.router = r
    },
    formatCreateView(str) {
      let depth = 0
      
      return str.replace(/(\()|(\))|(,)/g, function(str) {
        if (str === '(') depth++
        if (str === ')') depth--

        const space = ''.padEnd(depth * 2, ' ')

        switch (str) {
          case '(':
            return '(\n' + space
          case ')':
            return '\n' + space + ')'
          case ',':
            return ',\n' + space
        }
      })
    },
 

 async initKeyEvents() {
   const vm = this.$root
   const keyMap = {
     "13":"enter",
     "27":"esc",
     "37": "left",
     "38": "up",
     "39": "right",
     "40": "down",
     "65":"a", "66":"b", "67":"c", "68":"d", "69":"e",
     "70":"f", "71":"g", "72":"h", "73":"i", "74":"j",
     "75":"k", "76":"l", "77":"m", "78":"n", "79":"o",
     "80":"p", "81":"q", "82":"r", "83":"s", "84":"t",
     "85":"u", "86":"v", "87":"w", "88":"x", "89":"y",
     "90":"z", "91":"[", "92":"\\", "93":"]", "94":"^",
     "95":"_", "96":"`",
   }

   document.onkeydown = (e) => {
     const sKey = keyMap[e.keyCode]

     /*if (e.ctrlKey && e.shiftKey && e.altKey) {

     } else if (e.ctrlKey && e.shiftKey) {

     } else if (e.ctrlKey && e.altKey) {

     } else if (e.shiftKey && e.altKey) {

     } else */if (e.ctrlKey) {
       switch (sKey) {
         case 'y':
           if (e.target.closest('form')) return
           history.forward()
           break;
         case 'z':
           if (e.target.closest('form')) return
           history.back()
           break;
       }
     }/* else if (e.shiftKey) {

     }*/ else if (e.altKey) {
       switch (sKey) {
         case 'n':
           vm.mysql.showDialogOperDB('add')
           break
         case 'r':
           if (vm.mysql) {
             vm.ld.sqlHis.isShow = !vm.ld.sqlHis.isShow
             vm.ld.sqlHis.isShow && vm.$nextTick(() => {
               const editor = document.querySelector('.mysql .resizer-run-sql textarea')
               editor.focus()
             })
           }
           break
       }
     } else {
       switch (sKey) {
         case 'esc':
           if(document.activeElement.closest('form')) {
             return
           }

           // if (vm.dataAlert.isShow) {
           //   vm.cbAlert && vm.cbAlert()
           // } else if (vm.dataConfirm.isShow) {
           //   vm.cbConfirmCancel && vm.cbConfirmCancel()
           // } else if (vm.mysql) {
           //   if (vm.mysql.curDialogSelectCollation) { // 关闭字符集选择面板
           //     vm.mysql.curDialogSelectCollation.isShow = false
           //   } else if (vm.mysql.dataAlertRow.isShow) { // 显示一行数据
           //     vm.mysql.dataAlertRow.isShow = false
           //   } else if (vm.mysql.dataEditRow.isShow) { // 编辑一行
           //     vm.mysql.dataEditRow.isShow = false
           //   } else if (vm.mysql.db.oper.fm.isShow) { // 操作数据库
           //     vm.mysql.db.oper.fm.isShow = false
           //   } else if (vm.mysql.field.oper.fm.isShow) { // 操作表字段
           //     vm.mysql.field.oper.fm.isShow = false
           //   } else if (vm.mysql.export.isShow) { // 导出库表
           //     vm.mysql.export.isShow = false
           //   } else if (vm.ld.sqlHis.isShow) { // sql 运行面板
           //     vm.ld.sqlHis.isShow = false
           //   }
           // }
           break
       }
     }
   }
 }, },
  components: {
    AppHeader,
    KatusDb,
    KatusFs,
    KatusTheater,
    KatusAlgo,
    KatusForumn,

    SysAlert,
    SysConfirm,
  },
  async mounted() {
    const vm = this.$root

    window.onpopstate = () => {
      vm.initRouter()
    }
    window.onpopstate()

    if (!vm.nav.list.some(v => v.com === vm.router.com)) {
      vm.updateRouter({com: vm.nav.list[0].com}, false)
    }
  },
}

document.ondragover = (e) => {
  e.preventDefault()
}

document.ondrop = (e) => {
  e.preventDefault()
  console.log(e.dataTransfer.files[0])
}
</script>

<style lang="less" scoped>
#app-main {
  height: 100%;
  .app-main {
    .app-body {
      position: relative; overflow: hidden;
      & > div {
        width: 100%; height: 100%;
        position: absolute; left: 0; top: 0;
        background: #fff;
        overflow: auto;
      }
    }
  }
}

#cpInput {
  width: 800px; height: 400px;
  position: fixed; left: 10px; bottom: 110%;
}
</style>