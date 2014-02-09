<?php
/**
 * /admin/logout/index.php
 * 管理画面 ログアウト処理
 * @Update : 2014/01/20
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../../config.php');

	// セッション開始
	session_start();

	// セッション変数のクリア
	$_SESSION = array();

	// クッキーの破棄
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
		);
	}
	// セッションクリア
	@session_destroy();
	// リダイレクト
	header("Location: ../../");
?>