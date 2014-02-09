<?php
/**
 * /admin/uploader/index.php
 * 管理画面 画像投稿画面
 * @Update : 2014/01/20
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../../config.php');

	// セッション開始
	session_start();

	// セッションIDがなければリダイレクトしてTOPへ
	if (!isset($_SESSION["USERID"])) {
		header("Location: ../../");
		exit;
	}

	// 基本情報 CSV読み込み＆設定
	include(PROJECT_ROOT_PATH.'/api/get_config_data.php');

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $TITLE;?> 画像管理</title>
<link rel="stylesheet" href="../../lib/css/style.v01.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<style>
.header__title,.m-ttl01.o-show{ background-color: #<?php echo $HEAD_BG_COLOR;?> !important; }
.header__title a, .header__login, .m-ttl01.o-show{ color: #<?php echo $HEAD_TXT_COLOR;?> !important; }
</style>
</head>
<body>
<div id="page" class="m-page setting-upload">
	<header class="m-header m-header--fixed">
		<h1 class="header__title"><a href="../../"><?php echo $TITLE;?></a></h1>
		<a href="../../admin/logout/" class="header__login">ログアウト</a>
	</header>
	<div class="m-contents o-cfx">
		<nav class="m-menu m-menu--fixed">
			<p class="menu__btn01"><a href="../../" class="m-btn02"><i class="fa fa-lg fa-arrow-left"></i></a></p>
			<ul>
				<li class="menu__btn menu__btn--active"><a href="./"><i class="fa fa-lg fa-picture-o"></i> 画像投稿</a></li>
				<li class="menu__btn"><a href="../basic/"><i class="fa fa-lg fa-cog"></i> 基本情報登録</a></li>
				<li class="menu__btn"><a href="../category/"><i class="fa fa-lg fa-bookmark-o"></i> カテゴリ登録</a></li>
			</ul>
		</nav>
		<main class="m-main m-main--block01">
			<h2 class="m-ttl02">画像投稿</h2>
			<div class="m-upload">
				<form id="upload" class="upload__form" method="post" action="upload.php" enctype="multipart/form-data">
					<div id="drop" class="upload__area">
						<p class="upload__txt01"><i class="fa fa-lg fa-picture-o"></i><br>ここに画像をドラッグ</p>
						<p class="upload__txt02">- または - </p>
						<a class="upload__txt03">クリックして画像を選択</a>
						<input class="upload__input" type="file" name="upl" multiple>
					</div>
					<ul class="upload__list o-cfx"></ul>
				</form>
			</div>
			<div class="m-box">
				<p class="box__tit">1. 画像投稿について - カテゴリで振り分け</p>
				<ul class="box__txt">
					<li>
						<p>画像名に「カテゴリID」を含めると、「カテゴリ別」ページでカテゴリ分けされ表示されます。</p>
						<div class="mt15">
<?php
// CSVから読み取ったデータを格納
$csv_category = array();

// ファイルを追記モードで開く
$fp = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_CATEGORY, 'r');

// ファイルを排他ロックする
if(flock($fp, LOCK_EX)){
	while( $data = fgetcsv( $fp, 1000, "," ) ) {
		//配列に格納
		array_push($csv_category,$data);
	}
	flock($fp, LOCK_UN);
}

// ファイルを閉じる
fclose($fp);
$length = count($csv_category);
if($length > 0){
?>
							<div class="m-table02">
								<div class="table02__th">
									カテゴリ名
								</div>
								<div class="table02__td">
									カテゴリID
								</div>
							</div>
<?php
for($i=0; $i < $length; $i++) {
?>
							<div class="m-table02">
								<div class="table02__th">
									<?php echo $csv_category[$i][1]; ?>
								</div>
								<div class="table02__td">
									<?php echo $csv_category[$i][0];?>
								</div>
							</div>
<?php }?>
						</div>
<?php }else{ ?>
						<p>カテゴリが現在登録されていません。<br><a href="../category/">カテゴリ登録のページで登録してください。</a></p>
<?php } ?>
					</li>
				</ul>
			</div>
			<div class="m-box">
				<p class="box__tit">2. 画像投稿について - 日付で振り分け</p>
				<ul class="box__txt">
					<li>
						<p>「日付別」ページでは、画像の投稿日で自動的に振り分けされます。</p>
					</li>
					<li>
						<p>画像名に日付を含めることで、投稿日以外の日付に振り分けできます。<br><?php echo date("Y年m月d日");?>の場合「<?php echo date("ymd");?>_xxxxx.jpg」という名前をつけて画像を投稿すると<br>「日付別」の<?php echo date("ymd");?>に振り分けされます。</p>
					</li>
				</ul>
			</div>
		</main>
	</div>
</div>

<!-- JavaScript Includes -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../lib/js/vendor/jquery-2.0.3.min.js"><\/script>')</script>
<script src="assets/js/jquery.knob.js"></script>

<!-- jQuery File Upload Dependencies -->
<script src="assets/js/jquery.ui.widget.js"></script>
<script src="assets/js/jquery.iframe-transport.js"></script>
<script src="assets/js/jquery.fileupload.js"></script>

<!-- Our main JS file -->
<script src="assets/js/script.js"></script>

</body>
</html>