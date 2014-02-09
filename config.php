<?php
/**
 * THE_PREVIEWS
 * @Update 2014/01/20
 */

//--------------------------------------------------
// 0. 必ず設定を変えてください
//--------------------------------------------------

	// ログイン:ユーザ名
	define('ADMIN_ID','admin');

	// ログイン:パスワード
	define('ADMIN_PASS','hoge');

//--------------------------------------------------
// 1. 任意で設定を変えてください
//--------------------------------------------------

	// 画像を投稿した際の、画像の公開初期設定
	// 0 : 管理画面で星をつけるまでは公開しない
	// 1 : 投稿したタイミングで公開
	define('CONFIG_AUTO_PUBLIC_IMG','0');

//--------------------------------------------------
// 2. 変更可能ですが注意して変更してください
//--------------------------------------------------

	// プロジェクトのルートを定義
	define('PROJECT_ROOT_PATH',dirname(__FILE__));

	// 投稿内容CSVファイルの場所・名前
	define('CONFIG_CSV_FILE','data/db.csv');

	// カテゴリCSVファイルの場所・名前
	define('CONFIG_CSV_CATEGORY','data/category.csv');

	// 設定CSVファイルの場所・名前
	define('CONFIG_CSV_SETTING','data/config.csv');

	// オリジナル画像の配置先
	define('CONFIG_UPLOAD_IMG_DIR','data/img/');

	// オリジナル画像のサムネイル配置先
	define('CONFIG_UPLOAD_THUMBNAIL_DIR','data/thumbnail/');

	// 生成するサムネイルの横幅（サムネイルのサイズを変更した場合、CSSの調整が必要です）
	define('CONFIG__UPLOAD_THUMBNAIL_WIDTH',300);

	// アップロードを許可する拡張子
	$CONFIG_UPLOAD_IMG_ALLOW_EXE = array('.png','.gif','.jpg','.PNG','.GIF','.JPG');

	// エラー非表示
	// ini_set( "display_errors", "Off");

?>