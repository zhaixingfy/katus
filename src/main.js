import 'element-plus/dist/index.css'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap/dist/css/bootstrap-theme.css'
import 'bootstrap-icons/font/bootstrap-icons.css'
import 'animate.css'
import './assets/css/all.less'

import './assets/js/app-aux.js'

import { createApp } from 'vue'
import Katus from './assets/katus/index.js'
import ElementPlus from 'element-plus'
import zhCn from 'element-plus/dist/locale/zh-cn.mjs'
import App from './App.vue'

const app = window.app = createApp(App)
app.use(Katus)
app.use(ElementPlus, {locale: zhCn})
window.vm = app.mount('#app')

