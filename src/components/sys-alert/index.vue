<template>
  <katus-dialog class="sys-dialog"
    :is-show="$root.dataAlert.isShow"
  >
    <div class="inner">
      <div class="glb">
        <div class="auto-flex">
          <strong>提示</strong>
        </div>
      </div>
      <div class="dialog-body">
        <div class="space">
          <!-- <div>确定要执行下面的sql语句吗？</div>
          <pre class="text-danger" style="margin: 10px 0 0 0;">DROP DATABASE `information_schema`</pre> -->
          <div v-html="$root.dataAlert.msg"></div>
        </div>
      </div>
      <div class="dialog-footer">
        <button class="btn btn-success"
          @click="$root.dataAlert.isShow = false;"
        >确定</button>
      </div>
    </div>
  </katus-dialog>
</template>

<script>
export default {
  name: 'sys-alert',
  props: ['isShow'],
}
</script>