/**
 * 配列から指定した値を削除する
 * @param  array_data 対象の配列
 * @param  delete_val 配列から消したい値
 */
var deleteArrayVal = function(array_data, delete_val){

	(function(v) {
		for (var i = 0; i < this.length; i++){
			if (this[i] == v) this.splice(i--, 1);
		}
	}).call(array_data, delete_val);

}
