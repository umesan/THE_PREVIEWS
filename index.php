<?php
/**
 * index.php
 * メインページ
 * @Update 2014/01/20
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/config.php');

	// セッション開始
	session_start();

	// ログイン状態の取得とフラグ設置
	if (isset($_SESSION["USERID"])) {
		// ログイン中
		$LOGIN = true;
	}else{
		// ログアウト中
		$LOGIN = false;
	}

	// 基本情報 CSV読み込み＆設定
	include(PROJECT_ROOT_PATH.'/api/get_config_data.php');

	// カテゴリ登録 CSV読み込み＆設定
	include(PROJECT_ROOT_PATH.'/api/get_category_data.php');

	// リスト作成関数読み込み
	include(PROJECT_ROOT_PATH.'/api/getdirtree.php');

	// db.csv の読み込み
	$IMGDB = array();
	if( $handle = fopen(CONFIG_CSV_FILE, 'r' ) ){
		while( ( $lines = fgetcsv( $handle ) ) !== FALSE ){
			$IMGDB[$lines[0]]["id"] = $lines[0];
			$IMGDB[$lines[0]]["star"] = $lines[1];
			$IMGDB[$lines[0]]["txt1"] = $lines[2];
			$IMGDB[$lines[0]]["date"] = $lines[3];
		}
	}
	// print_r($IMGDB);

	// URLパラメータからモードを取得
	if(isset($_GET["sort"])){
		$sorttype = htmlspecialchars($_GET["sort"], ENT_QUOTES);
	}else{
		$sorttype = 'date';
	}

// ------------------------------------------------------------------------------------------------------

	// 画像リスト格納用の配列宣言
	$allCts = array();
	$allCtsID = array();
	$allCtsDay = array();

	// 対象ディレクトリに配置されている画像の一覧をリストとして取得
	$tree = getdirtree(CONFIG_UPLOAD_IMG_DIR);

	//連想配列キーソート(昇順)
	//ksort($tree);

	//連想配列キーソート(降順)
	krsort($tree);
	//print_r($tree);
	//printf(key($tree));

	// 画像一覧を
	while (current($tree)) {

		//パス取得
		$imgsrc = $tree[key($tree)];

		// 画像名の取得と整形
		$imgsrc = mb_convert_encoding($imgsrc, "UTF-8","auto");

		//拡張子取得
		$extension = substr($imgsrc, strrpos($imgsrc, '.') + 1);

		// 取得した拡張子と許可ファイルを比較して一致すれば
		if(in_array(strtolower('.'.$extension), $CONFIG_UPLOAD_IMG_ALLOW_EXE)){

			// 画像名から拡張子を削る
			$img_id = str_replace ($CONFIG_UPLOAD_IMG_ALLOW_EXE, "", $imgsrc);
			// 画像名からディレクトリ名を削る
			$img_id = str_replace (CONFIG_UPLOAD_IMG_DIR, "", $img_id);

			// 現在の画像を表示するか？
			$exportFlg = true;
			if(!$LOGIN){
				// Starがついているコンテンツのみ表示
				if(!$IMGDB[$img_id]["star"]){
					$exportFlg = false;
				}
			}

			if($exportFlg){
				//ファイルサイズ取得
				$get = file_get_contents($imgsrc);
				$size = ceil(strlen($get)/1024);

				// 画像サイズ取得
				list($width,$height) = getimagesize($imgsrc);

				// 画像の横幅の半分の値
				$width_helf = $width / 2;

				//更新日取得
				$mod = filemtime($imgsrc);

				// 画像名の取得と整形
				$imgsrc = str_replace (CONFIG_UPLOAD_IMG_DIR, "", $imgsrc);

				// 画像名に日付が含まれているかチェック
				if (preg_match("|\d{6}|", $imgsrc, $match)) {
					// 日付名が含まれている場合、画像に含まれる日付でソートを行う
					$day = $match[0];
				}else{
					// 日付名が含まれていない場合、画像投稿日によるソートを行う
					$day = $IMGDB[$img_id]["date"];
				}

				// 日付を整形
				$y = mb_substr($day, 0, 2, "UTF-8");
				$m = mb_substr($day, 2, 2, "UTF-8");
				$d = mb_substr($day, 4, 2, "UTF-8");
				$date = $y.'年'.$m.'月'.$d.'日';

				// 配列に取得情報を保存
				$img_info = array(
					'src'=>$imgsrc,
					'img_id'=>$img_id,
					'width'=>$width,
					'height'=>$height,
					'width_helf'=>$width_helf,
					'date' => $date
				);

				// カテゴリ別ページ出力用配列を作成
				array_push($allCtsID,$img_id);

				// カテゴリ別ページ出力用配列を作成
				array_push($allCts,$img_info);

				// 日付別ページ出力用配列を作成
				if (isset($allCtsDay[$day])) {
					// すでにある日付なら、日付配列内の配列に追加
					array_push($allCtsDay[$day],$img_info);
				}else{
					// 新規の日付なら、日付配列を作成
					$allCtsDay[$day] = array($img_info);
				}
			}
		}

		next($tree);
	}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $TITLE;?></title>
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="./lib/css/style.v01.css">
<link rel="stylesheet" href="./lib/css/jquery.powertip.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<style>
.header__title,.m-ttl01.o-show{ background-color: #<?php echo $HEAD_BG_COLOR;?> !important; }
.header__title a, .header__login, .m-ttl01.o-show{ color: #<?php echo $HEAD_TXT_COLOR;?> !important; }
</style>
</head>
<?php if($LOGIN){ ?>
<body data-log="in">
<?php }else{?>
<body data-log="out">
<?php } ?>
<div id="page" class="m-page">

	<header class="m-header m-header--fixed">
		<h1 class="header__title"><a href="./"><?php echo $TITLE;?></a></h1>

<?php if($LOGIN){ ?>
		<a href="./admin/logout/" class="header__login">ログアウト</a>
<?php }else{?>
		<a href="./admin/login/" class="header__login"><i class="fa fa-lock"></i> ログイン</a>
<?php } ?>

		<div class="header__sort o-cfx">
			<form class="header__sort__form">
				<p class="header__toggle-btn"><a id="js-toggle" class="m-btn01">＋ 全て展開</a></p>
				<div class="header__layout-switch o-cfx">
					<p class="view-now"><a id="js-view-now" class="m-btn01 btn01--w1 js-powertip" data-powertip="レイアウト変更"></a></p>
					<div id="js-view-select" class="view-select">
						<p class="txt">レイアウト変更</p>
						<ul class="m-btn-set">
							<li class="btn-set-item"><a id="js-view-thumb" class="m-btn01 btn01--icn-thumb">サムネイル表示</a></li>
							<li class="btn-set-item"><a id="js-view-thumb-l" class="m-btn01 btn01--icn-thumb-l">サムネイル（大）表示</a></li>
							<li class="btn-set-item"><a id="js-view-list" class="m-btn01 btn01--icn-list">リスト表示</a></li>
							<li class="btn-set-item"><a id="js-view-list-l" class="m-btn01 btn01--icn-list-l">リスト（大）表示</a></li>
						</ul>
					</div>
				</div>

<?php
	// ------------------------------------------------------------------------------------------------------------------------------------------
	// 日付別
	// ------------------------------------------------------------------------------------------------------------------------------------------
	if($sorttype == 'date'){
		krsort($allCtsDay);
		// print_r($allCtsDay);
?>

				<select id="sortform" class="header__select">
					<option value="">選択日付のみ表示</option>
					<option value="">全て表示</option>
<?php

	foreach ($allCtsDay as $key => $value) {
		echo '					<option value="'.$key.'">'.$key.'</option>'."\n";
	}
?>
				</select>
<?php } ?>

<?php
	// ------------------------------------------------------------------------------------------------------------------------------------------
	// カテゴリ
	// ------------------------------------------------------------------------------------------------------------------------------------------
	if($sorttype == 'cat'){
?>
				<select id="sortform" class="header__select">
					<option value="">選択カテゴリのみ表示</option>
					<option value="">全て表示</option>
<?php
	foreach ($config_conflist as $key => $value) {
		echo '					<option value="'.$key.'">'.$value[0].'</option>'."\n";
	}
?>
				</select>
<?php } ?>
			</form>
			<p class="header__allcheck"><a id="js-allcheck" class="m-btn01 btn01--w1 js-powertip" data-powertip="画像選択"><i class="fa fa-square-o"></i></a></p>
			<div id="js-cheakarea" class="cheakarea">
				<a id="js-view" class="view m-btn01 btn01--icn-preview js-powertip" data-powertip="選択した画像をグループにして表示"></a>
				<a id="js-linkcheck" class="view m-btn01 js-powertip" data-powertip="選択した画像のリンクを表示"><i class="fa fa-lg fa-link"></i></a>
				<a id="js-download-checkbox" class="view m-btn01 js-powertip" data-powertip="選択した画像をダウンロード"><i class="fa fa-lg fa-download"></i></a>
<?php if($LOGIN){ ?>
				<a id="js-rename-checkbox" class="view m-btn01 js-powertip" data-powertip="選択した画像をリネーム"><i class="fa fa-lg fa-file-text-o"></i></a>
				<a id="js-del" class="del m-btn01 js-powertip" data-powertip="選択した画像を削除"><i class="fa fa-lg fa-trash-o"></i></a>
<?php } ?>
			</div>
		</div>
	</header>

	<div class="m-contents o-cfx">
		<nav class="m-menu m-menu--fixed">
<?php if($LOGIN){ ?>
			<p class="menu__btn01"><a href="./admin/uploader/" class="m-btn02 btn02--upload"><i class="fa fa-lg fa-upload"></i> 画像 UPLOAD</a></p>
<?php } ?>
			<ul class="menu__btnset">
				<li class="menu__btn<?php if($sorttype == 'date'){ echo ' menu__btn--active'; } ?>"><a id="datelink" href="./"><i class="fa fa-calendar-o"></i> 日付別</a></li>
				<li class="menu__btn<?php if($sorttype == 'cat'){ echo ' menu__btn--active'; } ?>"><a id="catlink" href="./?sort=cat"><i class="fa fa-lg fa-bookmark-o"></i> カテゴリ別</a></li>
			</ul>
		</nav>

		<main class="m-main">

<?php if($sorttype == 'date'){ ?>
			<div id="ctsdate" class="m-ctsbox">
				<div class="ctsbox__wrap">
					<div class="m-headbar o-cfx">
						<p class="headbar__date">日付</p>
						<p class="headbar_link">日付に含まれる全ての画像を閲覧</p>
					</div>
<?php
	// 日付別出力
	foreach ($allCtsDay as $key => $value) {

		echo '					<h3 id="cat-'.$key.'" class="m-ttl01"><span class="ttl01__ckbox"><input type="checkbox"></span> <i class="fa fa-folder-o"></i><span class="ttl01__t">'.$key.'</span> <span class="ttl01__link"><i class="fa fa-lg fa-picture-o"></i> 全ての画像を閲覧（'.count($value).'画像）</span></h3>'."\n";
		echo '					<ul id="row-'.$key.'" class="m-imglist o-cfx">'."\n";

		for ($i=0; $i < count($value); $i++) {

				$category = setCategory($value[$i]['src']);
				if($category[0] != ''){
					$category_tag = '<p class="imglist__cat"><i class="fa fa-bookmark-o"></i> '.$category[0].'</p>';
				}else{
					$category_tag = '';
				}

				if($LOGIN){
					if($IMGDB[$value[$i]['img_id']]["star"]){
						$star_tag = '<p class="imglist__star fix" id="star_'.$value[$i]['img_id'].'">★</p>';
					}else{
						$star_tag = '<p class="imglist__star" id="star_'.$value[$i]['img_id'].'">☆</p>';
					}
					$status_btn = '<div class="status__btn"><button class="js-btn_update status__btn-update">更新</button><p class="status__btn-refresh"><i class="fa fa-refresh fa-spin"></i></p></div>';
					$status__txt = '<textarea id="status_'.$value[$i]['img_id'].'" class="status__textarea" placeholder="メモをとる...">'.$IMGDB[$value[$i]['img_id']]["txt1"].'</textarea>';
				}else{
					$star_tag = '';
					$status_btn = '';
					$status__txt = '<div id="status_'.$value[$i]['img_id'].'" class="status__textarea">'.$IMGDB[$value[$i]['img_id']]["txt1"].'</div>';
				}
				$status__short_txt = '<p id="status_short-txt_'.$value[$i]['img_id'].'" class="status__short-txt">'.$IMGDB[$value[$i]['img_id']]["txt1"].'</p>';

print<<<EOF
						<li class="imglist__item o-cfx" data-itemid="{$value[$i]['img_id']}">
							<p class="imglist__ckbox"><input type="checkbox" name="deletefile[]" value="" /></p>
							{$star_tag}
							<p class="imglist__vis"><a href="./?row={$key}&p={$i}" target="_blank"><img src="./data/thumbnail/{$value[$i]['src']}" data-width="{$value[$i]['width']}" data-width_helf="{$value[$i]['width_helf']}" data-height="{$value[$i]['height']}"></a></p>

							<div class="imglist__txt">
								{$category_tag}
								<p class="imglist__tit">
									<span class="imglist__filename">{$value[$i]['src']}</span>
								</p>
							</div>

							<div class="imglist__status m-status">
								{$status__short_txt}
								<div class="status__frm">
									<div class="status__txt">
										{$status__txt}
									</div>
									{$status_btn}
								</div>
							</div>

						</li>
EOF;
		}
		echo '					</ul>'."\n";
	}
	echo '				</div>'."\n";
?>
<?php } ?>

<?php if($sorttype == 'cat'){ ?>
			<div id="ctscategory" class="m-ctsbox">
				<div class="ctsbox__wrap">
					<div class="m-headbar o-cfx">
						<p class="headbar__name">ページ名</p>
						<p class="headbar__id">ID</p>
						<p class="headbar__html">HTML</p>
						<p class="headbar__link">ページに含まれる全ての画像を閲覧</p>
					</div>
<?php
	// カテゴリ別出力
	foreach ($config_conflist as $key => $value) {

		if($value[1] !== ''){
			$site_tag = '<span class="ttl01__site"><a href="'.$value[1].'" target="_blank"><i class="fa fa-list-alt"></i></a></span>';
		}else{
			$site_tag = '<span class="ttl01__site">-</span>';
		}

		$imgcount = 0;
		$tag = null;
		for ($i=0; $i < count($allCtsID); $i++) {

			$pos = strpos($allCtsID[$i], $key);
			if($pos !== false){

				$imgcount = $imgcount + 1;

				if($LOGIN){
					if($IMGDB[$allCtsID[$i]]["star"]){
						$star_tag = '<p class="imglist__star fix" id="star_'.$allCtsID[$i].'">★</p>';
					}else{
						$star_tag = '<p class="imglist__star" id="star_'.$allCtsID[$i].'">☆</p>';
					}
					$status_btn = '<div class="status__btn"><button class="js-btn_update status__btn-update">更新</button><p class="status__btn-refresh"><i class="fa fa-refresh fa-spin"></i></p></div>';
					$status__txt = '<textarea id="status_'.$allCtsID[$i].'" class="status__textarea" placeholder="メモをとる...">'.$IMGDB[$allCtsID[$i]]["txt1"].'</textarea>';
				}else{
					$star_tag = '';
					$status_btn = '';
					$status__txt = '<div id="status_'.$allCtsID[$i].'" class="status__textarea">'.$IMGDB[$allCtsID[$i]]["txt1"].'</div>';
				}
				$status__short_txt = '<p id="status_short-txt_'.$allCtsID[$i].'" class="status__short-txt">'.$IMGDB[$allCtsID[$i]]["txt1"].'</p>';

$tag .= <<<EOF
						<li class="imglist__item o-cfx" data-itemid="{$allCtsID[$i]}">
							<p class="imglist__ckbox"><input type="checkbox" name="deletefile[]" value="" /></p>
							{$star_tag}
							<p class="imglist__vis"><a href="./v/?img={$allCts[$i]['src']}" target="_blank"><img src="./data/thumbnail/{$allCts[$i]['src']}" data-width="{$allCts[$i]['width']}" data-width_helf="{$allCts[$i]['width_helf']}" data-height="{$allCts[$i]['height']}"></a></p>

							<div class="imglist__txt">
								<p class="imglist__date"><i class="fa fa-calendar-o"></i> {$allCts[$i]['date']}</p>
								<p class="imglist__tit">
									<span class="imglist__filename">{$allCts[$i]['src']}</span>
								</p>
							</div>

							<div class="imglist__status m-status">
								{$status__short_txt}
								<div class="status__frm">
									<div class="status__txt">
										{$status__txt}
									</div>
									{$status_btn}
								</div>
							</div>
						</li>

EOF;
			}
		}

		if($imgcount == 0){
			$disabled_class = ' disabled';
		}else{
			$disabled_class = '';
		}

		echo '					<h3 id="cat-'.$key.'" class="m-ttl01'.$disabled_class.'"><span class="ttl01__ckbox"><input type="checkbox"></span> <i class="fa fa-folder-o"></i><span class="ttl01__t">'.$value[0].'</span><span class="ttl01__prefix">'.$key.'</span>'.$site_tag.'<span class="ttl01__link"><i class="fa fa-lg fa-picture-o"></i> 全ての画像を閲覧（'.$imgcount.'画像）</span></h3>'."\n";
		echo '					<ul id="row-'.$key.'" class="m-imglist o-cfx'.$disabled_class.'">'."\n";
		echo $tag;
		echo '					</ul>'."\n";
	}
?>
				</main>
<?php }; ?>
			</div>

		</div>
	</div>
</div>


<!-- SingleImgView -->
<div id="imgview" class="m-imgview">
	<header id="imgviewhead" class="imgview__head">
		<div class="js-imgviewhead__frm imgview__frm">
			<div class="imgview__frm-inner imgview__frm-inner--top">
				<div class="imgview__title">
					<div class="imgview__title_txt">
						<p id="js-imgview-cattitle" class="imgview__category">カテゴリ名</p>
						<p id="js-imgview-imgtitle" class="imgview__imgtitle">画像名</p>
					</div>
					<span id="js-imgview-imgurl" class="imgview__imgurl"></span>
				</div>
			</div>
			<nav class="imgview__nav">
				<span id="js-chevron-left" class="imgview__btn--chevron-left js-powertip" data-powertip="前"><i class="fa fa-lg fa-chevron-left"></i></span>
				<span id="js-chevron-right" class="imgview__btn--chevron-right js-powertip" data-powertip="次"><i class="fa fa-lg fa-chevron-right"></i></span>
				<span id="js-view-change" class="js-powertip" data-powertip="表示形式切替"><i class="fa fa-lg fa-picture-o"></i></span>
				<span id="js-download" class="js-powertip" data-powertip="画像ダウンロード"><i class="fa fa-lg fa-download"></i></span>
				<span id="js-link" class="js-powertip" data-powertip="画像のリンクを表示"><i class="fa fa-lg fa-link"></i></span>
				<span id="js-external-link" class="imgview__btn--external-link js-powertip" data-powertip="画像を開く"><i class="fa fa-lg fa-external-link"></i></span>
				<span id="btn_close" class="js-powertip" data-powertip="閉じる"><i class="fa fa-lg fa-times"></i></span>
			</nav>
		</div>
	</header>
	<div id="js-nav-l" class="imgview__pagenav imgview__pagenav--l">
		<div id="js-navfrm-l" class="imgview__pagenav__frm--l">
			<p class="m-btn__pagenav"><i class="fa fa-3x fa-chevron-left"></i></p>
		</div>
	</div>
	<div id="js-nav-r" class="imgview__pagenav imgview__pagenav--r">
		<div id="js-navfrm-r" class="imgview__pagenav__frm--r">
			<p class="m-btn__pagenav"><i class="fa fa-3x fa-chevron-right"></i></p>
		</div>
	</div>
	<div id="js-autoscroll" class="m-autoscroll autoscroll-fixed-bottom"></div>
	<div id="imgviewlist" class="m-imgviewlist"></div>
</div>

<!-- ModalView__Delete -->
<div id="js-delwin-box" class="modal01__box modal01__box--fix o-hide">
	<p class="modal01__txt">選択した画像を本当に削除しますか？</p>
	<p id="js-delclose" class="m-btn03 js-btn-cancel">キャンセル</p> 
	<p id="js-delstart" class="m-btn03 btn03--delete js-btn-del">削除</p>
</div>

<!-- LinkView -->
<div id="js-linkwin" class="modal01__box modal01__box--fix o-hide">
<p class="ta_l mb15"><i class="fa fa-link"></i> <span id="js-link-tit">PermaLink</span></p>
<textarea id="js-linktxt" class="m-textarea01"></textarea>
<div class="mt15">
	<p id="js-link__btn--close" class="m-btn03">キャンセル</p> 
</div>
</div>

<!-- DownLoadView -->
<div id="js-download-win" class="modal01__box modal01__box--fix o-hide">
<p class="modal01__txt"><i class="fa fa-download"></i> 選択した画像をダウンロードしますか？</p>
<p id="js-download__btn--close" class="m-btn03 js-btn-cancel">キャンセル</p> 
<p id="js-download__btn--start" class="m-btn03 btn03--delete js-btn-del">ダウンロード</p>
</div>

<!-- RenameView -->
<div id="js-rename-win" class="modal01__box modal01__box--fix modal01__box--size02 o-hide">
<p class="ta_l mb15"><i class="fa fa-lg fa-file-text-o"></i> <span>画像をリネーム</span></p>
<div class="js-renamelist modal01__renamearea"></div>
<div class="mt15">
<p id="js-rename__btn--close" class="m-btn03">キャンセル</p>
<p id="js-rename__btn--start" class="m-btn03 btn03--delete">変更</p>
</div>
</div>

<!-- ModalViewBG -->
<div id="js-modalwindow" class="modal01__bg o-hide"></div>

<!-- statusDB -->
<script>
var statusDB = [];
<?php
	foreach ( $IMGDB as $key=>$val){
		echo 'statusDB["'.$val['id'].'"] = {star:"'.$val['star'].'",txt1:"'.$val['txt1'].'",date:"'.$val['date'].'"};'."\n";
	}
?>
</script>
<script src="./lib/js/app.min.js"></script>
</body>
</html>