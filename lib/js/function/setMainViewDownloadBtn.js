/**
 * 
 * setMainViewDownloadBtn
 * @Summary: メインビュー、チェックを入れた画像をzipダウンロードするボタン
 * 
 */

var setMainViewDownloadBtn = {
	init:function(){
		this.getElements();
		this.setEvent();
	},
	getElements:function(){
		this.el = {};

		// body
		this.el.$body = $('body');

		// 起動ボタン
		this.el.$downloadBtn = $('#js-download-checkbox');

		// ウィンドウ
		this.el.$modalWin = $('#js-modalwindow');
		this.el.$downloadWin = $('#js-download-win');

		// 終了ボタン
		this.el.$btnClose = $('#js-download__btn--close');

		this.el.$btnDownload = $('#js-download__btn--start');
		this.el.$ckbox = $('.imglist__ckbox');
		this.el.$checkbox = this.el.$ckbox.find('input');

	},
	setEvent:function(){
		var that = this;
		this.el.$downloadBtn.on('click',function(){
			that.el.$modalWin.removeClass('o-hide');
			that.el.$downloadWin.removeClass('o-hide');
		});

		this.el.$btnDownload.on('click',function(){

			if(!that.el.$body.hasClass('mode-imgview')){
				var check_length = that.el.$ckbox.find('input:checked').length;
				if(check_length > 0){
					if(check_length == 1){

						// シングルビューの場合
						var url = that.el.$ckbox.find('input:checked').closest('li').find('.imglist__filename').text();
						location.href = './api/download.php?img=' + url;

					}else{
						// マルチヴューモードの場合
						var srcArray = [];
						that.el.$checkbox.each(function (i) {
							if($(this).is(':checked')){
								var src = $(this).closest('li').find('.imglist__filename').text();
								srcArray.push('img[]='+src);
							}
						});
						// console.log(srcArray.join('&'));
						location.href = './api/zip.download.php?'+srcArray.join('&');
					}
				}
				that.el.$btnClose.trigger('click');
			}
		});

		this.el.$btnClose.on('click',function(){
			that.el.$modalWin.addClass('o-hide');
			that.el.$downloadWin.addClass('o-hide');
		});

	}
};
