(function($){
	$(function(){

		// ソート
		var $sorttable = $('#sortable');
		$sorttable.sortable({
			update: function(event, ui) {
				$sorttable.find('li').each(function(i) {
					$(this).find('input').each(function(k) {
						var before_name = $(this).attr('name');
						var after_name = before_name.replace(/\d+/,i);
						$(this).attr('name',after_name);
					});

				});
			}
		}).disableSelection();

		// 追加
		var $addbtn = $('#addbtn');
		$addbtn.on('click',function(){
			var li_length = $sorttable.find('li').length;

			var addlist = '';
			addlist += ''
			+ '<li class="o-cfx add">'
			+ 	'<div class="table-filelist__grab"><i class="fa fa-bars"></i></div>'
			+ 	'<div class="table-filelist__prefix">'
			+ 		'<input type="text" name="category['+li_length+'][prefix]" value="">'
			+ 	'</div>'
			+ 	'<div class="table-filelist__name">'
			+ 		'<input type="text" name="category['+li_length+'][name]" value="">'
			+ 	'</div>'
			+ 	'<div class="table-filelist__url">'
			+ 		'<input type="text" name="category['+li_length+'][url]" value="">'
			+ 	'</div>'
			+ 	'<div class="table-filelist__ckbox"><a href="#"><i class="fa fa-lg fa-trash-o"></i></a></div>'
			+ '</li>';

			$sorttable.append(addlist);
		});


		// 削除
		$(document).on('click','.table-filelist__ckbox a',function(){
			$(this).closest('li').addClass('js-delete-list');
			$('body').append('<div class="modal01__bg"></div><div class="modal01__box modal01__box--fix"><p class="modal01__txt">選択したカテゴリを本当に削除しますか？</p><p class="m-btn03 js-btn-cancel">キャンセル</p> <p class="m-btn03 btn03--delete js-btn-del">削除</p></div></div>');
			return false;
		});

		$(document).on('click','.js-btn-cancel',function(){
			$('.modal01__bg').remove();
			$('.modal01__box').remove();
			$('#sortable').find('.js-delete-list').removeClass('.js-delete-list');
			return false;
		});

		$(document).on('click','.js-btn-del',function(){
			var $dellist = $('#sortable').find('.js-delete-list');

			if($dellist.hasClass('add')){
				// 対象のリストが .add を持っている場合は、まだ登録をしていないので、表示を消すだけでOK
				$dellist.remove();
			}else{
				// 対象のリストが .add を持っていない場合は、CSVからもカテゴリを消す
				// var delete_id = $dellist.find('.category_prefix').find('input').val();
				var delete_id = $dellist.data('catid');
				$dellist.remove();
				if(delete_id != null){
					$.ajax({
						type: "GET",
						url: '../../api/delete_category.php?id='+delete_id,
						dataType: "xml",
						cache : false,
						success: function(xml){
						}
					});
				}
			}
			$('.modal01__bg').remove();
			$('.modal01__box').remove();
			$dellist.removeClass('.js-delete-list');

			return false;
		});




		// 配列内に重複する値があるかをチェックする関数
		function checkOverlap(array) {
			var storage = {};
			var i,value;
			var result = 0;
			var overlaps = [];
			var array_length = array.length;
			for ( i=0; i < array_length; i++) {
				value = array[i];
				if (!(value in storage)) {
					storage[value] = true;
				}else{
					overlaps.push(array[i]);
					result = 1;
				}
			}
			var check = {
				result:result,
				overlapWord: overlaps
			}
			return check;
		}


		// 重複チェック
		$('.btnarea').find('input').on('click',function(){
			var catIdCheck = [];
			$('.category_prefix').each(function (i) {
				var catid = $(this).find('input').val();
				if(catid != null){
					catIdCheck.push(catid);
				}
			});

			console.log(catIdCheck);
			var check = checkOverlap(catIdCheck);
			if(check.result){
				event.preventDefault();
				$('#js-alert').remove();
				$('header').before('<div id="js-alert" class="alert alert__error">カテゴリIDが重複しています。</div>');
				setAlert();

				$('.error').removeClass('error');

				var length = check.overlapWord.length;
				for (var i = 0; i < length; i++) {

					$('.category_prefix').each(function (index) {
						var catid = $(this).find('input').val();
						if(catid == check.overlapWord[i]){
							$(this).closest('li').addClass('error');
						}
					});

				};

			}
			console.log('check = ' + check.result);

		});

		function setAlert(){
			// 完了 アラート
			$('#js-alert').fadeIn(1000,function(){
				$(this).delay(3000).fadeOut(1000,function(){
					$(this).remove();
				});
			});
		}
		setAlert();

	});


})(jQuery);