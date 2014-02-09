/**
 * getHashData
 * @Summary: URLからパラメータ（ハッシュ）を取得しオブジェクトにして返却
 * @Version: 0.2
 * @UpDate: 2013/05/23
 * @Return object
 * @Example
 * 		// http://test.dev?v=10&dir=test&p=2
 *		var parms = rjs.getHashData();
 *		var parms_v = parms['v']; //10
 *		var parms_p = parms['p']; //2
 */
this.rjs = this.rjs || {};
(function($){
	rjs.getHashData = function(){
		var hashArray = [];
		var q = window.location.search.substring(1);
		var p = q.split('&');
		for (var i=0; i<p.length; i++) {
			var pos = p[i].indexOf('=');
			if (pos > 0) {
				var key = p[i].substring(0,pos);
				var val = p[i].substring(pos+1);
				hashArray[key] = val;
			}
		}
		return hashArray;
	}
}) (jQuery);