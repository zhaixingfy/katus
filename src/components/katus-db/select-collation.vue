<template>
  <div>
    <input type="text" class="form-control" readonly required
      style="min-width: 120px;" 
      @click="isShow = true; $event.target.blur()"
      @keydown.prevent.enter="isShow = true; $event.target.blur()"
      :value="collation"
      :title="collation"
    >
    <katus-dialog class="dialog-select-collation"
      :is-show="isShow"
    >
      <div style="white-space: normal;">
        <div class="glass-bar glass-flex">
          <div class="auto-flex bold">选择字符集</div>
          <div>
            <i class="glyphicon glyphicon-remove"
              @click="isShow = false"
            ></i>
          </div>
        </div>
        <div class="dialog-body" style="max-width: 1000px;">
          <div class="space" style="padding-top: 0">
            <div class="list-sec">
              <section>
                <div class="main-bar">
                  <div class="auto-flex">Charset</div>
                  <div class="form-search">
                    <input type="search" class="form-control" placeholder="极速检索" 
                      v-model="searchText.charset"
                    >
                    <i class="glyphicon glyphicon-search"></i>
                  </div>
                </div>
                <div class="space-items-r">
                  <button type="button" 
                    v-for="(item, idx) in listCharset"
                    :key="idx"
                    :class="['btn btn-' + (item.Charset === charset ? 'primary' : 'default')]"
                    @click="$emit('change', $root.mysql.env.charset.map[item.Charset]['Default collation'])"
                  >{{item.Charset}}</button>
                </div>
              </section>
              <section>
                <div class="main-bar">
                  <div class="auto-flex">Collation</div>
                  <div class="form-search">
                    <input type="search" class="form-control" placeholder="极速检索" 
                      v-model="searchText.collation"
                    >
                    <i class="glyphicon glyphicon-search"></i>
                  </div>
                </div>
                <div class="space-items-r">
                  <button type="button"
                    v-for="(item, idx) in listCollation"
                    :key="idx"
                    :class="['btn btn-' + (item.Collation === collation ? 'success' : 'default')]"
                    @click="$emit('change', item.Collation)"
                  >{{item.Collation}}</button>
                </div>
              </section>
            </div>
          </div>
        </div>
        <div class="dialog-footer">
          <button type="button" class="btn btn-success"
            @click="isShow = false"
          >确定</button>
        </div>
      </div>
    </katus-dialog>
  </div>
</template>

<script>
export default {
  props: ['collation'],
  model: {
    prop: 'collation',
    event: 'change'
  },
  data() {
    return {
      isShow: false,
      searchText: {
        charset: '',
        collation: '',
      }
    }
  },
  computed: {
    pp() {
      return this.$root.katusDB
    },
    charset() {
      return this.pp.env.collation.map[this.collation]?.Charset || ''
    },
    listCharset() {
      const searchText = this.searchText.charset?.trim()
      return searchText ? this.pp.env.charset.list.filter((v) => {
        return v.Charset.indexOf(searchText) > -1
      }) : this.pp.env.charset.list
    },
    listCollation() {
      const searchText = this.searchText.collation?.trim()
      const charset = this.pp.env.collation.map[this.collation]?.Charset
      const list = this.pp.env.charset.mapGroup[charset] || []

      return searchText ? list.filter((v) => {
        return v.Collation.indexOf(searchText) > -1
      }) : list
    },
  },
  watch: {
    isShow: {
      immediate: true,
      handler(newVal) {
        const vm = this.$root
        if (newVal) {
          vm.mysql.curDialogSelectCollation = this
        } else {
          delete vm.mysql.curDialogSelectCollation
        }
      },
    },
  }
}
</script>

<style lang="less" scoped>

</style>