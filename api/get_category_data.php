<?php
/**
 * /api/get_category_data.php
 * カテゴリ情報を取得
 * @Update : 2013/12/30
 */

	// 設定ファイル読み込み
	//include(dirname(__FILE__).'/../config.php');

	$CATEGORY_NAME = '';
	$CATEGORY_PREFIX = '';
	$CATEGORY_URL = '';

	// CSVから読み取ったデータを格納
	$csv_category = array();

	// ファイルを読み取り専用モードで開く
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
	//print_r($csv_category);

	$config_conflist = array();
	for ($i=0,$length = count($csv_category); $i < $length; $i++) {
		$config_conflist[$csv_category[$i][0]] = array($csv_category[$i][1],$csv_category[$i][2]);
	}
	//print_r($config_conflist);

	// カテゴリの設定
	function setCategory($prefix){

		for ($i=0,$length = count($GLOBALS['csv_category']); $i < $length; $i++) {

			$checkword = strpos($prefix, $GLOBALS['csv_category'][$i][0]);
			if($checkword !== false){
				$text = array($GLOBALS['csv_category'][$i][1],$GLOBALS['csv_category'][$i][2]);
				return $text;
			}

			// //prefix
			// $csv_category[$i][0];

			// //name
			// $csv_category[$i][1];

			// //url
			// $csv_category[$i][2];
		}

	}

?>