<?php

namespace Akat03\Scaffoldplus\Makes;

use Illuminate\Filesystem\Filesystem;
use Akat03\Scaffoldplus\Commands\ScaffoldMakeCommand;
use Akat03\Scaffoldplus\Migrations\SchemaParser;
use Akat03\Scaffoldplus\Migrations\SyntaxBuilder;
use Akat03\Scaffoldplus\libs\ScaffoldplusLib;

class MakeApiController
{
    // use AppNamespaceDetectorTrait, MakerTrait;
    use MakerTrait;

    protected $scaffoldCommandObj;
    protected $schema;
    protected $schema_not_null;
    protected $validation_text;

    function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;
        $this->start();
    }


    private function start()
    {
        $addapi = $this->scaffoldCommandObj->option('addapi');
        if ($addapi === 'not generate') {
            $this->scaffoldCommandObj->comment("Skip creating API Controller.( add option --addapi and will create API Controller.) ");
            return false;
        }

        // Cria o nome do arquivo do controller // TweetController

        $name = $this->scaffoldCommandObj->getObjName('Name') . 'ApiController';

        // Verifica se o arquivo existe com o mesmo o nome
        if ($this->files->exists($path = $this->getPath($name))) {
            return $this->scaffoldCommandObj->error($name . ' already exists!');
        }

        // Cria a pasta caso nao exista
        $this->makeDirectory($path);

        // Save Controller
        $this->files->put($path, $this->compileControllerStub());

        // save CrudControllerTrait
        $trait_path = './app/Http/Controllers/CrudControllerTrait.php';
        if (!is_file($trait_path)) {
            $this->files->put($trait_path,  $this->files->get(__DIR__ . '/../Stubs/CrudControllerTrait.stub'));
        }

        // save language files
        $lang_path_ja = ScaffoldplusLib::getLangDir() . '/ja/excrud.php';
        if (!is_file($lang_path_ja)) {
            if (!is_dir(dirname($lang_path_ja))) {
                mkdir(dirname($lang_path_ja));
            }
            $this->files->put($lang_path_ja,  $this->files->get(__DIR__ . '/../Stubs/resources/lang/ja/excrud.php'));
        }

        $this->scaffoldCommandObj->info('API Controller created successfully.');

        //$this->composer->dumpAutoloads();
    }


    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileControllerStub()
    {
        $stub = $this->files->get(__DIR__ . '/../Stubs/apicontroller.stub');

        $this->replaceClassName($stub, "controller")
            ->replaceModelPath($stub)
            ->replaceModelName($stub)
            ->replaceSchema($stub, 'controller');

        return $stub;
    }


    /**
     * Replace the class name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceClassName(&$stub)
    {

        $className = $this->scaffoldCommandObj->getObjName('Name') . 'ApiController';
        $stub = str_replace('{{class}}', $className, $stub);

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

        $model_name = \App::getNamespace() . $this->scaffoldCommandObj->getObjName('Name');
        $stub = str_replace('{{model_path}}', $model_name, $stub);

        return $this;
    }


    private function replaceModelName(&$stub)
    {
        $model_name_uc    = $this->scaffoldCommandObj->getObjName('Name');
        $model_name       = $this->scaffoldCommandObj->getObjName('name');
        $model_names      = $this->scaffoldCommandObj->getObjName('names');
        $prefix           = $this->scaffoldCommandObj->option('prefix');
        $prefix           = str_replace('"', '', $prefix);
        $prefix_namespace = "\\" . ucfirst($prefix);

        // dd($model_name_uc,$model_name,$model_names,$prefix,$prefix_namespace);


        $stub = str_replace('{{model_name_class}}', $model_name_uc, $stub);
        $stub = str_replace('{{model_name_var_sgl}}', $model_name, $stub);
        $stub = str_replace('{{model_name_var}}', $model_names, $stub);

        if ($prefix != null) {
            $stub = str_replace('{{prefix}}', $prefix . '.', $stub);
            $stub = str_replace('{{prefix_namespace}}', $prefix_namespace, $stub);         // 2019_01_29
        } else {
            $stub = str_replace('{{prefix}}', '', $stub);
            $stub = str_replace('{{prefix_namespace}}', '', $stub);
        }

        // crud_format
        $crud_format = $this->scaffoldCommandObj->option('crud_format');
        $crud_format = str_replace('"', '', $crud_format);

        if ($crud_format == null) {
            $crud_format = 'json';
        }
        $stub = str_replace('{{crud_format}}', $crud_format, $stub);


        return $this;
    }


    /**
     * Replace the schema for the stub.
     *
     * @param  string $stub
     * @param string $type
     * @return $this
     */
    protected function replaceSchema(&$stub, $type = 'migration')
    {

        if ($schema = $this->scaffoldCommandObj->option('schema')) {
            $schema = (new SchemaParser)->parse($schema);
        }

        // 2018_08_06 add by econosys system
        // validation ?????
        $this->schema = $schema;

        $this->schema_not_null = array_filter($schema, function ($hash) {
            return (!(@$hash['options']['nullable'] === true));
        });

        $this->createValidation($stub);
        $stub = str_replace('{{validation_text}}', $this->validation_text, $stub);

        // validation ?????



        // Create controllers fields
        $schema = (new SyntaxBuilder)->create($schema, $this->scaffoldCommandObj->getMeta(), 'controller');
        $stub = str_replace('{{model_fields}}', $schema, $stub);


        return $this;
    }


    /**
     * Create validation
     *
     */
    protected function createValidation(&$stub, $type = 'migration')
    {

        $validation_text = <<< 'DOC_END'
protected $validation_column = [

DOC_END;

        // dd($this->schema, $this->schema_not_null);

        foreach ($this->schema as $k => $v) {

            $validation_array = [];
            if (!(@$v['options']['nullable'] == true)) {
                array_push($validation_array, 'required');
                // $validation_text .= <<< DOC_END
                //             '{$v['name']}'  => 'required',

                // DOC_END;
            }

            // 2019_04_21 econosys system
            if (preg_match("{(datetime|date)}i", @$v['type'])) {
                if ((@$v['options']['nullable'] == true)) {
                    array_push($validation_array, 'nullable|date');
                } else {
                    array_push($validation_array, 'date');
                }
            }

            if (preg_match("{integer}i", @$v['type'])) {
                if ((@$v['options']['nullable'] == true)) {
                    array_push($validation_array, 'nullable|integer');
                } else {
                    array_push($validation_array, 'integer');
                }
            }
            if (@$v['type'] == 'decimal' or @$v['type'] == 'double' or @$v['type'] == 'float') {
                if ((@$v['options']['nullable'] == true)) {
                    array_push($validation_array, 'nullable|numeric');
                } else {
                    array_push($validation_array, 'numeric');
                }
            }


            $validation_param = join('|', $validation_array);
            $validation_text .= <<< DOC_END
            '{$v['name']}'  => '{$validation_param}',\n
DOC_END;
        }

        // dd($validation_text);

        $validation_text .= <<< 'DOC_END'
        ];
DOC_END;

        $this->validation_text = $validation_text;
    }
}
