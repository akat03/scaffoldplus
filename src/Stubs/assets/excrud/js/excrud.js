// excrud.js for Laravel
// Copyright (c) 2019 https://econosys-system.com/
// version 0.31

/**
 * ソートします
 *
 * @param   string      base_url
 * @param   string      column
 * @param   string      direction
 *
 */
function sort(base_url, column, direction) {

    var now_url = location.href;

    console.log(base_url, column, direction, now_url);

    // url.min.js
    var u = new Url; // curent document URL will be used
    // console.log( u.query.toString() );
    // u.query.column = 'aiueo';
    // console.log( u.query.toString() );


    if (now_url.match(/\?/)) {
        u.query.column = column;
        u.query.direction = direction;
    } else {
        u.query.column = column;
        u.query.direction = direction;
        // base_url = base_url + '/search'
    }

    // console.log( u.query.toString() );
    var jump_url = base_url + '?' + u.query.toString();
    // console.log( jump_url );

    location.href = jump_url;

}



/**
 * タグを変更します
 *
 * @param   string      base_url
 * @param   string      key			URLパラメータ 「key=value」 で渡る
 * @param   string      value
 *
 */
function changeTag( base_url, key, value) {

    // url.min.js
    var u = new Url; // curent document URL will be used

    // console.log( base_url );
    // console.log( u.query );
    // console.log( u.query.toString() );
    if (key){
    	if ( value == null && u.query[key] ){
    		delete u.query[key];	// value が null の時はパラメーターを削除する
    	}
		else{
		    u.query[key] = value;
		}
    }
    // console.log( u.query );
    var jump_url = base_url + '?' + u.query.toString();

    // console.log( u.query );
    // console.log( jump_url );
    // location.href = jump_url;
}



/**
 * タグを変更します
 *
 * @param   string      base_url
 * @param   string      key			URLパラメータ 「key=value」 で渡る
 * @param   string      value
 *
 */
function changeTagHash( base_url, arg_obj) {

    // url.min.js
    var u = new Url; // curent document URL will be used

    for (var key in arg_obj) {
    	// console.log( key );

    	var value = arg_obj[key];

	    if (key){
	    	if ( value == null && u.query[key] ){
	    		delete u.query[key];	// value が null の時はパラメーターを削除する
	    	}
			else{
			    u.query[key] = value;
			}
	    }
    }

	    var jump_url = base_url + '?' + u.query.toString();
    // console.log( u.query );
    // console.log( jump_url );
    location.href = jump_url;
}



/**
 * データを一括削除します
 */
function dl_delete_jump( base_url )
{
  var arg='';
  $('.dl:checked').each(function(){
    arg += '&dl[]='+$(this).val();
  });
  if (arg){
    if ( confirm('Delete selected rows. OK?') ){
      location.href = base_url + '/dl_delete_submit/?_back_url='+encodeURIComponent(location.href)+arg;
    }
  }
}



/**
 * チェックボックスにチェックがある時のみ一括削除ボタンを表示します
 */
function dl_check()
{
  var check_flag = false;
  $('.dl:checked').each(function(){
    check_flag = true;
  });
  if( check_flag ){
    $('#dl_delete').show();
  }
  else{
    $('#dl_delete').hide();
  }
}







