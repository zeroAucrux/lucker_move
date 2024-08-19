<template>
	<view>
		<ld-nav></ld-nav>
		<view class="container ">
			<u--form labelPosition="left" :model="form" :rules="rules" ref="uForm">
				<u-form-item label="姓名" prop="uname" borderBottom ref="uname">
					<u--input v-model="form.uname" border="none"></u--input>
				</u-form-item>
				<u-form-item label="电话" prop="mobile" borderBottom ref="mobile">
					<u--input v-model="form.mobile" border="none"></u--input>
				</u-form-item>
				<u-form-item label="内容" prop="remark" borderBottom ref="remark">
					<u--textarea v-model="form.remark" placeholder="请输入内容"></u--textarea>
				</u-form-item>
			</u--form>
			<u-button type="primary" text="提交" customStyle="margin-top: 50px" @click="submit"></u-button>
			<u-button type="error" text="重置" customStyle="margin-top: 10px" @click="reset"></u-button>
		</view>
		<ld-tabbar></ld-tabbar>
	</view>
</template>

<script>
	export default {
		data() {
			return {
				form: {
					uname: '',
					mobile: '',
					remark: '',

				},
				rules: {
					uname: {
						type: 'string',
						required: true,
						message: '请填写姓名',
						trigger: ['blur', 'change']
					},
					mobile: [{
							required: true,
							message: '请输入手机号',
							trigger: ['change', 'blur'],
						},
						{
							// 自定义验证函数，见上说明
							validator: (rule, value, callback) => {
								// 上面有说，返回true表示校验通过，返回false表示不通过
								// uni.$u.test.mobile()就是返回true或者false的
								return uni.$u.test.mobile(value);
							},
							message: '手机号码不正确',
							// 触发器可以同时用blur和change
							trigger: ['change', 'blur'],
						}
					],
					remark: {
						type: 'string',
						required: true,
						message: '请填写内容',
						trigger: ['blur', 'change']
					},
				},
			}
		},
		onReady() {
			// 如果需要兼容微信小程序，并且校验规则中含有方法等，只能通过setRules方法设置规则
			this.$refs.uForm.setRules(this.rules)
		},
		methods: {
			submit() {
				// 如果有错误，会在catch中返回报错信息数组，校验通过则在then中返回true
				this.$refs.uForm.validate().then(res => {
					this.$api.postMessage(this.form).then(res=>{
						if(res.code==1){
							uni.$u.route('/pages/index/index');
						}
						uni.$u.toast(res.msg);
					});
				}).catch(errors => {
					uni.$u.toast(errors[0].message);
				})
			},

			reset() {
				const validateList = ['uname', 'mobile', 'remark']
				this.$refs.uForm.resetFields()
				this.$refs.uForm.clearValidate()
				setTimeout(() => {
					this.$refs.uForm.clearValidate(validateList)
				}, 10)
			},
		}
	}
</script>

<style>

</style>
