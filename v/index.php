<?php
/**
 * /v/index.php
 * 画像単体表示ページ
 * @Update 2013/12/04
 */

	// このファイルからのルートへのパス
	$ROOT_PATH = '../';

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/'.$ROOT_PATH.'config.php');

	// GET値 [img]を取得
	$img = htmlspecialchars($_GET["img"], ENT_QUOTES);

	// 画像の横幅・縦幅を取得
	$imgsrc = $ROOT_PATH.CONFIG_UPLOAD_IMG_DIR.$img;
	list($width,$height) = getimagesize($imgsrc);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title><?php echo $imgsrc; ?></title>
<style>
*{
	margin: 0;
	padding: 0;
	border: none;
}
</style>
</head>
<body>
<div style="width: 100%; height: <?php echo $height; ?>px; background: url(<?php echo $imgsrc; ?>) no-repeat 50% 0;"></div>
</body>
</html>