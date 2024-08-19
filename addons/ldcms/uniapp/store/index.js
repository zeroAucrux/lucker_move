import Vue from 'vue'
import Vuex from 'vuex'
Vue.use(Vuex)

const store = new Vuex.Store({
	state: {
		tabbar:[
			{
				text: '首页',
				icon:"home",
				pagePath: "/pages/index/index"
			},
			{
				icon: "integral",
				text: '关于',
				pagePath: "/pages/guanyuwomen/index"
			},
			{
				icon: "grid",
				text: '产品',
				pagePath: "/pages/chanpinzhongxin/index"
			},
			{
				icon: "chat",
				text: '留言',
				pagePath: "/pages/zaixianliuyan/index"
			},
		],
		config:{},
		nav:[],
		navOn:0,
		navId:0,
		userInfo:{
			token:''
		}
	},
	mutations: {
		setTabbar(state,data){
			state.tabbar=data
		},
		setConfig(state,data){
			state.config=data
		},
		setNav(state,data){
			state.nav=data
		},
		setNavOn(state,data){
			state.navOn=data
		},
		setNavId(state,data){
			state.navId=data
		}
	}
})

export default store