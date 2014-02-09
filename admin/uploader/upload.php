<?php
/**
 * /admin/uploader/index.php
 * 管理画面 画像投稿画面
 * @Update : 2014/01/20
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../../config.php');

	if(isset($_FILES['upl']) && $_FILES['upl']['error'] == 0){
		$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);
		// 拡張子チェック
		if(!in_array(strtolower('.'.$extension), $CONFIG_UPLOAD_IMG_ALLOW_EXE)){
			echo '{"status":"error"}';
			exit;
		}

		// ファイルアップロード
		if(move_uploaded_file($_FILES['upl']['tmp_name'], PROJECT_ROOT_PATH.'/'.CONFIG_UPLOAD_IMG_DIR.$_FILES['upl']['name'])){
			echo '{"status":"success"}';
			exit;
		}
	}

	echo '{"status":"error"}';
	exit;
?>