# Scaffold , CRUD Package for Laravel 5 / 6 / 7 / 8 / 9

<img src="https://raw.githubusercontent.com/akat03/scaffoldplus/master/readme-posts-list.png" title="Scaffold , CRUD Package for Laravel">


<br>

# ● Installation


## 1. Install package via Composer

```
composer require akat03/scaffoldplus
```



## 2. edit .env file

change database settings in **.env** 

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=test
DB_USERNAME=test
DB_PASSWORD=xxxxxxxxxxxx
```

change **APP_URL** in **.env** 

```
APP_URL=https://your-server.com
```



## 3. clear Laravel and Composer cache 
```
php artisan cache:clear; php artisan config:clear; php artisan route:clear; php artisan view:clear; composer dump-autoload
```

## 4. show scaffolding command
```
php artisan
```

show some commands like below

```
 scaffoldplus
  scaffoldplus:create   Create Migration, Model, Controller, and YAML(json)
  scaffoldplus:publish  Publish /assets/js/ , /assets/css/ files
```



# ● Execute scaffold command


## ＊1. Copy /assets/ files to your public directory
```
php artisan scaffoldplus:publish
```

## ＊2. Create Shell Script

```
vi scaffold_posts.sh
```

**scaffold_posts.sh**

```
s_controller_name="Post"
s_model_name="posts"
s_migration_name="posts"

# =========================================== change this
# name:string:comment('title name'),
# content_name:text:comment('content name'),
# img_file:text:nullable:comment('image file'),
# sort_no:integer:unsigned:default(0):comment('sort number'),
# =========================================== change this


# =========================================== don't change
# s1=$( set_schema | perl -pe 's/\n/,/g'  | perl -pe 's/\s+:/:/g' | perl -pe 's/,$//g' | perl -pe 's/--schema=,/--schema=/g' )
# echo $s1
# Execute Backup
mkdir -p ./___bak/database/migrations/
mkdir -p ./___bak/app/Http/Controllers/
mkdir -p ./___bak/resources/views/
mv ./database/migrations/*create_${s_migration_name}_table.php ./___bak/database/migrations/
mv ./app/Http/Controllers/${s_controller_name}Controller.php  ./___bak/app/Http/Controllers/___`date "+%Y%m%d_%H%M%S"`___${s_controller_name}Controller.php
mv ./resources/views/${s_model_name}/  ./___bak/resources/views/___`date "+%Y%m%d_%H%M%S"`___${s_model_name}/
# =========================================== don't change


# Execute Scaffolding Plus
# =========================================== change this
php artisan scaffoldplus:create ${s_controller_name} --extends="layout" --crud_format="yaml" --no-interaction --schema="name:string:comment('title name'),content_name:text:comment('content name'),img_file:text:nullable:comment('image file'),sort_no:integer:unsigned:default(0):comment('sort number')"
# =========================================== change this


# Move to admin directory
# =========================================== change this
# mv  ./resources/views/${s_model_name}/  ./resources/views/admin/${s_model_name}/
# mv  ./app/Http/Controllers/${s_controller_name}Controller.php  ./app/Http/Controllers/Admin/${s_controller_name}Controller.php
# =========================================== change this
```

## ＊3. Execute Shell Script

```
sh scaffold_posts.sh
```



## ＊4. Execute Migration

```
php artisan migrate
```

## ＊5. Add Routes

**routes/web.php**

```
Route::get("posts/dl_delete_submit", "PostController@dl_delete_submit")->name("posts.dl_delete_submit"); // multiple delete
Route::post("posts/sort_exec_ajax", "PostController@sort_exec_ajax")->name("posts.sort_exec_ajax"); // sort exec
Route::get("posts/sort", "PostController@sort")->name("posts.sort"); // sort view
Route::delete("posts/destroy_ajax", "PostController@destroy_ajax")->name("posts.destroy_ajax"); // ajax delete
Route::get("posts/index_ajax", "PostController@index_ajax")->name("posts.index_ajax"); // ajax index
Route::get("posts/search", "PostController@search")->name("posts.search");
Route::resource("posts","PostController");
```


## ＊6. Edit yaml

**app/Post.yml**

```
vi app/Post.yml 
```

add **view_add_param_php** , **input_css_style** into **sort_no**

```
    sort_no:
        name: sort_no
        view_list_title: 'sort number'
        comment: 'sort number'
        default: null
        view_list_flag: 1
        view_show_flag: 1
        view_add_flag: 1
        view_edit_flag: 1
        view_delete_flag: 1
        editable_flag: 1
        input_type: text
        input_css_style: "width:60px;"	
        view_add_param_php: $p = \App\Post::orderBy('sort_no','DESC')->first(); if($p){return $p->sort_no+1;}else{return 1;}

```

change img_file , like below

```
    img_file:
        name: img_file
        view_list_title: 'image file'
        comment: 'image file'
        default: null
        view_list_flag: 1
        view_show_flag: 1
        view_add_flag: 1
        view_edit_flag: 1
        view_delete_flag: 1
        editable_flag: 1
        input_css_style: ''
        input_type          : component_file
        file_store_disk     : public
        file_store_dir      : img_file
        file_store_base_name: img_file_{id}_01
        view_list_php: echo "<img width='128' src='" . $post->_onefile_url('img_file') . "'>";
```


## ＊7. Create storage link

```
php artisan storage:link
```


## ＊8. Access your laravel app

access 
[http://localhost/posts](http://localhost/posts)



<br>

# ● This package is forked from
[laralib/l5scaffold: Scaffold generator for Laravel 5.x](https://github.com/laralib/l5scaffold)
