// console.log( 'vue_sort' );
// console.log( vue_sort__data_loop );

var vm = new Vue({
    delimiters: ["(%","%)"] ,
    el: "#app",
    data: {
        // id: {{ $client->id }} ,
        data_loop : [] ,
        data_loop : vue_sort__data_loop ,
        cancel_data:{
            1 : {id:1, tanto_name:'', mobile_name:'', email_name:'' },
        },

        editableId : 0 ,
        errors : {
            tanto_name: 'EEE' ,
        }
    },
    created: function() {
    	this.data_loop = vue_sort__data_loop; // データ読み込み
    	// console.log( 'vue created' );
    	// console.log( this.data_loop );
        // this.get_ajax('/' + vue_sort__model_name + '/{{ $client->id }}/show_tantos_ajax__index'); // データ読み込み
    } ,
    methods:{
        // draggable onEnd
        onEnd: function(originalEvent){
            console.log( 'draggable onEnd' );
            // console.log( this.data_loop );
            var loop = [];
            for (var i = 0; i < this.data_loop.length; i++) {
                loop.push(this.data_loop[i].id);
            }

            postdata = {
                'id_loop': loop ,
                // 'id'  : this.id ,
            };
            console.log( postdata );

            this.post_ajax(_global_crud_sort_url, postdata);

        } ,
        // get_ajax: function (url, name) {
        //     return axios.get(url)
        //     .then((res) => {
        //         // console.log( res.data );
        //         this.data_loop = res.data;
        //     });
        // } ,
        post_ajax: function (url, postdata) {
            return axios.post(url, postdata)
            .then((res) => {
                console.log( "axios.post を実行しました" );
                console.log( res.data );
                // this.data = res.data;
            }).catch(err => {
                console.log('err:', err);
            });
        } ,
        // delete_ajax: function (url, postdata) {
        //     // postdata._method = 'DELETE';
        //     return axios.post(url, postdata)
        //     .then((res) => {
        //         console.log( "axios.delete を実行しました" );
        //         console.log( res.data );
        //     }).catch(err => {
        //         console.log('err:', err);
        //     });
        // } ,
            // // editable_flag  normal:通常　　edit:編集中　　add:追加追加中
            // make_editable_on: function( id, loop_index ){
            //     // キャンセルデータに保存（オブジェクトの値渡し）
            //     this.cancel_data[id] = JSON.parse(JSON.stringify(this.data_loop[loop_index]));

            //     // sort_no, _editable_flag を削除
            //     delete this.cancel_data[id]['sort_no'];
            //     delete this.cancel_data[id]['_editable_flag'];
            //     // console.log( this.cancel_data );
            //     // 編集可能にする
            //     this.data_loop[loop_index]._editable_flag = 'edit';
            // },
            // make_editable_off: function( loop_index ){
            //     this.data_loop[loop_index]._editable_flag = 'normal';
            // },
            // add_data: function(){
            //     this.data_loop.push({ tanto_name:'名称未設定', _editable_flag:'add' });
            // } ,
            // add_cancel: function( id, loop_index ){
            //     this.data_loop.splice(loop_index,1);
            // } ,
            // add_submit: function( id, loop_index ){
            //     console.log( 'add_submit()' );
            //     postdata = this.data_loop[loop_index];
            //     postdata.id = this.id;
            //     // ajax
            //     this.post_ajax('/tantos/store_ajax_from_client', postdata);
            //     // 編集不可能に戻す
            //     this.data_loop[loop_index]._editable_flag = 'normal';
            // } ,
            // edit_cancel: function( id, loop_index ){
            //     // console.log( this.cancel_data[id] );
            //     // キャンセルデータから戻す
            //     for (var i = 0; i < this.data_loop.length; i++) {
            //         if ( this.data_loop[i]['id'] === id ){
            //             for (var key in this.cancel_data[id]) {
            //                 // console.log( this.data_loop[i][key] + ' : ' + this.cancel_data[id][key] );
            //                 this.data_loop[i][key] = this.cancel_data[id][key];
            //             }
            //         }
            //     }
            //     // 編集不可能に戻す
            //     this.data_loop[loop_index]._editable_flag = 'normal';
            // },
            // edit_submit: function( id, loop_index ){
            //     console.log( 'add_submit()' );
            //     postdata = this.data_loop[loop_index];
            //     postdata.id = this.id;
            //     // ajax
            //     this.post_ajax('/tantos/update_ajax_from_client', postdata);
            //     // 編集不可能に戻す
            //     this.data_loop[loop_index]._editable_flag = 'normal';
            // } ,
            // delete_confirm: function( id, loop_index, data_title ){
            //     if ( confirm('データ「' + data_title + '」を削除します。よろしいですか？') ){
            //         // this.data_loop.splice(loop_index,1);
            //         this.delete_submit( id, loop_index );
            //     }
            // } ,
            // delete_submit: function( id, loop_index ){
            //     console.log( 'delete_submit()' );
            //     postdata = this.data_loop[loop_index];
            //     postdata.id = this.id;
            //     // ajax
            //     this.delete_ajax('/xxxxxxxxxx/destroy_ajax_from_client', postdata);
            //     // vue.js から削除
            //     this.data_loop.splice(loop_index,1);
            // } ,

        }
    });
