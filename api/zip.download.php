<?php
/**
 * /api/zip.download.php
 * 複数ファイルをZip化し、ダウンロードする
 * @Update : 2014/01/20
 * 【参考URL】
 * http://www.saison-lab.com/web/php-zip/
 * http://estpolis.com/2012/07/4361.html
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../config.php');

	// GET からimg配列を取得
	$imgArray = array();
	$imgArray = $_GET["img"];

	// File Path
	$zipFilePath = '../data/';

	// File Name
	$zipFileName = 'img.zip';

	// zip.lib.php の ZipArchiveクラスを new
	$zip = new ZipArchive();

	//zipファイルを作成し開く
	$result = $zip->open($zipFilePath.$zipFileName, ZipArchive::CREATE);

	//zipファイルを開くことが成功した場合
	if($result === true){

		// ここでzipファイルに入れるファイルを指定
		// GETから値を取得
		for ($i = 0; $i < count($imgArray); $i++){
			$zip->addFile('../'.CONFIG_UPLOAD_IMG_DIR.$imgArray[$i]);
		}

		//ファイル操作では必ずcloseする
		$zip->close();

		// ストリームに出力
		header('Content-Type: application/zip; name="' . $zipFileName . '"');
		header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
		header('Content-Length: '.filesize($zipFilePath.$zipFileName));
		echo file_get_contents($zipFilePath.$zipFileName);

		// 一時ファイルを削除
		unlink($zipFilePath.$zipFileName);
		exit(0);

	}

?>