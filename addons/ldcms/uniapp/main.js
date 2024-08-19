import App from './App'

// #ifndef VUE3
import Vue from 'vue'
Vue.config.productionTip = false
App.mpType = 'app'
Vue.prototype.$api = {};//定义api对象

import uView from '@/uni_modules/uview-ui'
Vue.use(uView)

import md5 from '@/js_sdk/js-md5/src/md5.js'
import config from '@/common/config.js'

//原型追加工具函数
Vue.prototype.$setting = config;
Vue.prototype.$md5 = md5;

let mpShare = require('@/uni_modules/uview-ui/libs/mixin/mpShare.js');
let common = require('@/common/common.js');
Vue.mixin(mpShare);
Vue.mixin(common);

import store from '@/store'
const app = new Vue({
	store,
    ...App
})

require('@/common/http.request.js')(app)

// http接口API抽离，免于写url或者一些固定的参数
import httpApi from '@/common/http.api.js'
Vue.use(httpApi, app)

app.$mount()
// #endif

// #ifdef VUE3
import { createSSRApp } from 'vue'
export function createApp() {
  const app = createSSRApp(App)
  return {
    app
  }
}
// #endif