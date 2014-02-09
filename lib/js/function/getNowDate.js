/**
 * getNowDate
 * @Summary: 今日の年月日時を、利用しやすい形式に整形した後、obj形式で返却
 * @Version: 0.2
 * @UpDate: 2013/05/23
 * @Return object
 *    dateObj = 2013年1月1日1時1分1秒
 *		-- dateObj.y = 2013
 *		-- dateObj.y2 = 13
 *		-- dateObj.m = 1
 *		-- dateObj.m2 = 01
 *		-- dateObj.d = 1
 *		-- dateObj.d2 = 01
 *		-- dateObj.h = 1
 *		-- dateObj.h2 = 01
 *		-- dateObj.min = 1
 *		-- dateObj.min2 = 01
 *		-- dateObj.sec = 1
 *		-- dateObj.sec2 = 01
 * @example
 *		var dateObj = rjs.getNowDate();
 *		console.log(dateObj.y);
 */
this.rjs = this.rjs || {};
(function($){
	rjs.getNowDate = function(){
		var date = new Date();
		var year = date.getFullYear();
		var month = date.getMonth() + 1;
		var day = date.getDate();
		var hours = date.getHours();
		var minutes = date.getMinutes();
		var seconds = date.getSeconds();
		var year2,month2,day2,hours2,minutes2,seconds2;
		year = year.toString();
		year2 = year.substr(2,2);
		if(month < 10){ month2 = '0'+month; } else{ month2 = month; }
		if(day < 10){ day2 = '0'+day; } else{ day2 = day; }
		if(hours < 10){ hours2 = '0'+hours; } else{ hours2 = hours; }
		if(minutes < 10){ minutes2 = '0'+minutes; } else{ minutes2 = minutes; }
		if(seconds < 10){ seconds2 = '0'+seconds; } else{ seconds2 = seconds; }
		var dateObj ={
			y:year,
			y2:year2,
			m:month,
			m2:month2,
			d:day,
			d2:day2,
			h:hours,
			h2:hours2,
			min:minutes,
			min2:minutes2,
			sec:seconds,
			sec2:seconds2
		}
		return dateObj;
	}
}) (jQuery);
