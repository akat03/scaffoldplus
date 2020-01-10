<?php
/**
 * Created by PhpStorm.
 * User: fernandobritofl
 * Date: 4/22/15
 * Time: 10:34 PM
 */

namespace Akat03\Scaffoldplus\Makes;


use Illuminate\Filesystem\Filesystem;
use Akat03\Scaffoldplus\Commands\ScaffoldMakeCommand;
use Akat03\Scaffoldplus\Migrations\SchemaParser;

class MakeSeed
{
    use MakerTrait;

    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;

        $this->start();
    }


    protected function start()
    {


        // Get path
        $path = $this->getPath($this->scaffoldCommandObj->getObjName('Name') . 'TableSeeder', 'seed');


        // Create directory
        $this->makeDirectory($path);


        if ($this->files->exists($path)) {
            if ($this->scaffoldCommandObj->confirm($path . ' already exists! Do you wish to overwrite? [yes|no]')) {
                // Put file
                $this->files->put($path, $this->compileSeedStub());
                $this->getSuccessMsg();
            }
        } else {

            // Put file
            $this->files->put($path, $this->compileSeedStub());
            $this->getSuccessMsg();

        }

    }


    protected function getSuccessMsg()
    {
        $this->scaffoldCommandObj->info('Seed created successfully.');
    }


    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileSeedStub()
    {
        $stub = $this->files->get(__DIR__ . '/../stubs/seed.stub');
        $this->replaceClassName($stub)
             ->replaceModelName($stub);

        // テーブルリストをセット
        if ($schema = $this->scaffoldCommandObj->option('schema')) {
            $schema = (new SchemaParser)->parse($schema);
        }
// dump($schema);

        $all_columns = '';
        foreach ($schema as $k => $v) {
            $sample_value = null;
            if ( $v['type']=='text' || $v['type']=='string' ||  $v['type']=='varchar' ){
                $sample_value = '"sample"';
            } else {
                $sample_value = '999';
            }
$all_columns .= <<< DOC_END
            '{$v['name']}'          => {$sample_value} ,

DOC_END;
        }
        // dd($all_columns);
        $stub = str_replace('{{all_columns}}', $all_columns, $stub);

        return $stub;
    }


    private function replaceClassName(&$stub)
    {
        $name = $this->scaffoldCommandObj->getObjName('Name');

        $stub = str_replace('{{class}}', $name, $stub);

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
            $stub = str_replace('{{prefix}}', $prefix.'.', $stub);
        else
            $stub = str_replace('{{prefix}}', '', $stub);

        return $this;
    }



}