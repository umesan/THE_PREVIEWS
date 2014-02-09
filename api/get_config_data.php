<?php
/**
 * /api/get_config_data.php
 * 設定情報を取得
 * @Update : 2013/12/30
 */

	// 設定ファイル読み込み
	//include(dirname(__FILE__).'/../config.php');

	// CSVから読み取ったデータを格納
	$csv_config = array();

	// ファイルを追記モードで開く
	$fp = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_SETTING, 'r');

	// ファイルを排他ロックする
	if(flock($fp, LOCK_EX)){
		while( $data = fgetcsv( $fp, 1000, "," ) ) {
			//配列に格納
			array_push($csv_config,$data);
		}
		flock($fp, LOCK_UN);
	}

	// ファイルを閉じる
	fclose($fp);

	//サイト名
	$TITLE = $csv_config[0][1];
	$HEAD_BG_COLOR = $csv_config[1][1];
	$HEAD_TXT_COLOR = $csv_config[2][1];

?>