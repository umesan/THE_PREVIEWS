<?php
/**
 * /api/make_thumbnail.php
 * サムネイルの生成
 * @Update : 2014/01/20
 * @exsample : getdirtree($dir);
 * 【参考サイト】
 * データベース不要の簡単サムネイル作成PHPフォトギャラリー
 * http://colors7.net/nondb-php-gallery/
 *
 * PNGとGIFの透過情報を保ったまま画像をリサイズ
 * http://b.n-at.me/archives/132
　*/

function make_thumb($src,$dest,$desired_width,$extension) {

	// ファイルから画像の作成。画像のタイプによって関数が違います
	if($extension == 'jpg'){
		$source_image = imagecreatefromjpeg($src);
	}elseif($extension == 'png'){
		$source_image = imagecreatefrompng($src);
	}elseif($extension == 'gif'){
		$source_image = imagecreatefromgif($src);
	}

	$width = imagesx($source_image);
	$height = imagesy($source_image);
	$desired_height = floor($height*($desired_width/$width));

	// 新規画像の作成
	$virtual_image = imagecreatetruecolor($desired_width,$desired_height);

	// GIFとPNGの透過情報を保つ
	if($extension == 'png' || $extension == 'gif'){

		//もとの画像から透過色のIDを取得
		$index = imagecolortransparent($source_image);

		if($index >= 0) {

			//IDがあった場合にimagecolorsforindexでそのIDの色を取得
			$color = imagecolorsforindex($source_image, $index);
			//imagecolorallocateでその色を作成
			$alpha = imagecolorallocate($virtual_image, $color['red'], $color['green'], $color['blue']);
			//imagefillで新規画像を塗りつぶし
			imagefill($virtual_image, 0, 0, $alpha);
			//imagecolortransparentで新規画像の透過色を先ほど作成した色に設定
			imagecolortransparent($virtual_image, $alpha);

		} else if($extension == 'png') {

			// 透過色が無かった場合且つPNGの場合にはまずimagealphablendingでブレンドモードを解除
			imagealphablending($virtual_image, false);
			//imagecolorallocatealphaで完全に透明な色を作る
			$color = imagecolorallocatealpha($virtual_image, 0, 0, 0, 127);
			//imagefillで新規画像を塗りつぶし
			imagefill($virtual_image, 0, 0, $color);
			//imagesavealphaで完全なアルファチャネル情報を保存するフラグを設定
			imagesavealpha($virtual_image, true);
		}

	}

	// 指定した画像の矩形部分を 別の画像へコピー
	imagecopyresized($virtual_image,$source_image,0,0,0,0,$desired_width,$desired_height,$width,$height);

	// ファイルから画像の作成。画像のタイプによって関数が違います
	if($extension == 'jpg'){
		imagejpeg($virtual_image,$dest,98);
	}elseif($extension == 'png'){
		imagepng($virtual_image,$dest);
	}elseif($extension == 'gif'){
		imagegif($virtual_image,$dest);
	}

	// メモリ上の画像データを破棄
	imagedestroy($source_image);
	imagedestroy($virtual_image);

}

function get_files($images_dir,$exts = array('png','jpg','gif')) {
	$files = array();
	if($handle = opendir($images_dir)) {
		while(false !== ($file = readdir($handle))) {
			$extension = strtolower(get_file_extension($file));
			if($extension && in_array($extension,$exts)) {
				$files[] = $file;
			}
		}
		closedir($handle);
	}
	return $files;
}

function get_file_extension($file_name) {
	return substr(strrchr($file_name,'.'),1);
}