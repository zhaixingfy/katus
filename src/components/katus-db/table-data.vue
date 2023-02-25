<template>
  <div class="flex-v table-data">
    <div style="padding: 0 15px;">
      <div class="main-bar">
        <div class="auto-flex">数据</div>
        <div class="btns-ctrl">
          <button
            :class="['btn btn-' + (r.isShowTableStructure ? 'primary' : 'default')]"
            @click="$root.updateRouter({isShowTableStructure: !r.isShowTableStructure || undefined}, false)"
          >
            <i class="glyphicon glyphicon-eye-open"></i>
            <span>库表结构</span>
          </button>
          <katus-tooltip msg="复制成功" direction="bottom">
            <button type="button" class="btn btn-info"
              @click="$root.copy('[\n  ' + $parent.tableData.list.map(row => JSON.stringify(row)).join(',\n  ') + '\n]');"
            >
              <i class="glyphicon glyphicon-copy"></i>
              <span>复制</span>
            </button>
          </katus-tooltip>
          <button type="button" title="添加一行" class="btn btn-success">
            <i class="glyphicon glyphicon-plus"></i>
            <span>添加一行</span>
          </button>
          <button type="button" title="清空" class="btn btn-danger">
            <i class="glyphicon glyphicon-trash"></i>
            <span>清空</span>
          </button>
        </div>
      </div>
    </div>
    <div class="auto-flex bb">
      <div class="space" style="padding-top: 0;"
        v-if="table.list.length > 0"
      >
        <template v-if="r.isShowTableStructure">
          <div>
            <pre v-if="db.sc">{{db.sc}}</pre>
          </div>
          <div>
            <pre v-if="scTable">{{scTable}}</pre>
          </div>

          <div>
            <table class="table table-bordered table-striped" style="width: auto; margin-bottom: 15px;">
              <thead>
                <tr>
                  <th
                    v-for="(fieldKey, idx) in fieldKeys"
                    :key="idx"
                  >{{fieldKey}}</th>
                  <th>操作</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(row, idx) in field.list"
                  :key="idx"
                >
                  <td
                    v-for="(fieldKey, idx) in fieldKeys"
                    :key="idx"
                  >{{row[fieldKey]}}</td>
                  <td>
                    <button class="btn btn-xs btn-warning">
                      <i class="glyphicon glyphicon-pencil"></i>
                    </button>
                    <button class="btn btn-xs btn-danger">
                      <i class="glyphicon glyphicon-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>

        <div>
          <div class="ib">
            <table class="table table-bordered table-striped table-fix-header"
              style="margin-bottom: 0; white-space: nowrap;"
              @dblclick="handleDblclick"
            >
              <thead>
                <tr>
                  <th
                    v-for="(item, idx) in field.list"
                    :key="idx"
                  >{{item.Field}}</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(row, idx) in tableData.list"
                  :key="idx"
                >
                  <td
                    v-for="(field, idx) in fields"
                    :key="idx"
                  >{{row[field]}}</td>
                </tr>
                <tr v-if="tableData.list.length === 0 && !tableData.isLoading">
                  <td :colspan="fields.length">
                    <div class="alert alert-warning" style="margin-bottom: 0">暂无数据</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="glb c" style="display: block; overflow: auto;">
      <div class="ib" style="vertical-align: top; height: 22px;">
        <el-pagination
          v-model:current-page="r.pageCur"
          v-model:page-size="r.pageSize"
          :page-sizes="page.sizes"
          :small="true"
          :disabled="false"
          :background="true"
          layout="total, sizes, prev, pager, next, jumper"
          :total="page.total"
          @size-change="handleSizeChange"
          @current-change="handleCurrentChange"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
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
    db() {
      return this.$parent.db
    },
    table() {
      return this.$parent.table
    },
    scTable() {
      return (
        this.table.sc['Create View'] ?
          this.$root.formatCreateView(this.table.sc['Create View']) :
          this.table.sc['Create Table']
      )
    },
    field() {
      return this.$parent.field
    },
    fields() {
      return this.$parent.fields
    },
    fieldKeys() {
      return this.$parent.fieldKeys
    },
    tableData() {
      return this.$parent.tableData
    },
    page() {
      return this.$parent.page
    },
  },
  watch: {
    'r.pageCur'() {
      this.$parent.lsTableData()
    },
    'r.pageSize'() {
      this.$parent.lsTableData()
    },
  },
  methods: {
    handleDblclick(e) {
      if (e.target.nodeName.toLowerCase() !== 'td') return
      this.$root.copy(e.target.innerText)
    },
    handleSizeChange() {
      this.$root.isRouterPush = true
    },
    handleCurrentChange() {
      this.$root.isRouterPush = true
    },
  },
  async mounted() {
    
  },
}
</script>

<style lang="less" scoped>
.table-data {
  .btns-ctrl > *:not(:first-child) {
    margin-left: 8px;
  }
  pre {
    display: inline-block; max-width: 100%; vertical-align: top;
  }
}
</style>