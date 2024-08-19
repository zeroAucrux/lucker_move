const http = uni.$u.http
const addons= '/addons/ldcms/';
/* custom:{auth:false} 表示该接口 不需要验证token */
const install = (Vue, vm) => {
	vm.$api.getConfig = async (params={}) =>{return http.get(addons+'api.v1/config', {...params,...{custom:{auth:false}}})} ;
	vm.$api.getNav = async (params={}) =>{return http.get(addons+'api.v1/nav', {...{custom:{auth:false}}})} ;
	vm.$api.getAd = async (name) =>{
		return http.get(addons+'api.v1/ad', {params:{name},...{custom:{auth:false}}})
	};
	vm.$api.getLists= async (params={})=>{
		return http.get(addons+'api.v1/lists', {...params,...{custom:{auth:false}}})
	};
	vm.$api.getContent= async (cid)=>{
		return http.get(addons+'api.v1/content', {params:{cid},...{custom:{auth:false}}})
	};
	vm.$api.getDetail= async (id)=>{
		return http.get(addons+'api.v1/detail', {params:{id},...{custom:{auth:false}}})
	};
	vm.$api.postMessage= async (data)=>{
		return http.post(addons+'api.v1/diyform/type/message', data)
	}
}

export default {
	install
}
