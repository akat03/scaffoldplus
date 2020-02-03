# Scaffold , CRUD Package for Laravel 6

# ● Installation

## 0. add composer.json
**composer.json**

```
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/akat03/scaffoldplus.git"
        }
    ],
```


## 1. Install packages via Composer

```
composer require akat03/scaffoldplus
```

## 2. edit config/app.php and add 'providers'

add ‘providers’ in file **config/app.php** .

```
    'providers' => [
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        ..........
        ..........
        Akat03\Scaffoldplus\GeneratorsServiceProvider::class ,   // add this
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



# ● Execute Scaffold


## ＊1. Copy assets to your public directory
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
# img_file:text:comment('image file'),
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
php artisan scaffoldplus:create ${s_controller_name} --extends="layout" --crud_format="yaml" --no-interaction --schema="name:string:comment('title name'),content_name:text:comment('content name'),img_file:text:comment('image file'),sort_no:integer:unsigned:default(0):comment('sort number')"
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
        input_css_style: "width:60px;"																# add this
        view_add_param_php: return \App\Post::orderBy('sort_no','DESC')->first()->sort_no + 1;      # add this
```



