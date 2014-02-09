<?php
/**
 * /admin/login/index.php
 * 管理画面 ログイン画面
 * @Update : 2014/01/20
 */

	// 設定ファイル読み込み
	include(dirname(__FILE__).'/../../config.php');

	// セッション開始
	session_start();

	// セッションIDがなければリダイレクトしてTOPへ
	if (isset($_SESSION["USERID"])) {
		header("Location: ../../");
		exit;
	}

	// エラーメッセージ
	$errorMessage = "";

	// ユーザーID
	$viewUserId = "";

	// idの取得
	if (isset($_POST["userid"])) {
		$viewUserId = htmlspecialchars($_POST["userid"], ENT_QUOTES);
	}

	// loginの取得
	if (isset($_POST["login"])) {

		// 認証成功
		if ($_POST["userid"] == ADMIN_ID && $_POST["password"] == ADMIN_PASS) {
			// セッションIDを新規に発行する
			session_regenerate_id(TRUE);
			$_SESSION["USERID"] = $_POST["userid"];
			//echo $_SESSION["USERID"];
			header("Location: ../../");
			exit;
		}
		else {
			$errorMessage = '<div class="alert--error pa10 mb10">ユーザIDあるいはパスワードに誤りがあります。</div>';
		}

	}

	// 基本情報 CSV読み込み＆設定
	include(PROJECT_ROOT_PATH.'/api/get_config_data.php');

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo $TITLE;?></title>
<meta name="viewport" content="width=device-width">
<link rel="stylesheet" href="../../lib/css/style.v01.css">
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css">
<style>
.header__title,.m-ttl01.o-show{ background-color: #<?php echo $HEAD_BG_COLOR;?> !important; }
.header__title a, .header__login, .m-ttl01.o-show{ color: #<?php echo $HEAD_TXT_COLOR;?> !important; }
</style>
</head>
<body>
<header class="m-header m-header--fixed">
	<h1 class="header__title"><a href="../../"><?php echo $TITLE;?></a></h1>
</header>
<div class="m-loginform mt60">
	<form id="loginForm" name="loginForm" action="<?php print($_SERVER['PHP_SELF']) ?>" method="POST">
		<p class="loginform__tit">ログインして管理画面に移動します。</p>
		<?php echo $errorMessage ?>
		<div class="loginform__body">
			<div class="m-lock mrla">
				<i class="fa fa-4x fa-lock"></i>
			</div>
			<p class="lockform__lead">ようこそゲストさん</p>
			<div class="loginform__box">
				<label for="userid">ユーザ名</label><br>
				<input type="text" id="userid" name="userid" value="<?php echo $viewUserId ?>">
			</div>
			<div class="loginform__box">
				<label for="password">パスワード</label><br>
				<input type="password" id="password" name="password" value="">
			</div>
			<div class="loginform__btn">
				<input type="submit" id="login" class="m-btn03 btn03--entry" name="login" value="ログイン">
			</div>
		</div>
	</form>
	<div class="loginform__foot">
		<a href="../../"><i class="fa fa-lg fa-home"></i> TOPへ戻る</a>
	</div>
</div>
</body>
</html>