<template>
	<view class="content">
		<ld-nav></ld-nav>
		<u-swiper :list="banners" keyName="image" height="210" radius="0" indicator indicatorMode="line" circular></u-swiper>
		<view class="title-container">
			<text class="btitle">我们的产品</text>
			<text class="stitle">凡是别人做不了的我们都擅长</text>
		</view>
		<view class="product">
			<view class="item" v-for="(item,index) in products" :key="index" @click="goproduct(item.id)">
				<u--image class="image" width="171" height="115" :showLoading="true" :src="item.image" ></u--image>
				<view class="title">
					<u--text color="#666" :lines="1" :text="item.title"></u--text>
				</view>
			</view>
			
		</view>
		
		<view class="about" :style="'background-image:url('+about_bg+');'">
			<view class="bg-container">
				<view class="title-container">
					<text class="btitle">关于我们</text>
					<text class="stitle">凡是别人做不了的我们都擅长</text>
				</view>
				<view class="content">
					 {{about}}
				</view>
			</view>
		</view>
		
		<view class="news">
			<view class="title-container">
				<text class="btitle">新闻资讯</text>
				<text class="stitle">凡是别人做不了的我们都擅长</text>
			</view>
			<view class="container items">
				<view class="item"  v-for="(item,index) in news" :key="index" @click="gonews(item.id)">
					<u--image :src="item.image" width="80px" height="80px"></u--image>
					<view class="content">
						<u--text class="title" color="#333" :lines="1" :text="item.title"></u--text>
						<u--text class="text" size='13' color="#666" :lines="2" :text="item.seo_description"></u--text>
					</view>
				</view>
			</view>
		</view>
		
		<ld-tabbar></ld-tabbar>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				banners: [],
				products:[],
				about_bg:'',
				about:'',
				news:[],
			}
		},
		onLoad() {
			uni.setNavigationBarTitle({
				title: this.$store.state.config.sitetitle
			});
			this.$api.getAd('banner').then(res=>{
				this.banners=res.data
			});
			this.$api.getLists({params:{cid:144,limit:4}}).then(res=>{
				this.products=res.data
			});
			this.$api.getAd('api_about').then(res=>{
				this.about_bg=res.data[0].image
			});
			this.$api.getContent(125).then(res=>{
				this.about=res.data.seo_description
			});
			this.$api.getLists({params:{cid:128,limit:6}}).then(res=>{
				this.news=res.data
			});
		},
		methods: {
			goproduct(id){
				uni.$u.route('/pages/chanpinzhongxin/detail', {
					id
				});
			},
			gonews(id){
				uni.$u.route('/pages/xinwenzhongxin/detail', {
					id
				});
			}
		}
	}
</script>

<style lang="scss">
	.title-container {
		display: flex;
		flex-direction: column;
		text-align: center;
		padding: 28rpx 0 20rpx;

		.btitle {
			font-size: 40rpx;
			color: #000;
		}

		.stitle {
			font-size: 24rpx;
			color: #666;
		}
	}
	.product{
		display: flex;
		align-items:center;
		padding:0 10rpx;
		
		flex-wrap: wrap;
		.item{
			display: flex;
			flex-direction: column;
			align-items: center;
			width: 50%;
		}
		.title{
			height: 70rpx;
			line-height: 70rpx;
			color: #888;
			font-size: 28rpx;
			padding:0 10rpx;
		}
	}
	.about{
		background: #666;
		
		margin-top: 30rpx;
		overflow: hidden;
		.bg-container{
			margin: 30rpx;
			padding:20rpx;
			height: 450rpx;
			background: #fff;
			opacity: .9;
		}
		.content{
			font-size: 28rpx;
			color: #333;
		}
	}
	.news{
		padding-bottom: 30rpx;
		.item{
			display: flex;
			margin-top: 30rpx;
			.content{
				margin-left:20rpx;
				.text{
					margin-top: 10rpx;
					font-size: 26rpx;
					color: #999;
				}
			}
		}
	}
</style>
