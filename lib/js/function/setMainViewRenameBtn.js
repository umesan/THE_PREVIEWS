/**
 * 
 * setMainViewRenameBtn
 * @Summary: メインビュー、チェックを入れた画像をリネームする
 * 
 */

var setMainViewRenameBtn = {
	init:function(){
		this.getElements();
		this.setEvent();
	},
	getElements:function(){
		this.el = {};

		// body
		this.el.$body = $('body');

		// 起動ボタン
		this.el.$renameBtn = $('#js-rename-checkbox');

		// ウィンドウ
		this.el.$modalWin = $('#js-modalwindow');
		this.el.$renameWin = $('#js-rename-win');

		// 終了ボタン
		this.el.$btnClose = $('#js-rename__btn--close');

		this.el.$renamelist = $('.js-renamelist');
		this.el.$btnRenameStart = $('#js-rename__btn--start');


		this.el.$ckbox = $('.imglist__ckbox');
		this.el.$checkbox = this.el.$ckbox.find('input');

	},
	setEvent:function(){
		var that = this;
		this.el.$renameBtn.on('click',function(){
			that.el.$modalWin.removeClass('o-hide');
			that.el.$renameWin.removeClass('o-hide');

			var srcArray = [];
			that.el.$checkbox.each(function (i) {
				if($(this).is(':checked')){
					var filename1 = $(this).closest('li').find('.imglist__filename').text();
					var filename_ex = filename1.split(".").pop();
					var filename2 = filename1.replace('.'+filename_ex,'');
					srcArray.push('<div class="m-rename__table"><div class="rename__table-l">'+filename1+'</div><div class="rename__table-c"><i class="fa fa-long-arrow-right"></i></div><div class="rename__table-r"><input type="text" class="file_rename" value="'+filename2+'"></div><div class="rename__table-ex">.'+filename_ex+'</div></div>');
				}
			});
			that.el.$renamelist.html(srcArray.join('\n'));
		});

		this.el.$btnRenameStart.on('click',function(){
			var srcArray = [];
			$('.m-rename__table').each(function (i) {
				var obj ={
					renameBefore: $(this).find('.rename__table-l').text(),
					renameAfter: $(this).find('.file_rename').val() + $(this).find('.rename__table-ex').text()
				};
				srcArray.push(obj);
			});
			// console.log(srcArray);

			// xml取得
			$.ajax({
				type: "post",
				url: './api/rename.php',
				data:{
					renamefiles:srcArray
				},
				success: function(xml){
					$(xml).find('rename').each(function (i) {
						console.log($(this).find('before').text());
						console.log($(this).find('after').text());
					});
					location.href = location.href;
				}
			});



		});

		this.el.$btnClose.on('click',function(){
			that.el.$modalWin.addClass('o-hide');
			that.el.$renameWin.addClass('o-hide');
		});

	}
};
