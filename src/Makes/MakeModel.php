<?php

namespace Akat03\Scaffoldplus\Makes;

use Illuminate\Filesystem\Filesystem;
use Akat03\Scaffoldplus\Commands\ScaffoldMakeCommand;
use Akat03\Scaffoldplus\Migrations\SchemaParser;
use Akat03\Scaffoldplus\Migrations\SyntaxBuilder;

class MakeModel
{
    use MakerTrait;

    protected $scaffoldCommandObj;
    protected $schemaArray = [];


    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;
        $this->getSchemaArray();

        $this->start();
    }


    protected function start()
    {
        $name = $this->scaffoldCommandObj->getObjName('Name');

        // Make: ./app/[MODEL].php
        $path = $this->getPath($name, 'model');
        $stub = $this->files->get(__DIR__ . '/../Stubs/model.stub');
        $this->replaceName($stub)
            ->replaceModelPath($stub)
            ->replaceModelName($stub)
            ->replaceSchemaShow($stub);
        // put if file is not exists.
        if (!$this->files->exists($path)) {
            $this->files->put($path, $stub);
            $this->getSuccessMsg();
        }


        // Make: ./app/CrudTrait.php
        $path = $this->getPath('CrudTrait', 'model');
        $stub = $this->files->get(__DIR__ . '/../Stubs/CrudTrait.stub');
        $this->replaceName($stub)
            ->replaceSchemaShow($stub);
        // put if file is not exists.
        if ($this->files->exists($path)) {
        } else {
            $this->files->put($path, $stub);
            $this->getSuccessMsg();
        }


        // ========== Make: ./app/[MODEL].json
        $crud_format = $this->scaffoldCommandObj->option('crud_format');
        $crud_format = str_replace('"', '', $crud_format);
        $crud_ext = '';

        if ($crud_format == 'yaml') {
            $crud_ext = 'yml';
        } else {
            $crud_ext = 'json';
        }

        $path = './app/Models/' . $this->scaffoldCommandObj->getObjName('Name') . ".{$crud_ext}";
        $stub = '';
        $json = [];

        $json['view_column_name_in_show'] = 1;
        $json['view_column_name_in_edit'] = 1;
        $json['table_title']              = '[' . $this->scaffoldCommandObj->getObjName('Name') . ']';

        $json['view_list_order_column']      = 'id';
        $json['view_list_order_direction']   = 'DESC';

        $json['view_list_order_column_second']      = '';
        $json['view_list_order_direction_second']   = '';

        $json['view_list_order']          = '';
        $json['table_sort_sortable']      = 0;
        $json['table_sort_column']        = '';

        $json['view_list_limit_param']   = [10, 20, 25, 30, 50, 100];
        $json['view_list_limit_default'] =  10;

        $json['view_list_search_columns'] = [];
        foreach ($this->schemaArray as $v) {
            $tmp_hash = [];
            if (@$v['options']['comment']) {
                $json['view_list_search_columns'][$v['name']] = $v['options']['comment'];
                $json['view_list_search_columns'][$v['name']] = preg_replace("{^['\"]}", "", $json['view_list_search_columns'][$v['name']]);
                $json['view_list_search_columns'][$v['name']] = preg_replace("{['\"]$}", "", $json['view_list_search_columns'][$v['name']]);
            } else {
                $json['view_list_search_columns'][$v['name']] = ucfirst($v['name']);
            }
            // array_push( $json['view_list_search_columns'], $tmp_hash);
        }
        $json['view_list_search_all_flag'] = 1;


        // ID
        $replace_model_name = '${{model_name_var_sgl}}->id';
        $this->replaceModelName($replace_model_name);

        $h = [
            "name"                => 'id',
            "view_list_title"     => ucfirst('id'),
            "comment"             => 'ID',
            "default"             => null,
            "view_list_flag"      => 1,

            "view_list_format"    => null,
            "view_list_param"     => $replace_model_name,
            "view_list_php"       => null,

            "view_list_css_class" => "",
            "view_list_css_style" => "white-space: nowrap;",
            "view_show_flag"      => 1,
            "view_add_flag"       => 0,
            "view_edit_flag"      => 1,
            "view_delete_flag"    => 1,
            "editable_flag"       => 0,
            "input_type"          => "text",
            "input_css_style"     => "",

        ];
        $json['table_desc']['id'] = $h;


        // $stub_parts = $this->files->get(__DIR__ . '/../Stubs/ModelJson.stub');
        foreach ($this->schemaArray as $v) {
            $view_list_title = ucfirst($v['name']);
            if (@$v['options']['comment']) {
                $view_list_title = $v['options']['comment'];
                // 先頭と最後の ''  ""  を削除
                $view_list_title = preg_replace("{^['\"]}", "", $view_list_title);
                $view_list_title = preg_replace("{['\"]$}", "", $view_list_title);
            }
            // dump($view_list_title);
            $h = [
                "name"            => $v['name'],
                "view_list_title" => $view_list_title,
                "comment"         => $view_list_title,
                "default"         => null,
                "view_list_flag"  => 1,
                "view_list_css_class" => null,
                "view_list_css_style" => null,
                "view_list_php"       => null,
                "view_show_flag"  => 1,
                "view_add_flag"   => 1,
                "view_edit_flag"  => 1,
                "view_delete_flag" => 1,
                "editable_flag"   => 1,
                "input_type"      => "text",
                "input_css_style" => "",
            ];
            if ($v['type'] == 'text') {
                $h['input_type'] = 'textarea';
            }
            $json['table_desc'][$v['name']] = $h;
        }

        // created_at
        $h = [
            "name"            => 'created_at',
            "view_list_title" => 'created_at',
            "comment"         => 'created_at',
            "default"         => null,
            "view_list_flag"  => 0,
            "view_show_flag"  => 1,
            "view_add_flag"   => 0,
            "view_edit_flag"  => 0,
            "view_delete_flag" => 0,
            "editable_flag"   => 0,
            "input_type"      => "text"
        ];
        $json['table_desc']['created_at'] = $h;

        // updated_at
        $h = [
            "name"            => 'updated_at',
            "view_list_title" => 'updated_at',
            "comment"         => 'updated_at',
            "default"         => null,
            "view_list_flag"  => 0,
            "view_show_flag"  => 1,
            "view_add_flag"   => 0,
            "view_edit_flag"  => 0,
            "view_delete_flag" => 0,
            "editable_flag"   => 0,
            "input_type"      => "text"
        ];
        $json['table_desc']['updated_at'] = $h;


        if ($crud_format == 'yaml') {
            $stub = \Symfony\Component\Yaml\Yaml::dump($json, 99);
            $usage = <<< 'DOC_END'
#  ===== hasMany Relation
#
# sortable_flag               : 0
# relation                    : hasMany
# relation_model              : App\Manager
# relation_pivot_model        : App\PatientManager
# relation_method             : managers
#
# relation_id_column_name     : id
# relation_view_column_name   : name
#
# relation_view_show_param    : "{$relation_model['name']}　　(ID:{$relation_model['id']})"
# relation_view_show_separator: "<br>"
# relation_view_edit_param    : "{$relation_model['name']}　　(ID:{$relation_model['id']})"


# ===== ＜input type="text"＞にスタイルをつける
# input_type     : text
# input_css_style: 'width: 60px;'


# ===== ＜SELECT＞ を 別モデルから自動生成　「モデル名:input_values_model」「モデルのidカラム名：input_values_model__id_column」「モデルの表示カラム名：input_values_model__name_column」
# input_type                     : select
# input_values_model             : \App\MtBranch
# input_values_model__scope      : inActive
# input_values_model__id_column  : id
# input_values_model__name_column: branch_name


# ===== ＜SELECT＞ を 直接生成
# input_type        : select
# input_values_array: {'': 選択してください , day: 日 , week: 週 , month: 月}
# input_css_class   : form-control
# input_css_style   : width:160px;


# ===== ＜SELECT＞ を 直接生成（PHPによる値設定）
# input_type        : select
# input_values_php  : |
#     $values = [
#         '' => '選択してください' ,
#     ];
#     $dt_start = new \Carbon\Carbon('2009-01-01');
#     $dt_end   = new \Carbon\Carbon();
#     for ($i=0; $i<100; $i++){
#         if ( $dt_start > $dt_end ){ break; }
#         $year = $dt_start->format("Y");
#         $values[$year] = $year;
#         $dt_start->addYearNoOverflow();
#     }
#     return $values;


# ===== ＜checkbox＞ の　値を指定
# input_type: checkbox
# input_checked_value: 1
# input_label: ◯◯◯◯◯する


# ===== Relation (hasMany) (morphMany)
#editable_flag               : 0
#sortable_flag               : 0
#relation                    : hasMany
#relation_model              : App\Patient
#relation_pivot_model        : App\PatientPractitioner
#relation_method             : patients
#relation_id_column_name     : id
#relation_view_column_name   : name
#relation_view_list_php      : echo $catalog->items->count() . "件";
#relation_view_show_param    : "{$relation_model['name']}　　(ID:{$relation_model['id']})"
#relation_view_show_separator: "<br>"
#relation_view_edit_param    : "{$relation_model['name']}　　(ID:{$relation_model['id']})"


# ===== table sortable
# table_sort_sortable: 1
# view_list_order_column: sort_no
# view_list_order_direction: ASC


# ===== 新規登録ボタンを表示するかどうか？ 1:表示する　0:表示しない　（何も指定しないときは 表示する）
view_list_add_buttons_flag: 1

# ===== 一覧リストでページネーションを表示するかどうか？ 1:表示する　0:表示しない　（何も指定しないときは 表示する）
view_list_pagination_flag: 1


# ===== 編集ボタンエリア を自由に定義する
# view_list_edit_buttons_include: crud_edit_buttons.XXXXX_edit_buttons


# ===== 項目の区切りヘッダーをつける
table_header: <div>ヘッダー名称未設定 <small></small></div>


# ===== tab
view_list_tab_group:
    all:
        column_name: project_status_id
        value: null
        tab_name: ALL
    tab_2:
        column_name: project_status_id
        value: 1
        tab_name: TAB Group 1
    tab_3:
        column_name: project_status_id
        value: 2
        tab_name: TAB Group 2
    tab_4:
        column_name: project_status_id
        value: 3
        tab_name: TAB Group 3


# ===== write setting in .env
view_column_name_in_show_php: env("SCAFFOLD_PLUS_VIEW_COLUMN_NAME_IN_SHOW");
view_column_name_in_edit_php: env("SCAFFOLD_PLUS_VIEW_COLUMN_NAME_IN_EDIT");



# ==================== component ====================

# ===== component file
# input_type          : component_file
# file_store_disk     : public
# file_store_dir      : item
# file_store_permission: public
# file_store_base_name: item_{id}_01
# view_list_php: echo "<img width='128' src='" . $post->_onefile_url('img_file') . "'>";


# ===== component summernote
# input_type          : component_summernote
# file_store_disk     : public
# file_store_dir      : summernote
# file_store_column   : content_files


# ===== component tinymce (Amazon S3 Disk)
# input_type           : component_tinymce
# file_store_disk      : s3
# file_store_dir       : mydir
# file_store_permission: private
# file_store_url       : /s3/signed_url/mydir/{file}
# file_store_column    : content_files


# ===== component tinymce (Local Disk)
input_type           : component_tinymce
file_store_disk      : local
file_store_dir       : event__common_tiny_mce_files
file_store_permission: private
file_store_url       : /cms/admin/file/showlocalfile/?path=event__common_tiny_mce_files/{file}
file_store_column    : common_tiny_mce_files
# https://amita.numd.me/cms/admin/file/showlocalfile/?path=event__common_tiny_mce_files/202103081241__365188157.png


# ===== component calendar（カレンダー）
# input_type          : component_calendar


# ===== component datetimepicker（カレンダー + 時刻）
# input_type          : component_datetimepicker


# ===== component_fileuploader
# input_type          : component_fileuploader
# file_store_disk     : public
# file_store_dir      : img_files_name
# component_option    : 'data-fileuploader-limit="1"'

# file_showlocalfile_url: /admin/file/showlocalfile/?path=

#view_list_php: echo "<img width='48' src='" . $item->_file_url('img_files_name',0) . "'>" . "<br><small>画像 " . $organizer->_file_count('img_files_name') . '</small>';





DOC_END;

            $stub = $usage . "\n" . $stub;
        } elseif ($crud_format == 'json') {
            $stub = json_encode($json, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        }


        if ($this->files->exists($path)) {
            // if ($this->scaffoldCommandObj->confirm($path . ' already exists! Do you wish to overwrite? [yes|no]')) {
            //     $this->files->put($path, $stub);
            //     $this->getSuccessMsg();
            // }
        } else {
            $this->files->put($path, $stub);
            $this->getSuccessMsg();
        }
    }



    protected function getSuccessMsg()
    {
        $this->scaffoldCommandObj->info('Seed created successfully.');
    }


    protected function getSchemaArray()
    {
        if ($this->scaffoldCommandObj->option('schema') != null) {
            if ($schema = $this->scaffoldCommandObj->option('schema')) {
                $this->schemaArray = (new SchemaParser)->parse($schema);
            }
        }
    }


    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceName(&$stub)
    {
        $stub = str_replace('{{Class}}', $this->scaffoldCommandObj->getObjName('Names'), $stub);
        $stub = str_replace('{{class}}', $this->scaffoldCommandObj->getObjName('names'), $stub);
        $stub = str_replace('{{classSingle}}', $this->scaffoldCommandObj->getObjName('name'), $stub);

        $prefix = $this->scaffoldCommandObj->option('prefix');
        $prefix = str_replace('"', '', $prefix);


        if ($prefix != null) {
            $stub = str_replace('{{prefix}}', $prefix . '.', $stub);
        } else {
            $stub = str_replace('{{prefix}}', '', $stub);
        }

        return $this;
    }

    /**
     * Replace the schema for the show.stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceSchemaShow(&$stub)
    {
        // Create view index content fields
        $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-show-content');
        $stub = str_replace('{{content_fields}}', $schema, $stub);

        return $this;
    }

    /**
     * Renomeia o endereço do Model para o controller
     *
     * @param $stub
     * @return $this
     */
    private function replaceModelPath(&$stub)
    {

        $model_name = '\\App\\Models\\' . $this->scaffoldCommandObj->getObjName('Name');
        $stub = str_replace('{{model_path}}', $model_name, $stub);

        return $this;
    }


    private function replaceModelName(&$stub)
    {
        $model_name_uc = $this->scaffoldCommandObj->getObjName('Name');
        $model_name = $this->scaffoldCommandObj->getObjName('name');
        $model_names = $this->scaffoldCommandObj->getObjName('names');
        $prefix = $this->scaffoldCommandObj->option('prefix');

        $stub = str_replace('{{model_name_class}}', $model_name_uc, $stub);
        $stub = str_replace('{{model_name_var_sgl}}', $model_name, $stub);
        $stub = str_replace('{{model_name_var}}', $model_names, $stub);

        if ($prefix != null)
            $stub = str_replace('{{prefix}}', $prefix . '.', $stub);
        else
            $stub = str_replace('{{prefix}}', '', $stub);

        return $this;
    }
}
