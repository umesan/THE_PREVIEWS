<?php
/**
 * /api/getdirtree.php
 * 指定したディレクトリ以下のファイル一覧を獲得する
 * @Update : 2014/01/20
 * @exsample : getdirtree($dir);
 * @param : ディレクトリを示す文字列
 * @return : ファイル一覧を格納した配列
 */

function getdirtree ( $path ){
	// ディレクトリでなければ false を返す
	if (!is_dir($path)) {
		return false;
	}

	// 戻り値用の配列
	$dir = array();

	if ($handle = opendir($path)) {

		while (false !== ($file = readdir($handle))) {

			// 自分自身と上位階層のディレクトリを除外
			if ('.' == $file || '..' == $file || 'Thumbs.db' == $file || '.svn' == $file) {
				continue;
			}

			if (is_dir($path.$file)) {

				// ディレクトリならば自分自身を呼び出し
				$dir[$file] = $path.$file;

			} elseif (is_file($path.$file)) {
				// ファイルならばパスを格納
				$dir[$file] = $path.$file;
			}
		}
		closedir($handle);
	}
	return $dir;
}
?>