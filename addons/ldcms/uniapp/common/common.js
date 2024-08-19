module.exports ={
	data(){
		return {
			
		}
	},
	methods:{
		getNavInfo(id){
			let nav=this.$store.state.nav;
			for(let index=0;index<nav.length;index++){
				let item=nav[index];
				if(item.id==id){
					return item;
				}
			}
			return {}
		}
	}
}