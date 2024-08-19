<script>
	export default {
		onLaunch:async function() {
			console.log('App Launch')
			let res = await this.$api.getConfig();
			if (!res.code) {
				return;
			}
			this.$store.commit('setConfig',res.data);
			uni.setNavigationBarTitle({
				title: res.data.sitetitle
			});
		},
		onShow: function() {
			
		},
		onHide: function() {
			console.log('App Hide')
		}
	}
</script>

<style lang="scss">
	/*每个页面公共css */
	@import "@/uni_modules/uview-ui/index.scss";
	.container{
		width: 690rpx;
		margin: 0 auto;
	}
</style>
