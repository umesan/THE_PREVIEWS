/**
 * document.radery の状態で実行
 */
$(function(){

	setSortForm.init();
	setThumbBtn.init();
	setToggleBtn.init();

	setCtsToggleEvent();
	setTtlLinkBtn();

	singleView.init();

	setViewChangeBtn.init();

	setInitPage();

	setAllCheckBtn.init();
	setCheckBox.init();
	setDeleteBtn.init();
	setMultiViewBtn.init();
	setSelectSingleView.init();

	setSelectSingleView.groupCheck();

	getScrollPosition.init();

	setAreaCheckBtn.init();

	setLinkBtnCheck.init();
	setLinkBtn.init();

	setMainViewDownloadBtn.init();
	setImgViewDownloadBtn.init();

	setMainViewRenameBtn.init();

	setUrlBtn.init();

	setMainViewImgListItem.init();

	//$('.m-imglist').rjs_hlg();
});
