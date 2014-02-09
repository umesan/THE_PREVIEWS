<?php
/**
 * /api/add.php
 * 指定画像ファイルIDに対して★を付加
 * @Update 2014/01/20
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../config.php');

	// GET値 [id][star][txt1][date]を取得
	$id = htmlspecialchars($_GET["id"], ENT_QUOTES);
	$star = htmlspecialchars($_GET["star"], ENT_QUOTES);
	$txt1 = htmlspecialchars($_GET["txt1"], ENT_QUOTES);
	$date = htmlspecialchars($_GET["date"], ENT_QUOTES);

	// CSVから読み取ったデータを格納
	$csv_data = array();

	// ファイルを読み取り専用モードで開く
	$fp = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_FILE, 'r');

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
	for($i=0; $i < $data_count; $i++) {

		if($csv_data[$i][0] == $id){

			// 一致した行に書き込み
			$csv_data[$i][1] = $star;
			$csv_data[$i][2] = $txt1;
			$csv_data[$i][3] = $date;

			// csvファイルにデータの書き込み
			$file = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_FILE,"w+");

			// ファイルを排他ロックする
			if(flock($file, LOCK_EX)){

				// 中身を消す
				ftruncate($file, 0);

				// 念のため先頭を置く。
				rewind($file);

				for($j=0; $j<$data_count; $j++) {

					// 一致した行に書き込み
					$outputline = $csv_data[$j][0].",".$csv_data[$j][1].",".$csv_data[$j][2].",".$csv_data[$j][3]."\n";
					fwrite($file,$outputline);

				}

			}
			fclose($file);

			break;
		}
	}

	header("Content-Type: text/xml");
	echo '<?xml version="1.0" encoding="UTF-8"?>';
	echo '<data>';
	echo '<status>1</status>';
	echo '</data>';

?>