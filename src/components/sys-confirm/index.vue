<template>
  <katus-dialog class="sys-dialog"
    :is-show="$root.dataConfirm.isShow"
  >
    <div class="inner">
      <div class="glb">
        <div class="auto-flex">
          <strong>提示</strong>
        </div>
      </div>
      <div class="dialog-body">
        <div class="space">
          <div v-html="$root.dataConfirm.msg"></div>
        </div>
      </div>
      <div class="dialog-footer">
        <button class="btn btn-default"
          @click="handleCancel"
        >取消</button>
        <button class="btn btn-success"
          @click="handleConfirm"
        >确定</button>
      </div>
    </div>
  </katus-dialog>
</template>

<script>
export default {
  name: 'sys-confirm',
  props: ['isShow'],
  methods: {
    handleCancel() {
      const vm = this.$root

      vm.dataConfirm.isShow = false
      delete vm.cbConfirm
    },
    handleConfirm() {
      const vm = this.$root

      vm.cbConfirm && vm.cbConfirm()

      vm.dataConfirm.isShow = false
      delete vm.cbConfirm
    },
  }
}
</script>