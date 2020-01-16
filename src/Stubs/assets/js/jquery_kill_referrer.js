// jQuery.kill_referrer.js
// copyright (c) econosys system  http://econosys-system.com/
// Version 1.00
// Version 1.01	リンク先がjavascriptの場合は何もしないように
// Version 1.02	リンク先が http:// 以外の場合は何もしないように
// Version 1.03	for IEでtargetを考慮
// Version 1.10	同じドメインには適応しないよう変更
// Version 1.11	シンタックス変更
// Version 1.12	別ドメインは必ず新規ウィンドウで開く


// Initialization
jQuery.kill_referrer = {
	init: function() {
		for (var module in jQuery.kill_referrer) {
			if (jQuery.kill_referrer[module].init)
				jQuery.kill_referrer[module].init();
		}
	}
};


jQuery(function(){
	jQuery.kill_referrer.init();
});


// rewrite
jQuery.kill_referrer.rewrite = {

	init: function() {
		jQuery('a').bind('click', this.click);
	},
	click: function() {
		var url = $(this).attr('href');
        var re = new RegExp( 'https?://'+ document.domain);
console.log( url );

		if (! url.match(/^https?:\/\//) ){ return true; }
		if ( url.match(re) ){ return true; }

		// for IE
		if (navigator.userAgent.indexOf('MSIE',0) != -1){
			var target = $(this).attr('target');
			var blank_flag = 0;
			if (target=='_blank'){
				subwin = window.open('','','location=yes, menubar=yes, toolbar=yes, status=yes, resizable=yes, scrollbars=yes,');
				subwin.document.open();
				subwin.document.write('<meta http-equiv="refresh" content="0;url='+url+'">');
				subwin.document.close();
			}
			else{
				document.open();
				document.write('<meta http-equiv="refresh" content="0;url='+url+'">');
				document.close();
			}
			return false;
		}
		// for Chrome
		else if (navigator.userAgent.indexOf('Chrome',0) != -1){
			subwin = window.open(url,'','location=yes, menubar=yes, toolbar=yes, status=yes, resizable=yes, scrollbars=yes,');
			return false;
		}
        // for firefox
        else if (navigator.userAgent.indexOf('Firefox',0) != -1){
            subwin = window.open(url,'','location=yes, menubar=yes, toolbar=yes, status=yes, resizable=yes, scrollbars=yes,');
            return false;
        }
		// for Safari,Firefox
		else{
			if ( url.match(/data:text\/html;charset=utf-8/) ){}
			else{
				var html = '<html><head><script type="text/javascript"><!--\n'
						+ 'document.write(\'<meta http-equiv="refresh" content="0;url='+url+'">\');'
						+ '// --><'+'/script></head><body></body></html>';
				$(this).attr('href', 'data:text/html;charset=utf-8,'+encodeURIComponent(html))
				.attr('target', '_blank');
			}
		}
	}
};

