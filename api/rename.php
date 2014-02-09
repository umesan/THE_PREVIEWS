<?php
/**
 * rename.php
 * 画像ファイルをリネーム
 * @Update : 2014/01/20
 * @exsample : ./api/rename.php?before=b.jpg&after=a.jpg
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../config.php');

	// ファイルリネーム
	if(!empty ($_POST["renamefiles"])){
		$renamefiles = array();
		$renamefiles = $_POST["renamefiles"];

		foreach($renamefiles as $dfile){

			// オリジナル画像
			if(file_exists(PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_IMG_DIR.$dfile[renameBefore])){
				rename(PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_IMG_DIR.$dfile[renameBefore], PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_IMG_DIR.$dfile[renameAfter]);
			}

			// サムネイル画像
			if(file_exists(PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_THUMBNAIL_DIR.$dfile[renameBefore])){
				rename(PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_THUMBNAIL_DIR.$dfile[renameBefore] , PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_THUMBNAIL_DIR.$dfile[renameAfter]);
			}

		}

		// fgetcsv関数を使用時に日本語が消失する現象を回避するためsetlocaleの文字コードを指定
		setlocale(LC_ALL, 'ja_JP.UTF-8');

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
		foreach($renamefiles as $dfile){

			$id = str_replace ($CONFIG_UPLOAD_IMG_ALLOW_EXE, "", $dfile[renameBefore]);
			$id_after = str_replace ($CONFIG_UPLOAD_IMG_ALLOW_EXE, "", $dfile[renameAfter]);

			// パラメータで指定したidと、CSV内一列目のidが一致した行にデータを書き込み
			for($i=0; $i < $data_count; $i++) {

				if($csv_data[$i][0] == $id){

					// 一致した行に書き込み
					$csv_data[$i][0] = $id_after;

					break;
				}
			}

		}


		// csvファイルにデータの書き込み
		$file = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_FILE,"w+");
		// flock($file, LOCK_EX);

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


		header("Content-Type: text/xml");
		echo '<?xml version="1.0" encoding="UTF-8"?>';
		echo '<data>';

		foreach($renamefiles as $dfile){
			echo '<rename>';
			echo '<before>'.$dfile[renameBefore].'</before>';
			echo '<after>'.$dfile[renameAfter].'</after>';
			echo '</rename>';
		}

		echo '</data>';
	}

?>