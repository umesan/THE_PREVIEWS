<?php
/**
 * /admin/basic/index.php
 * 管理画面 カテゴリ登録
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

	// 保存完了通知
	$COMPLEATE = '';
	$CATEGORY_NAME = '';
	$CATEGORY_PREFIX = '';
	$CATEGORY_URL = '';

	// カテゴリ CSV書き込み
	if($_SERVER["REQUEST_METHOD"]=="POST" && !empty ($_POST)){
		if(isset($_POST['post-category'])){

			$fp = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_CATEGORY, 'ab');

			// ファイルを排他ロックする
			if(flock($fp, LOCK_EX)){

				// 中身を消す
				ftruncate($fp, 0);

				// 念のため先頭を置く。
				rewind($fp);

				if(!empty ($_POST["category"])){
					$categoryData = $_POST["category"];
					for ($i=0, $length = count($categoryData); $i < $length; $i++) {
						if($categoryData[$i]['prefix'] != null){
							$ins = $categoryData[$i]['prefix'].','.$categoryData[$i]['name'].','.$categoryData[$i]['url']."\n";
							// データをファイルに書き込む
							fwrite($fp, $ins);
						}
					}
				}

				flock($fp, LOCK_UN);
			}
			// ファイルを閉じる
			fclose($fp);

		}

		$COMPLEATE = '<div id="js-alert" class="m-alert alert--compleate alert--fix">保存しました。</div>';

	}

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

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>管理画面 - カテゴリ登録</title>
<link rel="stylesheet" href="../../lib/css/style.v01.css">
<link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/ui-lightness/jquery-ui.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<style>
.header__title,.m-ttl01.o-show{ background-color: #<?php echo $HEAD_BG_COLOR;?> !important; }
.header__title a, .header__login, .m-ttl01.o-show{ color: #<?php echo $HEAD_TXT_COLOR;?> !important; }
</style>
</head>
<body>
<div id="page" class="m-page setting-config">
	<header class="m-header m-header--fixed">
		<h1 class="header__title"><a href="../../"><?php echo $TITLE;?></a></h1>
		<a href="../../admin/logout/" class="header__login">ログアウト</a>
	</header>
	<?php echo $COMPLEATE; ?>
	<div class="m-contents o-cfx">
		<nav class="m-menu m-menu--fixed">
			<p class="menu__btn01"><a href="../../" class="m-btn02"><i class="fa fa-lg fa-arrow-left"></i></a></p>
			<ul>
				<li class="menu__btn"><a href="../uploader/"><i class="fa fa-lg fa-picture-o"></i> 画像投稿</a></li>
				<li class="menu__btn"><a href="../basic/"><i class="fa fa-lg fa-cog"></i> 基本情報登録</a></li>
				<li class="menu__btn menu__btn--active"><a href="./"><i class="fa fa-lg fa-bookmark-o"></i> カテゴリ登録</a></li>
			</ul>
		</nav>
		<main class="m-main m-main--block01">
			<form action="" method="post" class="pr">
				<h2 class="m-ttl02">カテゴリ</h2>
				<ul class="m-table-filelist">
					<li class="o-cfx">
						<div class="table-filelist__head table-filelist__grab"></div>
						<div class="table-filelist__head table-filelist__prefix"><p>カテゴリID</p></div>
						<div class="table-filelist__head table-filelist__name"><p>カテゴリ名</p></div>
						<div class="table-filelist__head table-filelist__url"><p>URL</p></div>
						<div class="table-filelist__head table-filelist__ckbox"></div>
					</li>
				</ul>
				<ul id="sortable" class="m-table-filelist">
<?php
$length = count($csv_category);
for($i=0; $i < $length; $i++) {
?>
					<li class="o-cfx" data-catid="<?php echo $csv_category[$i][0];?>">
						<div class="table-filelist__grab"><i class="fa fa-bars"></i></div>
						<div class="table-filelist__prefix">
							<input type="text" name="category[<?php echo $i; ?>][prefix]" value="<?php echo $csv_category[$i][0];?>">
						</div>
						<div class="table-filelist__name">
							<input type="text" name="category[<?php echo $i; ?>][name]" value="<?php echo $csv_category[$i][1]; ?>">
						</div>
						<div class="table-filelist__url">
							<input type="text" name="category[<?php echo $i; ?>][url]" value="<?php echo $csv_category[$i][2]; ?>">
						</div>
						<div class="table-filelist__ckbox"><a href="#"><i class="fa fa-lg fa-trash-o"></i></a></div>
					</li>
<?php }?>
				</ul>
				<div>
					<p id="addbtn" class="m-btn04">+ カテゴリを追加する</p>
				</div>
				<div class="w-half mtb40 mrla tac">
					<input type="submit" name="post-category" class="m-btn01 btn01--entry btn01--w1" value="登録する">
				</div>
			</form>
			<div class="m-box">
				<p class="box__tit">カテゴリ登録について</p>
				<ul class="box__txt">
					<li>・カテゴリIDの入力は必須です。</li>
					<li>・カテゴリIDが重複した場合はエラーになります。</li>
				</ul>
			</div>
		</main>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../lib/js/vendor/jquery-2.0.3.min.js"><\/script>')</script>
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script src="../../lib/js/admin/function.js"></script>
</body>
</html>