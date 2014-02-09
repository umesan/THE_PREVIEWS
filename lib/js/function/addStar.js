/**
 * addStar
 *  - 星をつける
 */
(function($){
	$(function(){

		// DB（CSV）への書き込み処理
		function addDB(img_id,flg,txt,date){
			$.ajax({
				type: "GET",
				url: './api/add.php?&id=' + img_id +'&star='+flg+'&txt1='+txt+'&date='+date,
				dataType: "xml",
				success: function(xml){
					activeflg = true;
					console.log('!');
					setTimeout(
						function() {
							$('.imglist__item').removeClass('js-refresh');
						},1000
					);
				}
			});
		}

		// AddStar
		var activeflg = true;
		$('.imglist__star').on('click',
			function(){
				if(activeflg){
					activeflg = false;
					var $li = $(this).closest('li');
					var img_id = $li.data('itemid');
					var rewrite_txt = $li.find('textarea').val();
					if($(this).hasClass('fix')){
						addDB(img_id,0,rewrite_txt,statusDB[img_id].date);
						$(this).removeClass('fix').text('☆');
					}else{
						addDB(img_id,1,rewrite_txt,statusDB[img_id].date);
						$(this).addClass('fix').text('★');
					}
				}
				return false;
			}
		);


		// Add Comment
		$('.js-btn_update').on('click',
			function(e){
				e.preventDefault();
				var $li = $(this).closest('li');
				$li.addClass('js-refresh');

				var rewrite_txt = $li.find('textarea').val();
				var img_id = $li.data('itemid');

				$li.find('.status__short-txt').text(rewrite_txt);

				var $star = $li.find('.imglist__star');
				if($star.hasClass('fix')){
					addDB(img_id,1,rewrite_txt,statusDB[img_id].date);
				}else{
					addDB(img_id,0,rewrite_txt,statusDB[img_id].date);
				}

			}
		);

	});
})(jQuery);