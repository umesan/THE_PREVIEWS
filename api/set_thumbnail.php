<?php
/**
 * set_thumbnail.php
 * サムネイルを作成
 * @Update : 2014/01/20
 * @exsample : ./api/set_thumbnail.php
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../config.php');

	// fgetcsv関数を使用時に日本語が消失する現象を回避するためsetlocaleの文字コードを指定
	setlocale(LC_ALL, 'ja_JP.UTF-8');

	// サムネイル作成ライブラリ読み込み
	include(PROJECT_ROOT_PATH.'/api/make_thumbnail.php');

	// CSVデータ格納
	$csv_datalist = array();

	// ファイルを追記モードで開く
	$fp = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_FILE, 'r');

	// ファイルを排他ロックする
	if(flock($fp, LOCK_EX)){
		while( $data = fgetcsv( $fp, 1000, "," ) ) {
			//配列に格納
			array_push($csv_datalist,$data);
		}
		flock($fp, LOCK_UN);
	}
	// ファイルを閉じる
	fclose($fp);
	//print_r($csv_datalist);

	$csv_datalist_count = count($csv_datalist);

	// 追加したいデータ用の配列
	$add_list_data = array();

	// サムネイル作成
	$image_files = get_files(PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_IMG_DIR);
	//print_r($image_files);

	if(count($image_files)) {
		$index = 0;
		foreach($image_files as $index=>$file) {
			$index++;
			$thumbnail_image = PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_THUMBNAIL_DIR.$file;
			//echo $thumbnail_image;

			if(!file_exists($thumbnail_image)) {
				$extension = get_file_extension($thumbnail_image);

				if($extension) {
					make_thumb(PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_IMG_DIR.$file,$thumbnail_image,CONFIG__UPLOAD_THUMBNAIL_WIDTH,$extension);

					// CSVの作成&追記
					$file_after = str_replace($CONFIG_UPLOAD_IMG_ALLOW_EXE, "", $file);

					// CSVに追加したデータ
					array_push($add_list_data,$file_after);

				}

			}
		}

	}


	// データの新規追加があれば
	//print_r($add_list_data);
	$add_list_data_count = count($add_list_data);
	if($add_list_data_count > 0){

		for ($i=0; $i < $add_list_data_count; $i++) {

			// 書き込みフラグを設定
			$add_checkflg = true;

			for ($k=0; $k < $csv_datalist_count; $k++) {
				// CSV内に追加するレコードがすでにあるかチェック
				$word_check = strpos($csv_datalist[$k][0], $add_list_data[$i]);
				if($word_check !== false){
					// 既にあるなら書きこまないフラグを設定
					$add_checkflg = false;
					break;
				}
			}

			// 書き込みフラグを確認
			if($add_checkflg){
				//画像名,★,ステータス,投稿日
				array_push($csv_datalist,array($add_list_data[$i],CONFIG_AUTO_PUBLIC_IMG,'',date("ymd")));
			}

		}
		//print_r($csv_datalist);

		$csv_datalist_count = count($csv_datalist);

		// ファイルを追記モードで開く
		$fp = fopen(PROJECT_ROOT_PATH.'/'.CONFIG_CSV_FILE, 'ab');

		// ファイルを排他ロックする
		if(flock($fp, LOCK_EX)){

			// 中身を消す
			ftruncate($fp, 0);

			// 念のため先頭を置く。
			rewind($fp);

			//１行分のデータをカンマ区切りで結合し、書き出し
			for($h = 0; $h < $csv_datalist_count; $h++){

				$ins = $csv_datalist[$h][0].','.$csv_datalist[$h][1].','.$csv_datalist[$h][2].','.$csv_datalist[$h][3]."\n";

				// データをファイルに書き込む
				fwrite($fp, $ins);
			}
			flock($fp, LOCK_UN);

		}
		// ファイルを閉じる
		fclose($fp);

	}
?>