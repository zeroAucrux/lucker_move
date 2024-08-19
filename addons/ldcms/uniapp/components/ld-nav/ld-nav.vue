<template>
	<u-sticky bgColor="#fff">
		<u-tabs :list="list" :duration="0" @click="ontab" :current='current'></u-tabs>
	</u-sticky>
</template>

<script>
import util from '../../common/utils';
	export default {
		name:"ld-nav",
		data() {
			return {
				list:[],
				page:''
			};
		},
		async created(){
			let nav=this.$store.state.nav;
			if(nav.length==0){
				let res=await this.$api.getNav()
				if (res.code) {
					res.data.unshift({'id':0,'name':'首页',urlname:'index'})
					this.list=res.data
					this.$store.commit('setNav',res.data)
				}
			}else{
				this.list=nav
			}
			this.page=uni.$u.page()
			this.list.forEach((item,index)=>{
				if('/pages/'+item.urlname+'/index'==this.page){
					this.$store.commit('setNavOn',index); //设置导航锁定
					this.$store.commit('setNavId',item.id); 
				}
			})
		},
		computed:{
			current(){
				return this.$store.state.navOn;
			}
		},
		methods:{
			ontab(item){
				/* 跳过当前页面 */
				if('/pages/'+item.urlname+'/index'==this.page){
					return ;
				}
				this.$store.commit('setNavOn',item.index); //设置导航锁定
				this.$store.commit('setNavId',item.id); 
				if(item.id==0){ 	/* 首页*/
					uni.redirectTo({
						url:'/pages/index/index'
					})
				}
				uni.redirectTo({
					url:'/pages/'+item.urlname+'/index'
				});
				
			}
		}
	}
</script>

<style lang="scss">

</style>