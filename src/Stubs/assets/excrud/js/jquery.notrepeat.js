// ===== global variable =====
var nrpt_sent = 0;
var nrpt_senttime = 0;
var nrpt_show_id = '';

function notrepeat(nrpt_show_id) {
 //    alert('x');
	// return false;

    var nowtime = 0;
    myD = new Date();
    nowtime = myD.getTime() / 1000;
    nowtime = Math.floor(nowtime);
    if (nrpt_sent === 0 || (nowtime - nrpt_senttime) >= 7) {
        // $(window).unload(function() {
        //     nrpt_sent = 0;
        //     if (nrpt_show_id){
	       //      if ($(nrpt_show_id).is(':visible')) { $(nrpt_show_id).hide(); }
        //     }
        // });

        nrpt_sent = 1;
        nrpt_senttime = nowtime;
        setTimeout(function() {
        	if (nrpt_show_id){
	            $(nrpt_show_id).show();
	            alert('x');
        	}
        }, 1);
        return true;
    } else {
        return false;
    }
}

// // ===== jQuery Ready =====
// $(document).ready(function() {
//     $("form.notrepeat").on("submit", function(event) {
//     	alert('fire');
//         notrepeat();
//     });
// });

