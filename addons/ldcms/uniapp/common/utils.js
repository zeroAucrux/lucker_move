// /* 当前页面*/
const util={
	getpage(){
		var pages = getCurrentPages();
		var page = pages[pages.length - 1];
		return page;
	}

}

export default util;