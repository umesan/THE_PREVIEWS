/**
 * 
 * setMainViewImgListItem
 * @Summary: リストにホバーした際の処理
 * 
 */

var setMainViewImgListItem = {
	init:function(){
		this.getElements();
		this.setEvent();
	},
	getElements:function(){
		this.el = {};

		this.el.$body = $('body');

		// 終了ボタン
		this.el.$imglistitem = $('.imglist__item');

	},
	setEvent:function(){
		var that = this;
		this.checkLogStatus();

		if(this.logStatus === 'out'){
			$('.status__short-txt').each(function (i) {
				if($(this).text() == ''){
					$(this).next('.status__frm').remove();
				}
			});
		}

		this.el.$imglistitem.hover(
			function(){
				$(this).addClass('imglist__item--hover');
			},function(){
				$(this).removeClass('imglist__item--hover');
			}
		);


	},
	checkLogStatus:function(){
		this.logStatus = this.el.$body.data('log');
	}
};
