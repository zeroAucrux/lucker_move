<template>
	<view class="container">
		<view class="h1">
			{{content.title}}
		</view>
		<view class="u-content">
			<u-parse :content="content.content" :domain="domain"></u-parse>
		</view>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				categoryInfo:{},
				content:"",
				domain:this.$setting.baseUrl,
				id:0
			};
		},
		onLoad(params) {
			this.id=params.id
			
			this.$api.getDetail(this.id).then(res=>{
				this.content=res.data;
				uni.setNavigationBarTitle({
					title: this.content.title
				});
			});
		}
	}
</script>

<style lang="scss">
	.h1{
		font-size: 38rpx;
		text-align: center;
		padding:20rpx 0;
	}
	.u-content{
		color: #555;
	}
</style>
