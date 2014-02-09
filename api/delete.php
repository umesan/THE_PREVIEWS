<?php
/**
 * /api/delete.php
 * 指定画像ファイルの削除と、指定id行をdb.csvから削除
 * @Update 2014/01/20
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../config.php');

	// ------------------------------------------------------------------------------------------------
	// 0. 指定画像ファイルの削除
	// ------------------------------------------------------------------------------------------------
		if(!empty ($_POST["deletefile"])){
			$deletefiles = $_POST["deletefile"];
			foreach($deletefiles as $dfile){
				if(file_exists('../'.CONFIG_UPLOAD_IMG_DIR.$dfile)){
					unlink('../'.CONFIG_UPLOAD_IMG_DIR.$dfile);
				}
				if(file_exists('../'.CONFIG_UPLOAD_THUMBNAIL_DIR.$dfile)){
					unlink('../'.CONFIG_UPLOAD_THUMBNAIL_DIR.$dfile);
				}
			}
		}

	// ------------------------------------------------------------------------------------------------
	// 1. 指定id行の削除
	// ------------------------------------------------------------------------------------------------
		if(!empty ($_POST["deletefile"])){

			// fgetcsv関数を使用時に日本語が消失する現象を回避するためsetlocaleの文字コードを指定
			setlocale(LC_ALL, 'ja_JP.UTF-8');

			// CSVから読み取ったデータを格納
			$csv_data = array();

			// ファイルを読み取り専用モードで開く
			$fp = fopen('../'.CONFIG_CSV_FILE, 'r');

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
			$editflg = false;

			$deletefiles = $_POST["deletefile"];
			foreach($deletefiles as $dfile){

				$id = str_replace ($CONFIG_UPLOAD_IMG_ALLOW_EXE, "", $dfile);

				// パラメータで指定したidと、CSV内一列目のidが一致した行にデータを書き込み
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

			}

			// データ追加後の配列数を取得
			$data_after_count = count($csv_data);

			// csvファイルにデータの書き込み
			$file = fopen('../'.CONFIG_CSV_FILE,"w+");

			// ファイルを排他ロックする
			if(flock($file, LOCK_EX)){

				// 中身を消す
				ftruncate($file, 0);

				// 念のため先頭を置く。
				rewind($file);

				// 残った配列数だけcsvに書き込み
				for($j=0; $j<$data_after_count; $j++) {
					$outputline = $csv_data[$j][0].",".$csv_data[$j][1].",".$csv_data[$j][2].",".$csv_data[$j][3]."\n";
					fwrite($file,$outputline);
				}

				flock($file, LOCK_UN);

			}
			fclose($file);

			header("Content-Type: text/xml");
			echo '<?xml version="1.0" encoding="UTF-8"?>';
			echo '<data>';
			echo '<status>'.'../'.CONFIG_UPLOAD_IMG_DIR.'</status>';
			echo '</data>';

		}

?>