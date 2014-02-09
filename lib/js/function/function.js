
	// ------------------------------------------------------------
	// URLを生成
	// ------------------------------------------------------------
		var setURL = function(opt){

			var allUrlParm = getAllUrlParm();

			if(allUrlParm.sort != 'cat'){ allUrlParm.sort = 'date'; }
			if(allUrlParm.c == null){ allUrlParm.c = ''; }
			if(allUrlParm.m == null){ allUrlParm.m = 'thumb'; }
			if(allUrlParm.o != '1'){ allUrlParm.o = '0'; }

			var obj = {
				sort:allUrlParm.sort,
				category:allUrlParm.c,
				mode:allUrlParm.m,
				open:allUrlParm.o
			}

			var opts = $.extend({}, obj, opt);
			var optslink = '&c='+opts.category+'&m='+opts.mode+'&o='+opts.open;

			// 左メニューリンクを再設定
			$('#datelink').attr('href','./?sort=date&c=&m='+opts.mode+'&o='+opts.open);
			$('#catlink').attr('href','./?sort=cat&c=&m='+opts.mode+'&o='+opts.open);

			if(allUrlParm.row == null || allUrlParm.p == null){
				if(allUrlParm.group == null){
					//URLを切替再設定
					var url = './?sort='+opts.sort+optslink;
					window.history.pushState('top','',url);
				}else{
					//console.log('parms_group='+allUrlParm.group);
					//URLを切替再設定
					var url = './?sort='+opts.sort+optslink+'&group='+allUrlParm.group;
					window.history.pushState('top','',url);
				}
			}
		}

		/**
		 * シングル・マルチ カラム切替
		 */
		var setUrlColumnMode = function(col){
			var allUrlParm = getAllUrlParm();

			// console.log(allUrlParm);
			// console.log('allUrlParm.group = ' + allUrlParm.group);

			if(allUrlParm.group != null){
				window.history.pushState('single','','./?sort='+allUrlParm.sort+'&c='+allUrlParm.c+'&m='+allUrlParm.m+'&o='+allUrlParm.o + '&col=' + THE_PREVIEW.DATABASE.url.col + '&group=' + allUrlParm.group);
			}else{
				window.history.pushState('single','','./?sort='+allUrlParm.sort+'&c='+allUrlParm.c+'&m='+allUrlParm.m+'&o='+allUrlParm.o + '&row=' + allUrlParm.row + '&col=' + THE_PREVIEW.DATABASE.url.col + '&p=' + allUrlParm.p);
			}

		};


	// ------------------------------------------------------------
	// パラメータから初期状態を復元
	// ------------------------------------------------------------
		var setInitPage = function(){

			var allUrlParm = getAllUrlParm();

			// DBに状態を保存
			THE_PREVIEW.DATABASE.url.sort = allUrlParm.sort;
			THE_PREVIEW.DATABASE.url.c = allUrlParm.c;
			THE_PREVIEW.DATABASE.url.m = allUrlParm.m;
			THE_PREVIEW.DATABASE.url.o = allUrlParm.o;
			THE_PREVIEW.DATABASE.url.row = allUrlParm.row;
			THE_PREVIEW.DATABASE.url.col = allUrlParm.col;
			THE_PREVIEW.DATABASE.url.p = allUrlParm.p;
			THE_PREVIEW.DATABASE.url.group = allUrlParm.group;

			//console.log(THE_PREVIEW.DATABASE);


			switch (allUrlParm.m){
				case "thumb":
					setThumbBtn.setThumb('thumb');
					break;
				case "thumb_l":
					setThumbBtn.setThumb('thumb_l');
					break;
				case "list":
					setThumbBtn.setThumb('list');
					break;
				case "list_l":
					setThumbBtn.setThumb('list_l');
					break;
				default:
					setThumbBtn.setThumb('thumb_l');
					break;
			};

			// カテゴリ表示の状態を設定
			if(allUrlParm.c != null){
				setSortForm.setSort(allUrlParm.c);
			};

			// ローカルストレージに保持されている、IDのバーを開く
			if(localStorage['openbar']){
				var openbar = JSON.parse(localStorage['openbar']);
				// console.log(openbar);
				var openbar_length = openbar.length;
				for (var i = 0; i < openbar_length; i++) {
					var $thisid = $('#'+openbar[i]);
					if(!$thisid.hasClass('o-show')){
						$thisid.click();
					}
				};
			}

			// シングルビューの状態を設定
			if(allUrlParm.row != null || allUrlParm.p != null){
				singleView.setParm();
				singleView.showArea(allUrlParm.row,allUrlParm.p,allUrlParm.col);
			};

		};





	// ------------------------------------------------------------
	// ソート絞り込み
	// ------------------------------------------------------------

		var setSortForm = {
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};
				this.el.$sortform = $('#sortform');
				this.el.$ctsbox = $('.m-ctsbox');
			},
			setEvent:function(){
				var that = this;
				that.el.$sortform.change(function() {
					var sortid= $(this).val();
					that.setSort(sortid);
				});

			},
			setSort:function(sortid){
				this.el.$sortform.val(sortid);
				if(sortid != ''){

					this.el.$ctsbox.find('h3').addClass('o-hide').removeClass('o-show');
					this.el.$ctsbox.find('ul').addClass('o-hide').removeClass('o-show');

					$('#row-'+sortid).removeClass('o-hide').addClass('o-show').prev('h3').removeClass('o-hide').addClass('o-show');

				}else{

					this.el.$ctsbox.find('h3').removeClass('o-hide').removeClass('o-show');
					this.el.$ctsbox.find('ul').removeClass('o-hide').removeClass('o-show');

				}
				setURL({category:sortid});

			}
		}




	// ------------------------------------------------------------
	// サムネイル切替
	// ------------------------------------------------------------

		var setThumbBtn = {
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};
				this.el.$view_list = $('#js-view-list');
				this.el.$view_thumb = $('#js-view-thumb');

				this.el.$view_list_l = $('#js-view-list-l');
				this.el.$view_thumb_l = $('#js-view-thumb-l');

				this.el.$ctsbox = $('.m-ctsbox');
				this.el.$viewnow = $('#js-view-now');
				this.el.$viewselect = $('#js-view-select');
				this.el.$viewselect_cts = this.el.$viewselect.find('.btn-set-item');

			},
			setEvent:function(){
				var that = this;

				this.el.$viewnow.on('click',function(){
					if(!$(this).hasClass('act')){
						$(this).addClass('act');
						that.el.$viewselect.show();
					}else{
						$(this).removeClass('act');
						that.el.$viewselect.hide();
					}
				});

				this.el.$view_list.on('click',function() {
					if(!$(this).hasClass('act')){
						that.setThumb('list');
					}
				});
				this.el.$view_list_l.on('click',function() {
					if(!$(this).hasClass('act')){
						that.setThumb('list_l');
					}
				});

				this.el.$view_thumb.on('click',function() {
					if(!$(this).hasClass('act')){
						that.setThumb('thumb');
					}
				});
				this.el.$view_thumb_l.on('click',function() {
					if(!$(this).hasClass('act')){
						that.setThumb('thumb_l');
					}
				});

			},


			setThumb:function(check_thumb){

				this.el.$viewselect_cts.find('.m-btn01').removeClass('act');

				switch (check_thumb){
					case "thumb":

						// レイアウト変更切り替えエリア、自分のボタンをアクティブにする
						this.el.$view_thumb.addClass('act');

						// 大枠のクラススタイルを追加＆削除
						this.el.$ctsbox.removeClass('noimg').removeClass('noimg-l').addClass('con-thumb').removeClass('con-thumb-l');

						// レイアウト変更切り替えボタンのアイコン差し替え
						this.el.$viewnow.removeClass('btn01--icn-list').removeClass('btn01--icn-list-l').addClass('btn01--icn-thumb').removeClass('btn01--icn-thumb-l');

						// URL変更
						setURL({mode:'thumb'});
						break;
					case "thumb_l":

						// レイアウト変更切り替えエリア、自分のボタンをアクティブにする
						this.el.$view_thumb_l.addClass('act');

						// 大枠のクラススタイルを追加＆削除
						this.el.$ctsbox.removeClass('noimg').removeClass('noimg-l').addClass('con-thumb').addClass('con-thumb-l');

						// レイアウト変更切り替えボタンのアイコン差し替え
						this.el.$viewnow.removeClass('btn01--icn-list').removeClass('btn01--icn-list-l').addClass('btn01--icn-thumb').addClass('btn01--icn-thumb-l');

						// URL変更
						setURL({mode:'thumb_l'});
						break;
					case "list":

						// レイアウト変更切り替えエリア、自分のボタンをアクティブにする
						this.el.$view_list.addClass('act');

						// 大枠のクラススタイルを追加＆削除
						this.el.$ctsbox.addClass('noimg').removeClass('noimg-l').removeClass('con-thumb').removeClass('con-thumb-l');

						// レイアウト変更切り替えボタンのアイコン差し替え
						this.el.$viewnow.addClass('btn01--icn-list').removeClass('btn01--icn-list-l').removeClass('btn01--icn-thumb').removeClass('btn01--icn-thumb-l');

						// URL変更
						setURL({mode:'list'});
						break;
					case "list_l":

						// レイアウト変更切り替えエリア、自分のボタンをアクティブにする
						this.el.$view_list_l.addClass('act');

						// 大枠のクラススタイルを追加＆削除
						this.el.$ctsbox.addClass('noimg').addClass('noimg-l').removeClass('con-thumb').removeClass('con-thumb-l');

						// レイアウト変更切り替えボタンのアイコン差し替え
						this.el.$viewnow.addClass('btn01--icn-list').addClass('btn01--icn-list-l').removeClass('btn01--icn-thumb').removeClass('btn01--icn-thumb-l');

						// URL変更
						setURL({mode:'list_l'});
						break;
					default:
						break;
				};

				this.el.$viewnow.removeClass('act');
				this.el.$viewselect.hide();
			}
		};



	// ------------------------------------------------------------
	// 全て展開、全て折りたたむボタン
	// ------------------------------------------------------------
		var setToggleBtn = {
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};
				this.el.$toggle = $('#js-toggle');
				this.el.$ctsbox = $('.m-ctsbox');
			},
			setEvent:function(){
				var that = this;
				that.el.$toggle.click(
					function(){
						if($(this).hasClass('o-show')){
							that.toggleClose();
						}else{
							that.toggleOpen();
						}

					}
				);
			},
			toggleClose:function(){
				this.el.$ctsbox.find('h3:not(.disabled)').removeClass('o-show');
				this.el.$ctsbox.find('ul:not(.disabled)').removeClass('o-show');
				this.el.$toggle.removeClass('o-show');
				this.el.$toggle.text('＋ 全て展開');
				setURL({open:'0'});

				// 開閉状態保持フラグを全初期化
				THE_PREVIEW.DATABASE.openBar = [];
				setLocalStorageOpenBar();

			},
			toggleOpen:function(){
				this.el.$ctsbox.find('h3:not(.disabled)').addClass('o-show');
				this.el.$ctsbox.find('ul:not(.disabled)').addClass('o-show');
				this.el.$toggle.addClass('o-show');
				this.el.$toggle.text('－ 全て折りたたむ');
				setURL({open:'1'});

				// 開閉状態保持フラグを全要素にたてる
				this.el.$ctsbox.find('h3:not(.disabled)').each(function (i) {
					var myid = $(this).attr('id');

					// 開閉状態保持フラグに存在しない場合は追加
					if ($.inArray(myid, THE_PREVIEW.DATABASE.openBar) == -1){
						THE_PREVIEW.DATABASE.openBar.push(myid);
					}

				});
				// console.log(THE_PREVIEW.DATABASE.openBar);
				setLocalStorageOpenBar();

			}

		};

	// ------------------------------------------------------------
	// コンテンツ開閉処理
	// ------------------------------------------------------------
		var setCtsToggleEvent = function (){
			$(document).on('click','h3',function(){
				if(!$(this).hasClass('disabled')){
					if($(this).hasClass('o-show')){
						$(this).removeClass('o-show');
						$(this).next().removeClass('o-show');

						// 配列から削除
						deleteArrayVal(THE_PREVIEW.DATABASE.openBar,$(this).attr('id'));
						// console.log(THE_PREVIEW.DATABASE.openBar);
						setLocalStorageOpenBar();

					}else{

						$(this).addClass('o-show');
						$(this).next().addClass('o-show');

						THE_PREVIEW.DATABASE.openBar.push($(this).attr('id'));
						// console.log(THE_PREVIEW.DATABASE.openBar);
						setLocalStorageOpenBar();

					}
				}
			});
		}


	// ------------------------------------------------------------
	// ローカルストレージにバーの開閉状態を保存する
	// ------------------------------------------------------------
		var setLocalStorageOpenBar = function (){
			localStorage['openbar'] = JSON.stringify(THE_PREVIEW.DATABASE.openBar);
		}


	// ------------------------------------------------------------
	// リンクボタン
	// ------------------------------------------------------------
		var setTtlLinkBtn = function(){
			$(document).on('click','.ttl01__link',function(){
				$(this).parent().next().find('.imglist__vis').find('a').eq(0).click();
			});
		}


	// ------------------------------------------------------------
	// チェックボックス
	// ------------------------------------------------------------
		var setCheckBox = {
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};
				this.el.$ctsbox = $('.m-ctsbox');
				this.el.$cheakarea = $('#js-cheakarea');
			},
			setEvent:function(){
				var that = this;
				var $input = this.el.$ctsbox.find('.m-imglist').find('input');

				$input.click(function(){
					var checkBoxFlg = 1;
					$input.each(function (i) {
						if($(this).is(':checked')){
							$(this).parent().addClass('imglist__ckbox--checked');
							checkBoxFlg = 0;
						}else{
							$(this).parent().removeClass('imglist__ckbox--checked');
						}
					});

					if(checkBoxFlg){
						//alert('チェックなし');
						that.el.$cheakarea.hide();
					}else{
						//alert('チェックあり');
						that.el.$cheakarea.show();
					}

				});

			}
		};


	// ------------------------------------------------------------
	// 全選択ボタン
	// ------------------------------------------------------------
		var setAllCheckBtn = {
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};
				this.el.$ctsbox = $('.m-ctsbox');
				this.el.$allcheck = $('#js-allcheck');
				this.el.$cheakarea = $('#js-cheakarea');
			},
			setEvent:function(){
				var that = this;
				var $input = this.el.$ctsbox.find('.m-imglist').find('input');
				var $ttl_input = this.el.$ctsbox.find('.m-ttl01').find('input');

				this.el.$allcheck.on('click',function(){
					if(!$(this).hasClass('on')){
						$(this).addClass('on').html('<i class="fa fa-check-square-o"></i>');
						$input.each(function (i) {
							$(this).prop('checked',true).parent().addClass('imglist__ckbox--checked');
						});
						$ttl_input.each(function (i) {
							$(this).prop('checked',true).addClass('on');
						});
						that.el.$cheakarea.show();
					}else{
						$(this).removeClass('on').html('<i class="fa fa-square-o"></i>');
						$input.each(function (i) {
							$(this).prop('checked',false).parent().removeClass('imglist__ckbox--checked');
						});
						$ttl_input.each(function (i) {
							$(this).prop('checked',false).removeClass('on');
						});
						that.el.$cheakarea.hide();
					}
				});

			}
		};


	// ------------------------------------------------------------
	// 削除ボタン
	// ------------------------------------------------------------
		var setDeleteBtn = {
			deleteimgs:[],
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};
				this.el.$ctsbox = $('.m-ctsbox');
				this.el.$del = $('#js-del');
				this.el.$delwin = $('#js-modalwindow');
				this.el.$delwinbox = $('#js-delwin-box');

				this.el.$delimglist = $('#js-delimglist');
				this.el.$delclose = $('#js-delclose');
				this.el.$delstart = $('#js-delstart');
			},
			setEvent:function(){
				var that = this;
				var $input = this.el.$ctsbox.find('input');
				this.el.$del.on('click',function(){
					that.el.$delwin.removeClass('o-hide');
					that.el.$delwinbox.removeClass('o-hide');
				});
				this.el.$delclose.on('click',function(){
					that.el.$delwin.addClass('o-hide');
					that.el.$delwinbox.addClass('o-hide');
					that.el.$delimglist.html('');
				});
				this.el.$delstart.on('click',function(){

					that.deleteimgs = [];
					$input.each(function (i) {
						if($(this).is(':checked')){
							var imgsrc = $(this).closest('li').find('.imglist__filename').text();
							that.deleteimgs.push(imgsrc);
							$(this).closest('li').remove();
						}
					});

					// xml取得
					$.ajax({
						type: "post",
						url: './api/delete.php',
						data:{
							deletefile:that.deleteimgs
						},
						success: function(xml){
							that.el.$delwin.addClass('o-hide');
							that.el.$delwinbox.addClass('o-hide');
							that.el.$delimglist.html('');
						}
					});

				});
			}
		};




	// ------------------------------------------------------------
	// Linkボタン - CheckBox
	// ------------------------------------------------------------
		var setLinkBtnCheck = {
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};

				this.el.$linkBtn = $('#js-linkcheck');
				this.el.$modalWin = $('#js-modalwindow');
				this.el.$linkwin = $('#js-linkwin');
				this.el.$btnClose = $('#js-link__btn--close');
				this.el.$linktxt = $('#js-linktxt');
				this.el.$linktit = $('#js-link-tit');
				this.el.$checkbox = $('.imglist__ckbox').find('input');

				// タイトル
				this.el.$imgViewImgtitle = $('#js-imgview-imgtitle');

			},
			setEvent:function(){
				var that = this;

				this.el.$linkBtn.on('click',function(){

					var protocol = location.protocol;
					var host = location.host;
					var pathname = location.pathname;
					var vPath = protocol+'//'+host + pathname + 'v/?img=';

					var srcArray = [];
					that.el.$checkbox.each(function (i) {
						if($(this).is(':checked')){
							srcArray.push(vPath + $(this).closest('li').find('.imglist__filename').text());
						}
					});

					that.el.$linktit.text('PermaLink - ' + srcArray.length + ' link');
					that.el.$linktxt.val(srcArray.join('\n'));

					that.el.$modalWin.removeClass('o-hide');
					that.el.$linkwin.removeClass('o-hide');

				});

				that.el.$linktxt.on('click',function(){
					this.select();
				});

				this.el.$btnClose.on('click',function(){
					that.el.$modalWin.addClass('o-hide');
					that.el.$linkwin.addClass('o-hide');
					that.el.$linktxt.val('');
				});

			}
		};








	// ------------------------------------------------------------
	// エリア選択ボタン
	// ------------------------------------------------------------
		var setAreaCheckBtn = {
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};
				this.el.$ttlCkboxInput = $('.ttl01__ckbox').find('input');
				this.el.$cheakarea = $('#js-cheakarea');
				this.el.$allckbox = $('.m-imglist').find('input');

			},
			setEvent:function(){
				var that = this;
				this.el.$ttlCkboxInput.on('click',function(e){
					var checkBoxFlg = 1;

					var $input = $(this).closest('h3').next('ul').find('.imglist__ckbox').find('input');
					if(!$(this).hasClass('on')){

						$(this).addClass('on');
						$input.each(function (i) {
							$(this).prop('checked',true).parent().addClass('imglist__ckbox--checked');
						});
						if($(this).closest('.m-ttl01').hasClass('o-show')){
							e.stopPropagation();
						}
					}else{

						$(this).removeClass('on');
						$input.each(function (i) {
							$(this).prop('checked',false).parent().removeClass('imglist__ckbox--checked');
						});
						e.stopPropagation();
					}

					that.el.$allckbox.each(function (i) {
						if($(this).is(':checked')){
							checkBoxFlg = 0;
						}
					});

					if(checkBoxFlg){
						//alert('チェックなし');
						that.el.$cheakarea.hide();
					}else{
						//alert('チェックあり');
						that.el.$cheakarea.show();
					}

				});

			}
		};









	/**
	 * 選択した画像をグループにして複数画像表示ボタン
	 */
		var setMultiViewBtn = {
			imgs:[],
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};
				this.el.$ctsbox = $('.m-ctsbox');
				this.el.$view = $('#js-view');
			},
			setEvent:function(){
				var that = this;
				var $input = this.el.$ctsbox.find('.imglist__item').find('input');
				this.el.$view.on('click',function(){
					that.imgs = [];
					$input.each(function (i) {
						if($(this).is(':checked')){
							var $thisImg = $(this).closest('li').find('.imglist__vis').find('img');
							var imgobj = {
								src:$(this).closest('li').find('.imglist__filename').text(),
								w:$thisImg.data('width'),
								h:$thisImg.data('height'),
								w2:$thisImg.data('width_helf')
							};
							that.imgs.push(imgobj);
						}
					});
					setSelectSingleView.setImg(that.imgs);
				});
			}
		};


	/**
	 * Select Single ページ
	 * @Summary：チェックの付いた画像だけ表示するモード
	 */
		var setSelectSingleView = {
			init:function(){
				this.getElements();
				this.setParm();
			},
			imgObjList:[],
			imgSrcObj:[],
			urlparm:'',
			//closeFlg:false,
			getElements:function(){
				this.el = {};
				this.el.$page = $('#page');
				this.el.$imgview = $('#imgview');
				this.el.$imgviewlist = $('#imgviewlist');
				this.el.$btn_close = $('#btn_close');

				this.el.$cattitle = $('#js-imgview-cattitle');
				this.el.$imgtitle = $('#js-imgview-imgtitle');
				this.el.$imgurl = $('#js-imgview-imgurl');

				this.el.$imgviewhead = $('#imgviewhead');
				this.el.$nav_l = $('#js-nav-l');
				this.el.$nav_r = $('#js-nav-r');
				this.el.$navfrm_l = $('#js-navfrm-l');
				this.el.$navfrm_r = $('#js-navfrm-r');

				this.el.$nav_l2 = $('#js-chevron-left');
				this.el.$nav_r2 = $('#js-chevron-right');

				this.el.$autoscroll = $('#js-autoscroll');

			},
			setImg:function(imgobj){

				// setSelectSingleViewの場合はカテゴリを非表示
				this.el.$cattitle.text('');

				// setSelectSingleViewの場合はURLを空に。
				this.el.$imgurl.html('');

				this.imgObjList = [];
				this.imgSrcObj = [];
				var imgobj_length = imgobj.length;

				var all_width = 0;

				for (var i = 0; i < imgobj_length; i++) {
					this.imgSrcObj.push(imgobj[i].src);
					var addtag = '<p class="views" data-src="'+imgobj[i].src+'" style="width:'+imgobj[i].w+'px; height:'+imgobj[i].h+'px; margin-left:-'+imgobj[i].w2+'px;"><img src="./data/img/'+imgobj[i].src+'"></p>';
					this.imgObjList.push(addtag);
					all_width = all_width + imgobj[i].w + 40;
				};

				// マルチカラム用に、画像数分の横幅を指定
				this.el.$imgviewlist.css('width',all_width+'px').append(this.imgObjList.join(''));
				this.el.$autoscroll.css('width',all_width+'px');

				this.el.$page.hide();
				this.el.$imgview.show();

				var $views = this.el.$imgviewlist.find('.views');
				var img_length = $views.length;
				if(img_length > 0){
					this.setPageNation($views,img_length,0);
				}
				if(img_length > 1){
					$views.css({'cursor':'pointer'})
				}

				if(THE_PREVIEW.DATABASE.url.col == '1'){
					setViewChangeBtn.setMultiColmunView();
				}else{
					setViewChangeBtn.setSingleColmunView();
				}

				window.history.pushState('single','',this.urlparm + '&col=' + THE_PREVIEW.DATABASE.url.col + '&group=' + this.imgSrcObj.join(','));
				this.showPage(0);
				this.setNav();

			},
			// ページを開く
			showPage:function(indexNo){
				$('html,body').animate({scrollTop: 0}, 0);
				var $views = this.el.$imgviewlist.find('.views');
				$views.hide();
				$views.eq(indexNo).show();
				this.el.$imgviewlist.css({'height':$views.eq(indexNo).height() +'px'});
				$('body').addClass('mode-imgview');
				singleView.closeFlg = false;

				// ヘッダに画像名を設定
				this.el.$imgtitle.text($views.eq(indexNo).data('src'));

			},

			// ナビゲーションの設定
			setNav:function(){
				var that = this;

				// ヘッダ
				this.el.$imgviewhead.find('.js-imgviewhead__frm').stop(false,false).delay(1000).animate({'top':'-50px'},200);
				this.el.$imgviewhead.hover(function(){
					$(this).find('.js-imgviewhead__frm').stop(false,false).animate({'top':'0'},200);
				},function(){
					$(this).find('.js-imgviewhead__frm').stop(false,false).animate({'top':'-50px'},200);
				});

				// 左右ページング
				this.el.$navfrm_l.stop(false,false).delay(1000).animate({'left':'0'},200);
				this.el.$navfrm_r.stop(false,false).delay(1000).animate({'left':'100px'},200);

				this.el.$nav_l.hover(function(){
					that.el.$navfrm_l.stop(false,false).animate({'left':'100px'},200);
				},function(){
					that.el.$navfrm_l.stop(false,false).animate({'left':'0'},200);
				});

				this.el.$nav_r.hover(function(){
					that.el.$navfrm_r.stop(false,false).animate({'left':'0'},200);
				},function(){
					that.el.$navfrm_r.stop(false,false).animate({'left':'100px'},200);
				});

			},

			// ページネーション
			setPageNation:function($imgs,img_length,indexNo,areaID){
				var that = this;

				// 画像
				$imgs.on('click',function(){
					indexNo = parseInt(indexNo,10) + 1;
					if(!$('body').hasClass('view-colmun')){
						if(indexNo >= img_length){
							indexNo = 0;
						}
						that.showPage(indexNo);
					}else{

						indexNo = that.el.$imgviewlist.find('.views').index(this);
						setViewChangeBtn.setSingleColmunView();
						that.showPage(indexNo);

					}

				});

				// 右ナビ
				this.el.$nav_r.find('p').on('click',function(){
					indexNo = parseInt(indexNo,10) + 1;
					if(indexNo >= img_length){
						indexNo = 0;
					}
					that.showPage(indexNo);
				});

				this.el.$nav_r2.on('click',function(){
					indexNo = parseInt(indexNo,10) + 1;
					if(indexNo >= img_length){
						indexNo = 0;
					}
					that.showPage(indexNo);
				});

				// 左ナビ
				this.el.$nav_l.find('p').on('click',function(){
					indexNo = parseInt(indexNo,10) -1;
					if(indexNo < 0){
						indexNo = img_length - 1;
					}
					that.showPage(indexNo);
				});
				this.el.$nav_l2.on('click',function(){
					indexNo = parseInt(indexNo,10) -1;
					if(indexNo < 0){
						indexNo = img_length - 1;
					}
					that.showPage(indexNo);
				});


			},
			setParm:function(){

				var allUrlParm = getAllUrlParm();
				this.urlparm = './?sort='+allUrlParm.sort+'&c='+allUrlParm.c+'&m='+allUrlParm.m+'&o='+allUrlParm.o;

			},

			/**
			 * 初期表示の際、URLパラメータにgroup が存在したらグループビューで実行
			 */
			groupCheck:function(){
				var allUrlParm = getAllUrlParm();
				if(allUrlParm.group != null){
					var img_group = allUrlParm.group.split(",");
					var img_group_length = img_group.length;
					// console.log('! = ' + img_group_length);
					for (var i = 0; i < img_group_length; i++) {
						$('.imglist__filename').each(function (j) {
							if(img_group[i] == $(this).text()){
								$(this).closest('li').find('.imglist__ckbox').find('input').prop('checked', true);
							}
						});
					};
					setMultiViewBtn.el.$view.click();
				}
			}
		};


	/**
	 * スクロール位置の保持
	 */
		var getScrollPosition = {
			y:0,
			init:function(){
				this.getElements();
				this.setEvents();
			},
			getElements:function(){
				this.el = {};
				this.el.$w = $(window);
				this.el.$header = $('.header');
			},
			setEvents:function(){
				var that = this;
				this.el.$w.scroll(function () {
					if(singleView.closeFlg){
						// console.log('singleView.closeFlg = ' + singleView.closeFlg);
						that.y = $(this).scrollTop();
						// console.log('that.y = ' + that.y);
						if(that.y > 0){
							that.el.$header.addClass('active');
						}else{
							that.el.$header.removeClass('active');
						}
					}
				});
			}
		};


	/**
	 * 画像表示ページで実行処理
	 */
		var singleView = {
			init:function(){
				this.getElements();
				this.setEvent();
			},
			imgObjList:[],
			urlparm:'',
			closeFlg:true,
			getElements:function(){
				this.el = {};
				this.el.$hb = $('html,body');

				this.el.$vis = $('.imglist__vis').find('a');
				this.el.$imgview = $('#imgview');
				this.el.$imgviewlist = $('#imgviewlist');
				this.el.$btn_close = $('#btn_close');
				this.el.$ctsbox = $('.m-ctsbox');
				this.el.$page = $('#page');

				this.el.$cattitle = $('#js-imgview-cattitle');
				this.el.$imgtitle = $('#js-imgview-imgtitle');
				this.el.$imgurl = $('#js-imgview-imgurl');

				this.el.$imgviewhead = $('#imgviewhead');

				this.el.$viewchange = $('#js-view-change');

				this.el.$nav_l = $('#js-nav-l');
				this.el.$nav_r = $('#js-nav-r');

				this.el.$navfrm_l = $('#js-navfrm-l');
				this.el.$navfrm_r = $('#js-navfrm-r');

				this.el.$nav_l2 = $('#js-chevron-left');
				this.el.$nav_r2 = $('#js-chevron-right');

				this.el.$autoscroll = $('#js-autoscroll');

			},

			// URLを変更する
			changeURL:function(parentID,indexNo){

				this.setParm();
				if(parentID == null || indexNo == null){
					window.history.pushState('top','',this.urlparm);
				}else{
					window.history.pushState('single','',this.urlparm + '&row=' + parentID + '&col=' + THE_PREVIEW.DATABASE.url.col + '&p=' + indexNo);
				}

			},
			// ページを開く
			showPage:function(areaID,indexNo){
				var $views = this.el.$imgviewlist.find('.views');
				$views.hide();
				$views.eq(indexNo).show();
				this.el.$imgviewlist.css({'height':$views.eq(indexNo).height() +'px'});

				// ヘッダに画像名を設定
				this.el.$imgtitle.text($views.eq(indexNo).data('src'));

			},

			// エリアを開く
			showArea:function(areaID,indexNo,colmunType){
				var that = this;

				// ページを先頭に移動
				this.el.$hb.animate({scrollTop: 0}, 0);

				// 選択した画像の所属するカテゴリ名を取得し、ヘッダにカテゴリ名を設定
				var categoryName = $('#row-'+areaID).prev('h3').find('.ttl01__t').text();
				that.el.$cattitle.html('<i class="fa fa-bookmark-o"></i> '+categoryName);

				// 選択した画像の所属するカテゴリのURLが存在する場合は、ヘッダにURLを設定
				var categoryURL = $('#row-'+areaID).prev('h3').find('.site').find('a').attr('href');
				if(categoryURL != null){
					that.el.$imgurl.html('<a href="'+categoryURL+'" target="_blank"></a>');
				}

				that.imgObjList = [];
				idObj = [];

				var $ul = $('#row-'+areaID);
				var $vis = $ul.find('.imglist__vis').find('a');
				var vis_length = $vis.length;

				var all_width = 0;

				$vis.each(function (i) {

					var img = $(this).find('img');
					var src = img.attr('src');
					var w = img.data('width');
					var w2 = img.data('width_helf');
					var h = img.data('height');

					var src_after = src.replace('thumbnail/','img/');
					var src_data = src.replace('./data/thumbnail/','');
					var src_id = src_data.replace('.jpg','');
					src_id = src_id.replace('.gif','');
					src_id = src_id.replace('.png','');

					var addtag = '<p class="views" data-src="'+src_data+'" style="width:'+w+'px; height:'+h+'px; margin-left:-'+w2+'px;"><img src="'+src_after+'"></p>';
					that.imgObjList.push(addtag);
					idObj.push(src_id);

					all_width = all_width + w + 40;

				});

				// マルチカラム用に、画像数分の横幅を指定
				this.el.$imgviewlist.css('width',all_width+'px').append(that.imgObjList.join(''));
				this.el.$autoscroll.css('width',all_width+'px');

				var $views = this.el.$imgviewlist.find('.views');
				var img_length = $views.length;
				if(img_length > 0){
					this.setPageNation($views,img_length,indexNo,areaID);
				}
				if(img_length > 1){
					$views.css({'cursor':'pointer'});

					if(!$('body').hasClass('view-colmun')){
						this.el.$nav_l.show();
						this.el.$nav_r.show();
						this.el.$nav_l2.show();
						this.el.$nav_r2.show();
					}

					this.el.$viewchange.show();
				}else{

					this.el.$nav_l.hide();
					this.el.$nav_r.hide();
					this.el.$nav_l2.hide();
					this.el.$nav_r2.hide();

					this.el.$viewchange.hide();
				}

				if(THE_PREVIEW.DATABASE.url.col == '1'){
					setViewChangeBtn.setMultiColmunView();
				}else{
					setViewChangeBtn.setSingleColmunView();
				}

				this.showPage(areaID,indexNo);
				this.changeURL(areaID,indexNo);

				this.el.$hb.addClass('mode-imgview');
				this.el.$page.hide();
				this.el.$imgview.show();

				this.setNav();

				this.closeFlg = false;
			},

			// ページネーション
			setPageNation:function($imgs,img_length,indexNo,areaID){
				var that = this;

				// 画像
				$imgs.on('click',function(){
					indexNo = parseInt(indexNo,10) + 1;
					if(!$('body').hasClass('view-colmun')){
						if(indexNo >= img_length){
							indexNo = 0;
						}

						that.showPage(areaID,indexNo);
						that.changeURL(areaID,indexNo);

					}else{

						indexNo = that.el.$imgviewlist.find('.views').index(this);
						setViewChangeBtn.setSingleColmunView();
						that.showPage(areaID,indexNo);
						that.changeURL(areaID,indexNo);
					}

				});

				// 右ナビ
				this.el.$nav_r.find('p').on('click',function(){
					indexNo = parseInt(indexNo,10) + 1;
					if(indexNo >= img_length){
						indexNo = 0;
					}
					that.showPage(areaID,indexNo);
					that.changeURL(areaID,indexNo);
				});
				this.el.$nav_r2.on('click',function(){
					indexNo = parseInt(indexNo,10) + 1;
					if(indexNo >= img_length){
						indexNo = 0;
					}
					that.showPage(areaID,indexNo);
					that.changeURL(areaID,indexNo);
				});

				// 左ナビ
				this.el.$nav_l.find('p').on('click',function(){
					indexNo = parseInt(indexNo,10) -1;
					if(indexNo < 0){
						indexNo = img_length - 1;
					}
					that.showPage(areaID,indexNo);
					that.changeURL(areaID,indexNo);
				});

				// 左ナビ
				this.el.$nav_l2.on('click',function(){
					indexNo = parseInt(indexNo,10) -1;
					if(indexNo < 0){
						indexNo = img_length - 1;
					}
					that.showPage(areaID,indexNo);
					that.changeURL(areaID,indexNo);
				});

			},

			setNav:function(){
				var that = this;

				// ヘッダ
				this.el.$imgviewhead.find('.js-imgviewhead__frm').stop(false,false).delay(500).animate({'top':'-50px'},200);
				this.el.$imgviewhead.hover(function(){
					$(this).find('.js-imgviewhead__frm').stop(false,false).animate({'top':'0'},200);
				},function(){
					$(this).find('.js-imgviewhead__frm').stop(false,false).animate({'top':'-50px'},200);
				});

				// 左右ページング
				this.el.$navfrm_l.stop(false,false).delay(500).animate({'left':'0'},200);
				this.el.$navfrm_r.stop(false,false).delay(500).animate({'left':'100px'},200);

				this.el.$nav_l.hover(function(){
					that.el.$navfrm_l.stop(false,false).animate({'left':'100px'},200);
				},function(){
					that.el.$navfrm_l.stop(false,false).animate({'left':'0'},200);
				});

				this.el.$nav_r.hover(function(){
					that.el.$navfrm_r.stop(false,false).animate({'left':'0'},200);
				},function(){
					that.el.$navfrm_r.stop(false,false).animate({'left':'100px'},200);
				});

			},

			// シングルビューを閉じる
			closeImgView:function(){
				this.el.$imgview.hide();
				this.el.$hb.removeClass('mode-imgview');
				this.el.$page.show();

				this.el.$imgurl.html('');
				this.el.$imgviewlist.html('');
				this.changeURL();
				this.el.$hb.animate({ scrollTop: getScrollPosition.y+'px' }, 0);

				this.el.$imgviewhead.find('.js-imgviewhead__frm').css({'top':'0'});
				this.el.$nav_l.find('p').css({'left':'100px'});
				this.el.$nav_r.find('p').css({'left':'0'});

				singleView.closeFlg = true;
			},
			setParm:function(){
				var allUrlParm = getAllUrlParm();
				this.urlparm = './?sort='+allUrlParm.sort+'&c='+allUrlParm.c+'&m='+allUrlParm.m+'&o='+allUrlParm.o;
			},
			setEvent:function(){
				var that = this;

				// 画像をクリック時
				this.el.$vis.on('click',function(){
					var $ul = $(this).closest('ul');

					// ID取得
					var areaID = $ul.attr('id').replace('row-','');

					// index値取得
					var $vis = $ul.find('.imglist__vis').find('a');
					var indexNo = $vis.index(this);

					that.setParm();
					that.showArea(areaID,indexNo);
					return false;
				});

				// タイトルをクリック時
				this.el.$ctsbox.find('.imglist__tit').on('click',function(){
					var $ul = $(this).closest('ul');

					// ID取得
					var areaID = $ul.attr('id').replace('row-','');

					// index値取得
					var $tit = $ul.find('.imglist__tit');
					var indexNo = $tit.index(this);

					that.setParm();
					that.showArea(areaID,indexNo);
					return false;
				});

				// 閉じるボタン
				this.el.$btn_close.on('click',function(){
					that.closeImgView();
					return false;
				});

			}

		};


	/**
	 * 画像表示切り替え処理 シングル／マルチ ビュー
	 */
		var setViewChangeBtn = {
			init:function(){
				this.getElements();
				this.setEvents();
			},
			getElements:function(){
				this.el = {};
				this.el.$body = $('body');
				this.el.$autoScroll = $('#js-autoscroll');
				this.el.$viewchangeBtn = $('#js-view-change');
				this.el.$imgviewlist = $('#imgviewlist');

				this.el.$imgtitle = $('#js-imgview-imgtitle');

				this.el.$chevronLeft = $('#js-chevron-left');
				this.el.$chevronRight = $('#js-chevron-right');

				this.el.$externalLink = $('#js-external-link');
				this.el.$navL = $('#js-nav-l');
				this.el.$navR = $('#js-nav-r');

			},
			setEvents:function(){
				var that = this;
				that.el.$body.addClass('view-single');

				that.el.$viewchangeBtn.on('click',function(){
					if(that.el.$body.hasClass('view-colmun')){
						that.setSingleColmunView();
					}else{
						that.setMultiColmunView();
					};
				});
			},

			/**
			 * シングルカラムで画像を表示
			 */
			setSingleColmunView:function(){
				this.el.$body.removeClass('view-colmun').addClass('view-single');
				this.el.$imgtitle.show();
				this.el.$chevronLeft.show();
				this.el.$chevronRight.show();
				this.el.$externalLink.show();
				this.el.$navL.show();
				this.el.$navR.show();

				// URLのcolパラメータをシングルカラムモードに変更
				THE_PREVIEW.DATABASE.url.col = 0;
				setUrlColumnMode('0');

			},
			/**
			 * マルチカラムで画像を表示
			 */
			setMultiColmunView:function(){

				this.el.$body.removeClass('view-single').addClass('view-colmun');
				this.el.$imgtitle.hide();
				this.el.$chevronLeft.hide();
				this.el.$chevronRight.hide();
				this.el.$externalLink.hide();
				this.el.$navL.hide();
				this.el.$navR.hide();

				// ----------------------------------------------
				// マウス位置にあわせて自動横スクロール
				// ----------------------------------------------
				this.el.$autoScroll.mouseScroll({
					offsetTop: 100,
					animationSpeed: 100,
					easingFunction: "easeOutBack",
					mousemoveTarget: "#js-autoscroll"
				});

				// URLのcolパラメータをマルチカラムモードに変更
				THE_PREVIEW.DATABASE.url.col = 1;
				setUrlColumnMode('1');

			}
		};


	// ------------------------------------------------------------
	// Linkボタン - イメージビュー内
	// ------------------------------------------------------------
		var setLinkBtn = {
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};

				this.el.$body = $('body');

				this.el.$linkBtn = $('#js-link');
				this.el.$modalWin = $('#js-modalwindow');
				this.el.$linkwin = $('#js-linkwin');
				this.el.$btnClose = $('#js-link__btn--close');
				this.el.$linktxt = $('#js-linktxt');
				this.el.$linktit = $('#js-link-tit');

				// タイトル
				this.el.$imgViewImgtitle = $('#js-imgview-imgtitle');
				this.el.$imgviewlist = $('#imgviewlist');

			},
			setEvent:function(){
				var that = this;

				this.el.$linkBtn.on('click',function(){

					var protocol = location.protocol;
					var host = location.host;
					var pathname = location.pathname;
					var vPath = protocol+'//'+host + pathname + 'v/?img=';

					if(that.el.$body.hasClass('view-colmun')){
						var srcArray = [];
						var $views = that.el.$imgviewlist.find('.views');

						// マルチヴューモードの場合
						$views.each(function (i) {
							var src = $(this).data('src');
							srcArray.push(vPath + src);
						});

						that.el.$linktit.text('PermaLink - ' + $views.length + ' link');
						that.el.$linktxt.val(srcArray.join('\n'));

					}else{
						that.el.$linktit.text('PermaLink');

						// シングルビューの場合
						var url = that.el.$imgViewImgtitle.text();
						that.el.$linktxt.val(vPath + url);

					}

					that.el.$modalWin.removeClass('o-hide');
					that.el.$linkwin.removeClass('o-hide');

				});

				that.el.$linktxt.on('click',function(){
					this.select();
				});

				this.el.$btnClose.on('click',function(){
					that.el.$modalWin.addClass('o-hide');
					that.el.$linkwin.addClass('o-hide');
					that.el.$linktxt.val('');
				});

			}
		};


	// ------------------------------------------------------------
	// URLボタン
	// ------------------------------------------------------------
		var setUrlBtn = {
			init:function(){
				this.getElements();
				this.setEvent();
			},
			getElements:function(){
				this.el = {};
				this.el.$externalLinkBtn = $('#js-external-link');
				// タイトル
				this.el.$imgViewImgtitle = $('#js-imgview-imgtitle');
			},
			setEvent:function(){
				var that = this;

				this.el.$externalLinkBtn.on('click',function(){
					var url = that.el.$imgViewImgtitle.text();
					window.open('./v/?img='+url);
				});

			}
		};


	/**
	 * URLに変更があった場合に、popstateで起こす処理
	 */
		window.addEventListener('popstate', function(event) {
			if(event.state == 'top'){
				singleView.closeImgView();
			}else if(event.state == 'single'){
				var parms = rjs.getHashData();
				var parms_row = parms['row'];
				var parms_p = parms['p'];
				if(singleView.closeFlg){
					singleView.showArea(parms_row,parms_p);
				}else{
					singleView.showPage(parms_row,parms_p);
				}
			}
		},false );

		var tooltop = function(){
			$('.js-powertip').powerTip({
				placement:'s'
			});
		}
		tooltop();