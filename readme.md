# Scaffold , CRUD Package for Laravel 6

# ● Installation

##1.
```
composer require ..............
```

##2. edit config/app.php の ‘providers’ の一番下に追加

add ‘providers’ in file **config/app.php** .
```
    'providers' => [
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        ..........
        ..........
        Akat03\Scaffoldplus\GeneratorsServiceProvider::class ,   // add this
```


# ● Execute Scaffold



# ● Sample Shell Script


**scaffold_docs.sh**
```
s_controller_name="Doccategory"
s_model_name="docs"
s_migration_name="docs"

# =========================================== change this
# name:text:comment('category name'),
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
php artisan make:scaffoldplus ${s_controller_name} --stubs="./resources/views/scaffolding_stubs" --extends="layout" --crud_format="yaml" --no-interaction --schema="name:text:comment('カテゴリ名'),sort_no:integer:unsigned:default(0):comment('ソート番号')"
# =========================================== change this


# Move to admin directory
# =========================================== change this
# mv  ./resources/views/${s_model_name}/  ./resources/views/admin/${s_model_name}/
# mv  ./app/Http/Controllers/${s_controller_name}Controller.php  ./app/Http/Controllers/Admin/${s_controller_name}Controller.php
# =========================================== change this
```
