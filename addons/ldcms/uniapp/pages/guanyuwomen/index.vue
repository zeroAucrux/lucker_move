<template>
	<view>
		<ld-nav></ld-nav>
		<view class="container u-content">
			<u-parse :content="content" :domain="domain"></u-parse>
		</view>
		<ld-tabbar ></ld-tabbar>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				categoryInfo:{},
				content:"",
				domain:this.$setting.baseUrl,
			};
		},
		onLoad() {
			this.categoryInfo=this.getNavInfo(this.$store.state.navId);
			uni.setNavigationBarTitle({
				title: this.categoryInfo.name
			});
			this.$api.getContent(this.categoryInfo.id).then(res=>{
				this.content=res.data.content;
			});
		},
		methods:{
			
		}
		
	}
</script>

<style lang="scss">
 .u-content {
        padding: 24rpx;
        font-size: 32rpx;
        color: $u-content-color;
        line-height: 1.6;
    }
</style>
