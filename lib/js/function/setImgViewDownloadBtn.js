/**
 * 
 * setImgViewDownloadBtn
 * @Summary: イメージビュー、チェックを入れた画像をzipダウンロードするボタン
 * 
 */

var setImgViewDownloadBtn = {
	init:function(){
		this.getElements();
		this.setEvent();
	},
	getElements:function(){
		this.el = {};

		// body
		this.el.$body = $('body');

		// 起動ボタン
		this.el.$downloadBtn = $('#js-download');

		// ウィンドウ
		this.el.$modalWin = $('#js-modalwindow');
		this.el.$downloadWin = $('#js-download-win');

		// 終了ボタン
		this.el.$btnClose = $('#js-download__btn--close');

		// ダウンロードボタン
		this.el.$btnDownload = $('#js-download__btn--start');

		// 画像タイトル
		this.el.$imgViewImgtitle = $('#js-imgview-imgtitle');

		// 画像名
		this.el.$imgViewList = $('#imgviewlist');

	},
	setEvent:function(){
		var that = this;

		this.el.$downloadBtn.on('click',function(){
			that.el.$modalWin.removeClass('o-hide');
			that.el.$downloadWin.removeClass('o-hide');
		});

		this.el.$btnDownload.on('click',function(){

			if(that.el.$body.hasClass('mode-imgview')){
				if(that.el.$body.hasClass('view-single')){

					// シングルビューの場合
					var url = that.el.$imgViewImgtitle.text();
					location.href = './api/download.php?img=' + url;

				}else{

					// マルチヴューモードの場合
					var srcArray = [];
					that.el.$imgViewList.find('.views').each(function (i) {
						var src = $(this).data('src');
						srcArray.push('img[]='+src);
					});

					console.log(srcArray.join('&'));
					//alert(srcArray.join('&'));
					location.href = './api/zip.download.php?'+srcArray.join('&');

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