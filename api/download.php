<?php
/**
 * /api/download.php
 * 画像ダウンロード用 API
 * @Update : 2013/12/30
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../config.php');

	// GET値 [img]を取得
	$img = htmlspecialchars($_GET["img"], ENT_QUOTES);

	// 画像の横幅・縦幅を取得
	$fpath = PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_IMG_DIR.$img;
	$fname = $img;

	//画像のダウンロード
	header('Content-Type: application/octet-stream');
	header('Content-Length: '.filesize($fpath));
	header('Content-disposition: attachment; filename="'.$fname.'"');
	readfile($fpath);
?>