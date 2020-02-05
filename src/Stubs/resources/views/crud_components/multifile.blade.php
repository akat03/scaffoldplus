
{{--

/**
 * コンポーネント multifile
 *
 * 動作概要
 *

 * 1. DB カラム名が test の場合、次のHTML要素
	    <div id="test__drop_area" class="dropzone_image_drop_area">ここにアップロードファイルをドロップ</div>
	    <div id="test__preview_area" class="dropzone-custom"></div>
	    <input type="hidden" name="test__sortable_order">

 * を自動生成します。

 * 2. フォームを送信するときに自動的に、hidden要素で dropzone_files を追加します。　カラム名が test の場合、フォーマットは以下の通り
	  "test__dropzone_files" => array:3 [▼
		    0 => "phpzDCH2p.png\t01_d02.png\t/home/kusanagi/sowas.flatmemo.net/DocumentRoot/cms/assets/component_multifile/../../../storage/app/tmp/phpzDCH2p.png"
		    1 => "php966Asq.png\t01_d01.png\t/home/kusanagi/sowas.flatmemo.net/DocumentRoot/cms/assets/component_multifile/../../../storage/app/tmp/php966Asq.png"
		    2 => "php9gQpgw.png\t01_w.png\t/home/kusanagi/sowas.flatmemo.net/DocumentRoot/cms/assets/component_multifile/../../../storage/app/tmp/php9gQpgw.png"
	  ]
 *
 */

コンポーネント multifile （次の値が利用可能です）

	$column_name:{{$column_name}} <br>
	$column_value:{{$column_value}} <br>
	$class:{{$class}} <br>

--}}


<!-- ===== component_multifile =====  -->

{{-- html --}}
    <div id="{{$column_name}}__drop_area" class="dropzone_image_drop_area">ここにアップロードファイルをドロップ</div>
    <div id="{{$column_name}}__preview_area" class="dropzone-custom"></div>
{{-- / html --}}


{{-- すでに登録済みの画像一覧 --}}
@php
	$value_array = json_decode($column_value,true);
	// dump( $column_value );
	dump( $value_array );
@endphp

	<ul id="sample">
	@if ($value_array)
		@foreach ($value_array as $va)
			<li data-value="{{$loop->index}}">
				<img src="{{ \Storage::disk($va['disk'])->url("{$va['dir']}/{$va['file_name']}") }}">
			</li>
		@endforeach
	@endif
	</ul>



{{-- Script --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<script>
$(function() {
	$('#sample').sortable({
		// revert: true ,
		opacity: 0.5 ,
		update: function(event, ui) {
	        console.log( $(this).sortable("toArray", { attribute: 'data-value' }) );
	        sortable_order = $(this).sortable("toArray", { attribute: 'data-value' });
	        console.log( sortable_order );
	        for (var i = 0; i < sortable_order.length; i++) {
				form_name = 'FM';
			    make_hidden('{{$column_name}}sortable_order[]', sortable_order[i], form_name);
	        }


		}
	});
});
</script>

<script type="text/javascript">

$('#{{$column_name}}__drop_area').dropzone({
	url                          : '{{asset('/assets/component_multifile/dropzone_upload.php')}}' ,
	paramName                    : 'file',
	maxFilesize                  : 999 , //MB
	addRemoveLinks               : true ,
	previewsContainer            : '#{{$column_name}}__preview_area' ,
	thumbnailWidth               : 50 , //px
	thumbnailHeight              : 50 , //px
	dictRemoveFile               :'[×]' ,
	dictCancelUpload             :'キャンセル' ,
	dictCancelUploadConfirmation : 'アップロードをキャンセルします。よろしいですか？' ,
		uploadprogress:function(file, progress, size){
		file.previewElement.querySelector("[data-dz-uploadprogress]").style.width = "" + progress + "%";
	},
	success:function(file, rt, xml){
		// それぞれのファイルアップロードが完了した時の処理
		form_name = 'FM';
        make_hidden('{{$column_name}}__dropzone_files[]', file.xhr.response, form_name);
		file.previewElement.classList.add("dz-success");
		$(file.previewElement).find('.dz-success-mark').show();
	},
	processing: function(){
		// ファイルアップロード中の処理（※要追加）
	} ,
	queuecomplete: function(){
		// 全てのファイルアップロードが完了した時の処理（※要追加）
	} ,
	dragover: function( arg ){
		$('#' + arg.srcElement.id).addClass('dragover');
	} ,
	dragleave: function( arg ){
		$('#' + arg.srcElement.id).removeClass('dragover');
	} ,
	drop: function( arg ){
		$('#' + arg.srcElement.id).removeClass('dragover');
	} ,
	error:function(file, _error_msg){
		var ref;
		(ref = file.previewElement) != null ? ref.parentNode.removeChild(file.previewElement) : void 0;
	},
	removedfile:function(file){
		delete_hidden('{{$column_name}}__dropzone_files[]',file.xhr.response);
		var ref;
		(ref = file.previewElement) != null ? ref.parentNode.removeChild(file.previewElement) : void 0;
	} ,
	canceled:function(arg){
	} ,
	previewTemplate : "\
	<div class=\"dz-preview dz-file-preview\">\n\
	  <div class=\"dz-details\">\n\
	    <div class=\"clearfix\">\n\
	      <img class=\"dz-thumbnail\" data-dz-thumbnail>\n\
	      <div class=\"dz-success-mark\" style=\"display:none;\"><i class=\"fa fa-2x fa-check-circle\"></i></div>\n\
	    </div>\n\
	    <div class=\"dz-progress\"><span class=\"dz-upload\" data-dz-uploadprogress></span></div>\n\
	    <div>\n\
	      <div class=\"dz-filename\"><span data-dz-name></span></div>\n\
	      <div class=\"dz-my-separator\"> / </div>\n\
	      <div class=\"dz-size\" data-dz-size></div>\n\
	      <div class=\"dz-error-message\"><span data-dz-errormessage></span></div>\n\
	    </div>\n\
	  </div>\n\
	</div>\n\
	"
});



/**
 * make_hidden : hiddenを作成する : Version 1.2
 */
function make_hidden(name, value, formname) {
console.log( name );
console.log( value );

    var q = document.createElement('input');
    q.type = 'hidden';
    q.name = name;
    q.value = value;
    if (formname) {
    	if ( document.forms[formname] == undefined ){
    		console.error( "ERROR(dropzone_config.js): form " + formname + " is not exists." );
    	}
    	document.forms[formname].appendChild(q);
    } else {
    	document.forms[0].appendChild(q);
    }
}



/**
 * delete_hidden : hiddenを削除する : Version 1.1
 */
function delete_hidden(name, value, formname) {
    var dom_obj_array = window.document.getElementsByName(name);
    for (var i = 0; i < dom_obj_array.length; i++) {
        if (dom_obj_array[i].value === value) {
            element = dom_obj_array[i];
            element.parentNode.removeChild(element);
        }
    }
}
</script>



<style type="text/css">
.dropzone_image_drop_area {
    position: relative;

    height: 120px;
    padding: 35px 20px;

    width: 100%;
    color: #666;
    line-height: 1.6;
    background-color: #f9f9f9;
    border: 4px dotted #bbb;
    display: block;
    text-align:center;
    vertical-align:middle;
}
.dropzone_image_drop_area_mini {
    position: relative;

    margin: 0 0 0 0;
    padding: 5px 10px;

    min-width: 80px;
    height: 100%;

    color: #666;
    line-height: 1.6;
    background-color: #f9f9f9;
    border: 4px dotted #bbb;
    display: block;
    text-align:center;
    vertical-align:middle;
    font-size: 10px;
}
.dragover {
    color: #fff !important;
    background-color: #23A4DE !important;
    border: 4px dotted #d6d6d6 !important;
}
.dz-preview {
    margin: 5px 0;
    padding: 10px;
    float: left;
    background-color: #F5F5F5;
    border-radius: 5px;
    width: 100%;
    border: 1px solid #ccc;
}
.dz-details {
    padding: 0;
}
.dz-filename , .dz-size, .dz-my-separator , .dz-remove {
    font: 11px "Lucida Grande", Lucida, Verdana, sans-serif;
}
.dz-filename , .dz-my-separator, .dz-size , .dz-error-message {
    float: left;
}
.dz-my-separator {
    width: 20px;
    text-align: center;
}
.dz-size {
    margin-top: 1px;
}
.dz-progress {
    width: 300px;
}
.dz-progress .dz-upload {
    display: block;
    border: 5px solid #1E9BFF;
    border-radius: 2px;
}
.dz-remove {
    float: left;
    margin-left: 5px;
}
.dz-thumbnail {
    margin-bottom: 3px;
    max-width: 100%;
}
.fa-check-circle:before {
    color: #93C54B;
}
.dz-thumbnail , .dz-success-mark {
    display: block;
    float: left;
}
.dz-success-mark {
    height: 25px;
    margin-left: 5px;
}
</style>

<!-- ===== / component_multifile =====  -->
