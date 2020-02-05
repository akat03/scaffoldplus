@php
	/**
	 * CRUD コンポーネント SummerNote
	 *
	 * パラメーターは  @include("crud_components.{$r[1]}" ,[ 'column_name' => $k, 'column_value' => $artnews[$k], 'class' => $class_name, 'crud_config' => $v] )
	 * で渡ってきます。
	 *
	 * @param   string      $column_name
	 * @param   string      $column_value
	 * @param   string      $class
	 * @param   array       $crud_config
	 *
	 * @version  0.2
	 * @version  0.3 		h2 タグのメニューを変更 , ペースト時のスタイル除去プラグイン停止
	 *
	 */

	// dump( $column_name, $column_value, $class, $crud_config );
	// dump( "ファイル保存カラム: " . $crud_config->file_store_column );
@endphp

@php
	// dump( "ファイル保存カラム: " . $crud_config->file_store_column );

// dd( $crud_config );
	// dump( $column_name,old($column_name) );
@endphp


<textarea name="{{$column_name}}" class="summernote form-control">@if(old($column_name)){{ old($column_name) }}@else{{$column_value}}@endif</textarea>

<!-- summernote_with_option -->

<!-- summernote -->
<link href="{{ url('/') }}/assets/summernote/summernote-bs4.css" rel="stylesheet">
<script src="{{ url('/') }}/assets/summernote/summernote-bs4.js"></script>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="{{ url('/') }}/assets/summernote/lang/summernote-ja-JP.js"></script>

{{-- summernote-bootstrap-grid --}}
{{-- <script src="{{ url('/') }}/assets/summernote/plugins/bootstrap-grid.js"></script> --}}

{{-- summernote-cleaner --}}
{{-- <script src="{{ url('/') }}/assets/summernote/summernote-cleaner.js"></script> --}}

{{-- summernote-add-text-tags.js --}}
{{-- <script src="{{ url('/') }}/assets/summernote/summernote-add-text-tags.js"></script> --}}

{{-- plugin --}}
<script src="{{ url('/') }}/assets/summernote/plugins/summernote-image-attributes.js"></script>
{{-- plugin --}}

{{-- 画像アップロード禁止 --}}
{{--
<style type="text/css">
	.note-editor .note-dropzone { opacity: 0 !important; }
	.note-editable h2 {
		background: #eaeaea;
		padding: 5px 0;
		margin: 0 0 8px 0;
	    font-weight: bold;
	    font-size: 1.2rem;
	}
	.note-editable p {
		border: solid #eaeaea 1px;
	}

</style>
 --}}
 {{-- / 画像アップロード禁止 --}}


<script type="text/javascript">
$(document).ready(function() {

	// カスタムボタン
	// var HelloButton = function (context) {
	// 	var ui = $.summernote.ui;
	// 	// create button
	// 	var button = ui.button({
	// 		contents: '<i class="fa fa-child"/> Hello',
	// 		tooltip: 'hello',
	// 		click: function () {
	// 		// invoke insertText method with 'hello' on editor module.
	// 		context.invoke('editor.insertText', 'hello');
	// 		}
	// 	});
	// 	return button.render();   // return button as jquery object
	// }

/*

$('#summernote').summernote('editor.insertText', 'hello world'));
$('#summernote').summernote('insertText', 'hello world');
$('#summernote').summernote('focus');

if ($('#summernote').summernote('isEmpty')) {
  alert('editor content is empty');
}

・INSERT

var node = document.createElement('div');
node.innerHTML = "\
<div class=\"mu_detail_imgHold\">\n\
<img src=\"dummy.png\" class=\"mu_detail_img\">\n\
<p class=\"mu_detail_caption\">ジャクソンポロック(1912-1956）&nbsp; 無題<br>36.0×45.0cm</p>\n\
</div>\n\
\n\
";
$('#summernote').summernote('insertNode', node);

$('#summernote').summernote('insertText', 'Hello, world');


*/

	// カスタムボタンメソッド_1
	{!! @$crud_config->summernote_option_1_button !!}

	// カスタムボタンメソッド_2
	{!! @$crud_config->summernote_option_2_button !!}


	/*
	var HelloButton = function (context) {
	  var ui = $.summernote.ui;
	  // create button
	  var button = ui.button({
	    contents: '見出し',
	    tooltip: '先頭に見出しを追加します',
	    click: function () {
	    	//
			var flag = context.invoke('isEmpty');

	    	// h2追加
			// var node = document.createElement('h2');
			// node.setAttribute("class", "artnews_details_subttl");
			// context.invoke('editor.insertNode', node);

			// 現在の段落が空ではなかった場合は新規作成する
			if ( flag == false ){
				var v = $( "[name='{{$v->name}}']" ).val();
				console.log( v );
				$( "[name='{{$v->name}}']" ).summernote('code', '<h2>見出しテスト</h2>' + v );
			} else {
		    	// h2追加
				context.invoke('editor.formatH2');
				context.invoke('editor.insertText', "見出し");
				// p 追加
				context.invoke('editor.insertParagraph');
			}
	    }
	  });
	  return button.render();   // return button as jquery object
	}
	*/

    // $( "[name='content']" ).summernote({
    $( "[name='{{$v->name}}']" ).summernote({
		disableDragAndDrop: true ,				// ドロップでの画像アップロード禁止

		width  :'100%' ,
		// height :'15vh' ,
		// height :'100px' ,	// heightを指定しないと自動伸縮になる


		// lang   : "ja-JP" ,
        lang: 'en-US', // Change to your chosen language


	    styleTags: ['h2','p'] ,

	    // plugin ↓
        popover: {
            image: [
                ['custom1', ['imageAttributes']],
                ['custom2', ['src']],
                ['imagesize', ['imageSize100', 'imageSize50', 'imageSize25']],
                ['float', ['floatLeft', 'floatRight', 'floatNone']],
                ['remove', ['removeMedia']]
            ],
        },
        imageAttributes:{
            icon:'<i class="note-icon-pencil"/>',
            removeEmpty:false, // true = remove attributes | false = leave empty if present
            disableUpload: false // true = don't display Upload Options | Display Upload Options
        } ,
	    // plugin ↑

		toolbar: [
			// ['style', ['bold', 'italic', 'underline', 'clear']],
			// ['para', ['ul', 'ol', 'paragraph', 'style']],

			// カスタムツールバー_1
	  		{!! @$crud_config->summernote_option_1_config_toolbar !!}

			// カスタムツールバー_2
	  		{!! @$crud_config->summernote_option_2_config_toolbar !!}
			

		    // ['mybutton', ['hello']]
			['para', ['style']],
		    ['para', ['paragraph']],

			// ['style', ['h2', 'italic', 'underline', 'add-text-tags', 'clear']] ,
		    // ['mybutton', ['h2_btn']] ,
		    // ['style', ['h2']],
			// left, center, right, justify
			// ['para', ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull']] ,
		    // ['font', ['bold', 'italic', 'underline', 'clear']],
		    // ['fontname', ['fontname']],
		    // ['color', ['color']],
		    // ['para', ['ul', 'ol', 'paragraph']],
            // ['para', ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull']],
		    // ['height', ['height']],
		    // ['table', ['table']],
		    // ['insert', ['link', 'picture', 'hr']],

		    ['insert', ['link' @if($crud_config->file_store_column) , 'picture' @endif ]],

		    ['view', ['codeview']],	 // 'fullscreen',

		    // ['help', ['help']] ,
			// ['table', ['bootstrap-grid'] ] ,		// plugin
	        // ['cleaner',['cleaner']] // The Button
		  ] ,

		  buttons: {
		  	// カスタムボタン_1
	  		{!! @$crud_config->summernote_option_1_config_buttons !!}

		  	// カスタムボタン_2
	  		{!! @$crud_config->summernote_option_2_config_buttons !!}

			
		    // hello: HelloButton
		  } ,
		// buttons: {
		// 	h2_btn: HelloButton 	// カスタムボタン
		// } ,

		callbacks: {
			onEnter: function() {
				// <p>タグ追加
				// var node = document.createElement('h2');
				// node.setAttribute("class", "artnews_details_subttl");
				// context.invoke('editor.insertNode', node);
			  // alert('Enter/Return key pressed');
			  // 			context.invoke('editor.pasteHTML', '<p class="artnews_details_txt">test</p>');
			  // return false;
			}
		} ,

	    cleaner:{
	          action: 'both', // both|button|paste 'button' only cleans via toolbar button, 'paste' only clean when pasting content, both does both options.
	          newline: '<br>', // Summernote's default is to use '<p><br></p>'
	          notStyle: 'position:absolute;top:0;left:0;right:0', // Position of Notification
	          icon: '<i class="note-icon">[Your Button]</i>',
	          keepHtml: true, // Remove all Html formats
	          // keepOnlyTags: ['<p>', '<br>', '<ul>', '<li>', '<b>', '<strong>','<i>', '<a>'], // If keepHtml is true, remove all tags except these
	          keepOnlyTags: ['<p>', '<h2>'], // If keepHtml is true, remove all tags except these
	          keepClasses: false, // Remove Classes
	          badTags: ['style', 'script', 'applet', 'embed', 'noframes', 'noscript', 'html'], // Remove full tags with contents
	          badAttributes: ['style', 'start'], // Remove attributes from remaining tags
	          limitChars: false, // 0/false|# 0/false disables option
	          limitDisplay: 'none', // text|html|both|none
	          limitStop: false // true/false
	    }

    });
});
</script>
<!-- / summernote -->


<style type="text/css">
.note-editable img {
    max-width: 100% !important;
}
/* 画像選択時の黒バック */
.note-control-selection{
	display: none !important;
}
</style>



<script type="text/javascript">
$(document).ready(function() {
        $.fn.extend({
            placeCursorAtEnd: function() {
                // Places the cursor at the end of a contenteditable container (should also work for textarea / input)
                if (this.length === 0) {
                    throw new Error("Cannot manipulate an element if there is no element!");
                }
                var el = this[0];
                var range = document.createRange();
                var sel = window.getSelection();
                var childLength = el.childNodes.length;
                if (childLength > 0) {
                    var lastNode = el.childNodes[childLength - 1];
                    var lastNodeChildren = lastNode.childNodes.length;
                    range.setStart(lastNode, lastNodeChildren);
                    range.collapse(true);
                    sel.removeAllRanges();
                    sel.addRange(range);
                }
                return this;
            }
        });

        // alert('ready');
});
</script>