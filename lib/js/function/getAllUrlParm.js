/**
 * 全てのパラメータを取得
 * 	@Sumary: パラメータのあるなしに関係なく全てを取得し返却します
 */

/*

URLパラメータルール
sort：リストページ、日付別・カテゴリ別　を判定するパラメータ [ cat,date ]
c：リストページ、絞込み用パラメータ
m：リストページ、サムネ表示切り替えパラメータ [ thumb,thumb_l,list,list_l ]
o：リストページ、開閉状態保持用パラメータ[ 0, 1 ]
row：シングルページ、現在開いているグループのID用パラメータ
p：シングルページ、現在開いているグループのIDの何ページ目を開いているかを保持するパラメータ
col: シングルページ、シングルカラム、マルチカラム表示にするかのパラメータ [ 0,1 ]

 */

var getAllUrlParm = function(){
	var parms = rjs.getHashData();
	var parms_sort = parms['sort'];
	var parms_category = parms['c'];
	var parms_mode = parms['m'];
	var parms_open = parms['o'];
	var parms_row = parms['row'];
	var parms_col = parms['col'];
	var parms_p = parms['p'];
	var parms_group = parms['group'];

	var parms_obj = {
		sort:parms_sort,
		c:parms_category,
		m:parms_mode,
		o:parms_open,
		row:parms_row,
		col:parms_col,
		p:parms_p,
		group:parms_group
	};
	return parms_obj;
};
