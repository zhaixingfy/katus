<template>
  <div class="katus-db">
    <div class="katus-db-main">
      <div class="flex-v panel-nav">
        <div class="glb-lg panel-nav-header">
          <div class="auto-flex">
            <i class="glyphicon glyphicon-send"></i>
            <strong>连接管理</strong>
          </div>
          <div>
            <button class="btn btn-primary"
              @click="$root.alert('alert')"
            >
              <i class="glyphicon glyphicon-question-sign"></i>
            </button>
            <button class="btn btn-info"
              @click="$root.confirm('confirm')"
            >
              <i class="glyphicon glyphicon-download"></i>
            </button>
            <button class="btn btn-success">
              <i class="glyphicon glyphicon-cog"></i>
            </button>
          </div>
        </div>
        <div class="auto-flex panel-nav-body">
          <div class="flex-v">
            <div class="glb">
              <div class="auto-flex">
                <i class="glyphicon glyphicon-hdd"></i>
                <strong>数据库 ({{db.list.length}})</strong>
              </div>
              <div>
                <i class="glyphicon glyphicon-export"></i>
                <i class="glyphicon glyphicon-import"></i>
                <i class="glyphicon glyphicon-plus"></i>
              </div>
            </div>
            <div class="auto-flex">
              <ul>
                <li
                  v-for="(item, idx) in db.list"
                  :key="idx"
                  :class="{on: item.name === curDB}"
                  :title="item.name"
                  @click="updateCurDB(item)"
                >{{item.name}}</li>
              </ul>
            </div>
            <div class="glbs">
              <div class="glb">
                <i class="glyphicon glyphicon-play"></i>
                <span>执行SQL (alt + r)</span>
              </div>
              <div class="glb">
                <i class="bi-github"></i>
                <span>Github</span>
              </div>
            </div>
          </div>
          <div class="flex-v">
            <div class="glb">
              <div class="auto-flex">
                <i class="bi-table"></i>
                <strong>数据表 ({{table.list.length}})</strong>
              </div>
              <div>
                <i class="glyphicon glyphicon-export"></i>
                <i class="glyphicon glyphicon-import"></i>
                <i class="glyphicon glyphicon-plus"></i>
              </div>
            </div>
            <div class="auto-flex">
              <ul>
                <li
                  v-for="(item, idx) in table.list"
                  :key="idx"
                  :class="{on: item.name === curTable}"
                  :title="item.name"
                  @click="updateCurTable(item)"
                >{{item.name}}</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="glb panel-nav-footer">
          <div class="auto-flex">
            <span class="mr">技术支持</span>
            <strong>
              <a href="http://codding.cn" target="_blank" class="text-primary">http://codding.cn</a>
            </strong> 
          </div>
          <div>
            <a target="_blank" href="https://qm.qq.com/cgi-bin/qm/qr?k=XRAaQM9Dh4NcpPlm3ILx1TI88S_pssb5&amp;jump_from=webapi">
              <img border="0" src="../../../img/qq-group.png" alt="KatusAdmin | 官方群" title="KatusAdmin | 官方群"></a>
          </div>
        </div>
      </div>
      <div class="auto-flex panel-main">
        <div class="flex-v">
          <div class="glb-lg">
            <div class="auto-flex">
              <span
                :title="dbTable"
                @dblclick="$root.copy(dbTable)"
              >
                <i class="glyphicon glyphicon-list-alt"></i>
                <strong>{{dbTable}}</strong>
              </span>
            </div>
            <div>
              <div class="btn-group">
                <button
                  v-for="(item, idx) in tab.list"
                  :key="idx"
                  :class="['btn btn-' + (item.com === curTab ? 'success' : 'default')]"
                  @click="$root.updateRouter({curTab: item.com})"
                >{{item.name}}</button>
              </div>
            </div>
          </div>
          <div class="auto-flex">
            <component :is="curTab"></component>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
/*global sleep*/

import TableData from './table-data.vue'
import TableStructure from './table-structure.vue'
import InfoDb from './info-db.vue'
import InfoServer from './info-server.vue'

export default {
  name: 'katus-db',
  data() {
    return {
      page: {
        total: 0,
        sizes: Array(4).fill().map((_, idx) => {
          return [1, 2, 5].map((n) => {
            return Number(n + 'e' + (idx + 1))
          })
        }).flat(),
      },
      tab: {
        list: [
          {name: '浏览数据', com: 'table-data'},
          {name: '库表结构', com: 'table-structure'},
          {name: '数据库信息', com: 'info-db'},
          {name: '服务器信息', com: 'info-server'},
        ],
      },
      env: {
        charset: [],
        collation: [],
        dbInfo: {},
        engines: [],
        globalStatus: [],
        now: 0,
        php_ini: {},
        server: {},
        serverLang: '',
        vars: [],
        version: '',
      },
      db: {
        sc: '',
        list: [],
        fm: {
          d: {
            name: '',
            charset: '',
          },
          od: {
            name: '',
            charset: '',
          },
        },
      },
      table: {
        sc: {},
        list: [],
      },
      field: {
        list: []
      },
      tableData: {
        isLoading: true,
        list: [],
      },
    }
  },
  computed: {
    vm() {
      return this.$root
    },
    r() {
      return this.$root.router
    },
    curTab() {
      return (
        this.tab.list.some(v => v.com === this.r.curTab) ?
          this.r.curTab : this.tab.list[0].com
      )
    },
    curDB() {
      return (
        this.db.list.some(v => v.name === this.r.curDB) ?
          this.r.curDB : this.db.list[0]?.name
      ) || ''
    },
    curTable() {
      return (
        this.table.list.some(v => v.name === this.r.curTable) ?
          this.r.curTable : this.table.list[0]?.name
      ) || ''
    },
    dbTable() {
      return this.curDB + (this.curTable ? '.' + this.curTable : '')
    },
    fields() {
      return this.field.list.map(v => v.Field)
    },
    fieldKeys() {
      return (
        this.field.list.length > 0 ?
          Object.keys(this.field.list[0]) : []
      )
    },
  },
  watch: {
    'r.curDB'() {
      this.lsTable()
    },
    'r.curTable'() {
      this.lsTableData()
    },
  },
  methods: {
    async updateCurDB(item) {
      this.page.total = 0
      this.$root.updateRouter({curDB: item.name, pageCur: 1})
      await this.lsTable()
    },
    async updateCurTable(item) {
      this.page.total = 0
      this.$root.updateRouter({curTable: item.name, pageCur: 1})
      await this.lsTableData()
    },
    async lsDef() {
      const me = this
      const vm = me.$root
      const signLsDef = me.signLsDef = Math.random()

      me.db.list = []
      me.db.sc = ''
      me.table.list = []
      me.table.sc = {}
      me.field.list = []
      me.page.total = 0
      me.tableData.list = []

      await sleep(1)
      if (signLsDef !== me.signLsDef) return

      me.tableData.isLoading = true

      return await vm.post('/api/katus.php', {
        a: 'ls-def'
      }).then(async () => {
        if (signLsDef !== me.signLsDef) return
        await me.lsDB()
      })
    },
    async lsDB() {
      const me = this
      const vm = me.$root
      const signLsDB = me.signLsDB = Math.random()

      me.db.list = []
      me.db.sc = ''
      me.table.list = []
      me.table.sc = {}
      me.field.list = []
      me.page.total = 0
      me.tableData.list = []

      await sleep(1)
      if (signLsDB !== me.signLsDB) return

      me.tableData.isLoading = true

      return await vm.post('/api/katus.php', {
        a: 'ls-db'
      }).then(async (data) => {
        if (signLsDB !== me.signLsDB) return

        me.db.list = data.map((name) => {
          return {name, checked: false}
        })
        await me.lsTable()
      })
    },
    async lsTable() {
      const me = this
      const vm = me.$root
      const signLsTable = me.signLsTable = Math.random()

      me.db.sc = ''
      me.table.list = []
      me.table.sc = {}
      me.field.list = []
      me.page.total = 0
      me.tableData.list = []

      if (!me.curDB) {
        me.tableData.isLoading = false
        return
      }
      await sleep(1)
      if (signLsTable !== me.signLsTable) return

      me.tableData.isLoading = true

      return await vm.post('/api/katus.php', {
        a: 'ls-table',
        curDB: me.curDB,
      }).then(async (data) => {
        if (signLsTable !== me.signLsTable) return

        me.db.sc = data.sc
        me.table.list = data.list.map((name) => {
          return {name, checked: false}
        })
        await me.lsTableData()
      })
    },
    async lsTableData() {
      const me = this
      const vm = me.$root
      const signLsTableData = me.signLsTableData = Math.random()

      me.tableData.list = []

      if (!me.curTable) {
        me.tableData.isLoading = false
        return
      }
      await sleep(1)
      if (signLsTableData !== me.signLsTableData) return

      me.tableData.isLoading = true

      return await vm.post('/api/katus.php', {
        a: 'ls-table-data',
        curDB: me.curDB,
        curTable: me.curTable,
        pageCur: me.r.pageCur || 1,
        pageSize: me.r.pageSize || 100,
      }).then(async (data) => {
        if (signLsTableData !== me.signLsTableData) return

        me.table.sc = data.sc
        me.field.list = data.listField
        me.page.total = data.page.total
        me.tableData.list = data.list
        me.tableData.isLoading = false
      })
    },
  },
  components: {
    TableData,
    TableStructure,
    InfoDb,
    InfoServer,
  },
  async mounted() {
    const me = this
    const vm = me.$root

    vm.katusDB = this

    isNaN(Number(me.r.pageCur)) && vm.updateRouter({pageCur: 1}, false)
    isNaN(Number(me.r.pageSize)) && vm.updateRouter({pageSize: 10}, false)

    await me.lsDef()
  },
  beforeUnmount() {
    delete this.$root.db
  },
}
</script>

<style lang="less" scoped>
.katus-db {
  .katus-db-main {
    // min-width: 1100px;
    // min-height: 800px;
    display: flex; height: 100%;
    .panel-nav {
      overflow: hidden; background: #f3f6f9;
      border-right: 1px solid #ccc;
      .panel-nav-header {

      }
      .panel-nav-body {
        display: flex;
        & > div {
          max-width: 260px;
          &:not(:first-child) {border-left: 1px solid #ccc;}
          &:not(:last-child) {border-right: 1px solid #fff;}
          & > .auto-flex {
            border-top: 1px solid #fff;
            border-bottom: 1px solid #ccc;
            ul {
              li {
                padding: .5em 15px; cursor: pointer;
                overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
                &.on {background: rgba(128, 128, 128, .2);}
              }
            }
          }
          .glbs {
            .glb {display: block; cursor: pointer;}
          }
        }
      }
      .panel-nav-footer {

      }
    }
    .panel-main {
      overflow: hidden;
      & > .flex-v {
        & > .auto-flex {
          pre {
            display: inline-block; vertical-align: top;
            max-width: 100%; overflow: auto;
          }
        }
      }
    }
  }
}
</style>