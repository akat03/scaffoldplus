@php
	/**
	 * CRUD コンポーネント tinymce
	 *
	 * パラメーターは  @include("crud_components.{$r[1]}" ,[ 'column_name' => $k, 'column_value' => $artnews[$k], 'class' => $class_name, 'crud_config' => $v] )
	 * で渡ってきます。
	 *
	 * @param   string      $column_name
	 * @param   string      $column_value
	 * @param   string      $class_name
	 * @param   array       $crud_config
	 *
	 * @version  0.1
	 *
	 */

	// dump( $column_name, $column_value, $class, $crud_config );
	// dump( "ファイル保存カラム: " . $crud_config->file_store_column );
@endphp

@php
	if ( old($column_name) ) {
		$column_value = old($column_name);
	}
@endphp


<textarea name="{{$column_name}}" class="summernote form-control">{{$column_value}}</textarea>


<script src="https://cdn.tiny.cloud/1/5gn3jroijknxj0wh969p8nksoykxfcrwgcpub78vuqf61055/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
		contextmenu: "bold fontsizeselect forecolor code undo redo codesample removeformat",
 		content_style: "body { font-size:13px; min-height:300px; margin-top:0; } pre{padding:0.5em 1em 0.2em 1em !important; border-radius:3px;}" ,
        apply_source_formatting : true ,
        selector:"[name='{{$column_name}}']" ,
        force_br_newlines : true,
        forced_root_block : '',
        force_p_newlines : false,
        branding: false,
        insert_toolbar: 'quickimage quicktable quicklink',
		plugins: 'textcolor image table codesample autoresize paste autolink imagetools code' ,
	    default_link_target: "_blank" ,		// link
	    link_default_protocol: "https" ,	// link
		relative_urls: false ,				// link
        menubar: false ,
		toolbar: 'undo redo | fontsizeselect | forecolor | codesample | bold italic | blockquote | styleselect | image | table | code',
		fontsize_formats: '10px 12px 14px 18px 24px' ,
        codesample_languages: [
            {text: 'PHP', value: 'php'},
            {text: 'JavaScript', value: 'javascript'},
            {text: 'C#', value: 'csharp'},
            {text: 'Ruby', value: 'ruby'},
            {text: 'Python', value: 'python'},
            {text: 'Java', value: 'java'},
            {text: 'HTML', value: 'markup'},
            {text: 'CSS', value: 'css'},
        ],

		// images_upload_handler: function (blobInfo, success, failure) {
		// 	alert('images_upload_handler');
		// 	var xhr, formData;

		// 	xhr = new XMLHttpRequest();
		// 	xhr.withCredentials = false;
		// 	xhr.open('POST', 'postAcceptor.php');

		// 	xhr.onload = function() {
		// 	  var json;

		// 	  if (xhr.status != 200) {
		// 	    failure('HTTP Error: ' + xhr.status);
		// 	    return;
		// 	  }

		// 	  json = JSON.parse(xhr.responseText);

		// 	  if (!json || typeof json.location != 'string') {
		// 	    failure('Invalid JSON: ' + xhr.responseText);
		// 	    return;
		// 	  }

		// 	  success(json.location);
		// 	};

		// 	formData = new FormData();
		// 	formData.append('file', blobInfo.blob(), blobInfo.filename());

		// 	xhr.send(formData);
		// } ,

		// file_picker_callback: function(callback, value, meta) {
		//   // Provide file and text for the link dialog
		//   if (meta.filetype == 'file') {
		//     callback('mypage.html', {text: 'My text'});
		//   }

		//   // Provide image and alt text for the image dialog
		//   if (meta.filetype == 'image') {
		//     callback('myimage.jpg', {alt: 'My alt text'});
		//   }

		//   // Provide alternative source and posted for the media dialog
		//   if (meta.filetype == 'media') {
		//     callback('movie.mp4', {source2: 'alt.ogg', poster: 'image.jpg'});
		//   }
		// }

		// Paste Image
		paste_as_text: false,
		paste_data_images: true,
		// plugins: 'paste',
		setup: function (editor) {
		    editor.on('init', function(e) {
		    	var content = tinymce.activeEditor.getContent();
		    	console.log( content );


		    });
			editor.on('paste', function (e) {
			  console.log(e.clipboardData.types);
			  // ["text/html", "Files"]

			  console.log(e.clipboardData.getData('text/html').replace(/[\w\/+]{100}[\w\/+]+/g, '...'));
			  // <html>
			  // <body>
			  // <!--StartFragment--><img src="data:image/jpeg;base64,...==" alt="Image result for kenny"/><!--EndFragment-->
			  // </body>
			  // </html>

			  console.log([].map.call(e.clipboardData.items, function (item) {
			    return item.kind + ':' + item.type;
			  }));
			  // ["string:text/html", "file:image/png"]

			  // Since there are `text/html` contents, `hasHtmlOrText(clipboardContent)` will be true, and pasting data image is skipped
			});
		},

    });
</script>


<style type="text/css">
.tox-toolbar__primary {
	transform-origin: top left; transform: scale(0.8); width: 125% !important;
}

.tox .tox-form__group--stretched .tox-textarea {
    font-size: 14px;
}
</style>