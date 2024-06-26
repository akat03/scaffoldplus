<?php

namespace Akat03\Scaffoldplus\Makes;

use Illuminate\Filesystem\Filesystem;
use Akat03\Scaffoldplus\Commands\ScaffoldMakeCommand;
use Akat03\Scaffoldplus\Migrations\SchemaParser;
use Akat03\Scaffoldplus\Migrations\SyntaxBuilder;

class MakeMigration
{
    use MakerTrait;

    protected $scaffoldCommandObj;

    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandObj = $scaffoldCommand;

        $this->start();
    }


    protected function start()
    {
        $name = 'create_' . str_plural(strtolower($this->scaffoldCommandObj->argument('name'))) . '_table';

        if ($this->files->exists($path = $this->getPath($name))) {
            return $this->scaffoldCommandObj->error($this->type . ' already exists!');
        }

        $this->makeDirectory($path);

        // Grava o arquivo
        $this->files->put($path, $this->compileMigrationStub());

        $this->scaffoldCommandObj->info('Migration created successfully');
    }


    /**
     * Get the path to where we should store the migration.
     *
     * @param  string $name
     * @return string
     */
    protected function getPath($name)
    {
        return './database/migrations/' . date('Y_m_d_His') . '_' . $name . '.php';
    }


    /**
     * Compile the migration stub.
     *
     * @return string
     */
    protected function compileMigrationStub()
    {
        $stub = $this->files->get(__DIR__ . '/../Stubs/migration.stub');

        $this->replaceClassName($stub)
            ->replaceSchema($stub)
            ->replaceTableName($stub);

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
        $className = ucwords(camel_case('Create' . str_plural($this->scaffoldCommandObj->argument('name')) . 'Table'));
        $stub = str_replace('{{class}}', $className, $stub);

        return $this;
    }

    /**
     * Replace the table name in the stub.
     *
     * @param  string $stub
     * @return $this
     */
    protected function replaceTableName(&$stub)
    {
        $table = $this->scaffoldCommandObj->getMeta()['table'];
        $stub = str_replace('{{table}}', $table, $stub);

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
            // dd($schema);
            $schema = (new SchemaParser)->parse($schema);
        }


        if ($type == 'migration') {
            // Create migration fields
            $schema = (new SyntaxBuilder)->create($schema, $this->scaffoldCommandObj->getMeta());
            $stub = str_replace(['{{schema_up}}', '{{schema_down}}'], $schema, $stub);
        } else if ($type = 'controller') {
            // Create controllers fields
            $schema = (new SyntaxBuilder)->create($schema, $this->scaffoldCommandObj->getMeta(), 'controller');
            $stub = str_replace('{{model_fields}}', $schema, $stub);
        } else {
        }

        return $this;
    }
}
