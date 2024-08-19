<template>
	<view>
		<ld-nav></ld-nav>
		<view class="container ">
			<u-list @scrolltolower="scrolltolower">
				<view class="product">
					<u-list-item v-for="(item, index) in products" :key="index">
						<view  @click="goproduct(item.id)">
							<u--image class="image" width="171" height="115" :showLoading="true" :src="item.image"></u--image>
							<view class="title">
								<u--text color="#666" :lines="1" :text="item.title"></u--text>
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
				products: [],
				query: {
					cid:0,
					page: 1,
					limit: 20,
					last_page: 1
				},
			};
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
					this.products = this.products.concat(data)
				}
				if(this.query.last_page==this.query.page){
					this.status='nomore';
				}else{
					this.loadmore_show=false;
				}
				
			},
			goproduct(id){
				uni.$u.route('/pages/chanpinzhongxin/detail', {
					id
				});
			}
		},
	}
</script>

<style lang="scss">
	.u-list {
		.product{
			display: flex;
			align-items: center;
			flex-wrap: wrap;
			justify-content:space-between;
		}
		.u-list-item {
			display: flex;
			flex-direction: column;
			align-items: center;
			width: 48%;
		}

		.title {
			height: 70rpx;
			line-height: 70rpx;
			color: #888;
			font-size: 28rpx;
			padding: 0 10rpx;
		}
	}
</style>
