import KatusDialog from './katus-dialog.vue'
import KatusLoading from './katus-loading.vue'
import KatusLoadingCxy from './katus-loading-cxy.vue'
import KatusResizer from './katus-resizer.vue'
import KatusSwitch from './katus-switch.vue'
import KatusTooltip from './katus-tooltip.vue'

export default (app) => {
  app.component('KatusDialog', KatusDialog)
  app.component('KatusLoading', KatusLoading)
  app.component('KatusLoadingCxy', KatusLoadingCxy)
  app.component('KatusResizer', KatusResizer)
  app.component('KatusSwitch', KatusSwitch)
  app.component('KatusTooltip', KatusTooltip)
}