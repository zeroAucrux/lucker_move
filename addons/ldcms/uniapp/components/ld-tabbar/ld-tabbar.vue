<template>
	<view>
		<u-tabbar
			:value="value"
		>
			<template  v-for="(item,index) in list" >
				<u-tabbar-item :key="index" :text="item.text" :icon="item.icon" @click="click(index,item.pagePath)" ></u-tabbar-item>
			</template>
			
		</u-tabbar>
	</view>
</template>

<script>
	export default {
		name:"ld-tabbar",
		data() {
			return {
				value:0,
				list:[],
			};
		},
		created() {
			// /* 当前页面 */
			let page= uni.$u.page()
			this.list=this.$store.state.tabbar

			/* tabbar页面 */
			this.list.forEach((item,index)=>{
				if(item.pagePath==page){
					this.value=index
				}
			})
			
		},
		methods:{
			click(index,path){
				let nav=this.$store.state.nav
				let page=uni.$u.page()
				if(path==page){  //跳过当前页面
					return ;
				}
				
				if(nav.length>0){
					nav.forEach((item,index)=>{
						if('/pages/'+item.urlname+'/index'==path){
							this.$store.commit('setNavId',item.id); 
						}
					})
				}
				uni.reLaunch({
					url:path
				})
			},
		}
	}
</script>

<style lang="scss">

</style>