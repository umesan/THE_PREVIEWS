<?php
/**
 * /api/delete_category.php
 * 対象カテゴリを削除
 * @Update : 2013/12/30
 * @exsample : /api/delete.php?id=neko2
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../config.php');

	// -------------------------------------------------------------------------------------------------
	// 0. category.csvから対象のデータを削除
	// -------------------------------------------------------------------------------------------------

		// fgetcsv関数を使用時に日本語が消失する現象を回避するためsetlocaleの文字コードを指定
		setlocale(LC_ALL, 'ja_JP.UTF-8');

		// GET からid を取得
		$id = htmlspecialchars($_GET["id"], ENT_QUOTES);

		// CSVから読み取ったデータを格納
		$csv_data = array();

		// ファイルを読み取り専用モードで開く
		$fp = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_CATEGORY, 'r');

		// ファイルを排他ロックする
		if(flock($fp, LOCK_EX)){
			while( $data = fgetcsv( $fp, 1000, "," ) ) {
				//配列に格納
				array_push($csv_data,$data);
			}
			flock($fp, LOCK_UN);
		}
		fclose($fp);
		//print_r($csv_data);

		// 配列数を取得
		$data_count = count($csv_data);
		//echo $data_count;

		// パラメータで指定したidと、CSV内一列目のidが一致した行にデータを書き込み
		$editflg = false;
		for($i=0; $i < $data_count; $i++) {

			if($csv_data[$i][0] == $id){

				$editflg = true;

				// 一致した行を配列から削除
				unset($csv_data[$i]);

				// 削除後に配列をつめる
				$csv_data = array_values($csv_data);
				break;
			}
		}

		// データ追加後の配列数を取得
		$data_after_count = count($csv_data);

		// csvファイルにデータの書き込み
		$file = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_CATEGORY,"w+");

		// ファイルを排他ロックする
		if(flock($file, LOCK_EX)){

			// 中身を消す
			ftruncate($file, 0);

			// 念のため先頭を置く。
			rewind($file);

			for($j=0; $j<$data_after_count; $j++) {

				// 一致した行に書き込み
				$outputline = $csv_data[$j][0].",".$csv_data[$j][1]."\n";

				fwrite($file,$outputline);

			}

			flock($file, LOCK_UN);

		}
		fclose($file);

	// -------------------------------------------------------------------------------------------------
	// 1. 結果をXMLで返却
	// -------------------------------------------------------------------------------------------------

		header("Content-Type: text/xml");
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<data>';
		echo '<status>1</status>';
		echo '</data>';

?>