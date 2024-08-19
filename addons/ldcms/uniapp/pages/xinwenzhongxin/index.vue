<template>
	<view>
		<ld-nav></ld-nav>
		<view class="container ">
			<u-list @scrolltolower="scrolltolower">
				<view class="news">
					<u-list-item v-for="(item, index) in news" :key="index">
						<view class="item"  @click="gonews(item.id)">
							<view class="image">
								<u--image class="image" width="100" height="100" :showLoading="true" :src="item.image"></u--image>
							</view>
							<view class="content">
								<u--text class="title" color="#333" :lines="1" :text="item.title"></u--text>
								<u--text class="text" size='13' color="#666" :lines="2" :text="item.seo_description"></u--text>
							</view>
							
						</view>
					</u-list-item>
				</view>
				<u-loadmore loadmore_show="loadmore_show" :status="status" />
			</u-list>
			
		</view>
		<ld-tabbar></ld-tabbar>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				status: 'loadmore',
				loadmore_show:false,
				categoryInfo: {},
				news: [],
				query: {
					cid:0,
					page: 1,
					limit: 20,
					last_page: 1
				},
			}
		},
		onLoad() {
			this.categoryInfo = this.getNavInfo(this.$store.state.navId);
			uni.setNavigationBarTitle({
				title: this.categoryInfo.name
			});
			this.query.cid=this.categoryInfo.id;
			this.loadmore()
		},
		methods: {
			scrolltolower() {
				if (this.query.last_page > this.query.page) {
					this.query.page++
					this.loadmore()
				}
			},
			async loadmore() {
				this.loadmore_show=true;
				let res = await this.$api.getLists({
					params: this.query
				})
				if (res.code == 1) {
					const {
						data,
						current_page,
						last_page
					} = res.data
					this.query['page'] = current_page
					this.query['last_page'] = last_page
					this.news = this.news.concat(data)
				}
				if(this.query.last_page==this.query.page){
					this.status='nomore';
				}else{
					this.loadmore_show=false;
				}
				
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
