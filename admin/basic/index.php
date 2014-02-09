<?php
/**
 * /admin/basic/index.php
 * 管理画面 基本設定
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

	//サイト名
	$TITLE = '';

	// 保存完了通知
	$COMPLEATE = '';

	// 基本情報 CSV書き込み
	if($_SERVER["REQUEST_METHOD"]=="POST" && !empty ($_POST)){
		if (isset($_POST['post-config'])) {
			$fp = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_SETTING, 'ab');

			// ファイルを排他ロックする
			if(flock($fp, LOCK_EX)){

				// 中身を消す
				ftruncate($fp, 0);

				// 念のため先頭を置く。
				rewind($fp);

				if(!empty ($_POST["title"])){
					$TITLE = $_POST["title"];
					$ins = 'サイト名,'.$TITLE."\n";
					// データをファイルに書き込む
					fwrite($fp, $ins);
				}

				if(!empty ($_POST["head_bg_color"])){
					$HEAD_BG_COLOR = $_POST["head_bg_color"];
					$ins = 'ヘッダ背景色,'.$HEAD_BG_COLOR."\n";
					fwrite($fp, $ins);
				}

				if(!empty ($_POST["head_txt_color"])){
					$HEAD_TXT_COLOR = $_POST["head_txt_color"];
					$ins = 'ヘッダテキスト色,'.$HEAD_TXT_COLOR."\n";
					fwrite($fp, $ins);
				}

				flock($fp, LOCK_UN);
			}
			// ファイルを閉じる
			fclose($fp);

			$COMPLEATE = '<div id="js-alert" class="m-alert alert--compleate alert--fix">保存しました。</div>';

		}

	}

	// 基本情報 CSV読み込み＆設定
	include(PROJECT_ROOT_PATH.'/api/get_config_data.php');

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>管理画面 - 基本情報</title>
<link rel="stylesheet" href="../../lib/css/style.v01.css">
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
				<li class="menu__btn menu__btn--active"><a href="./"><i class="fa fa-lg fa-cog"></i> 基本情報登録</a></li>
				<li class="menu__btn"><a href="../category/"><i class="fa fa-lg fa-bookmark-o"></i> カテゴリ登録</a></li>
			</ul>
		</nav>
		<main class="m-main m-main--block01">
			<form action="" method="post" class="pr">
				<h2 class="m-ttl02">基本情報</h2>
				<div class="m-table01">
					<table>
						<tr>
							<th><p>サイト名</p></th>
							<td><p><input type="text" name="title" value="<?php echo $TITLE; ?>" /></p></td>
						</tr>
					</table>
				</div>
				<div class="m-table01 mt20">
					<table>
						<tr>
							<th><p>ヘッダ背景色</p></th>
							<td><p><input type="text" name="head_bg_color" class="js-color-picker" value="<?php echo $HEAD_BG_COLOR; ?>" /></p></td>
						</tr>
						<tr>
							<th><p>ヘッダテキスト色</p></th>
							<td><p><input type="text" name="head_txt_color" class="js-color-picker" value="<?php echo $HEAD_TXT_COLOR; ?>" /></p></td>
						</tr>
					</table>
				</div>
				<div class="w-half mtb40 mrla tac">
					<input type="submit" name="post-config" class="m-btn01 btn01--entry btn01--w1" value="登録する">
				</div>
			</form>
		</main>
	</div>
</div>

<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="../../lib/js/vendor/jquery-2.0.3.min.js"><\/script>')</script>
<script src="../../lib/js/vendor/jscolor.js"></script>

<script>
(function($){
	$(function(){
		// 完了 アラート
		$('#js-alert').fadeIn(1000,function(){
			$(this).delay(3000).fadeOut(1000,function(){
				$(this).remove();
			});
		});
	});
})(jQuery);
</script>
</body>
</html>