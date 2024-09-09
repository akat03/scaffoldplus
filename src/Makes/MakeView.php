<?php

namespace Akat03\Scaffoldplus\Makes;

use Illuminate\Filesystem\Filesystem;
use Akat03\Scaffoldplus\Commands\ScaffoldMakeCommand;
use Akat03\Scaffoldplus\Migrations\SchemaParser;
use Akat03\Scaffoldplus\Migrations\SyntaxBuilder;

class MakeView
{
  use MakerTrait;


  protected $scaffoldCommandObj;
  protected $viewName;
  protected $schemaArray = [];

  public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files, $viewName)
  {
    $this->files = $files;
    $this->scaffoldCommandObj = $scaffoldCommand;
    $this->viewName = $viewName;
    $this->getSchemaArray();

    $this->start();
  }

  private function start()
  {
    $this->generateView($this->viewName); // index, show, edit and create
    $this->generatePagination();          // pagination
    $this->generateView('index_ajax');    // index_ajax
    $this->generateView('sort');          // index_ajax
  }


  protected function getSchemaArray()
  {
    if ($this->scaffoldCommandObj->option('schema') != null) {
      if ($schema = $this->scaffoldCommandObj->option('schema')) {
        $this->schemaArray = (new SchemaParser)->parse($schema);
      }
    }
  }


  protected function generateView($nameView = 'index')
  {
    $option_prefix = $this->scaffoldCommandObj->option('prefix');
    $option_dir = $option_prefix ? $option_prefix . '/' : '';

    // Get path
    $path = $this->getPath($option_dir . $this->scaffoldCommandObj->getObjName('names'), 'view-' . $nameView);

    if ($nameView == 'index_ajax') {
      $path = $this->getPath($this->scaffoldCommandObj->getObjName('names'), 'view-index');
      $path = preg_replace("{index\.blade\.php}", "index_ajax.blade.php", $path);
    } elseif ($nameView == 'sort') {
      $path = $this->getPath($this->scaffoldCommandObj->getObjName('names'), 'view-index');
      $path = preg_replace("{index\.blade\.php}", "sort.blade.php", $path);
    }

    // dd($nameView . ": " . $path);


    // Create directory
    $this->makeDirectory($path);
    if ($this->files->exists($path)) {
      if ($this->scaffoldCommandObj->confirm($path . ' already exists! Do you wish to overwrite? [yes|no]')) {
        // Put file
        $this->files->put($path, $this->compileViewStub($nameView));
      }
    } else {
      // Put file
      $this->files->put($path, $this->compileViewStub($nameView));
    }
  }


  protected function generatePagination($nameView = 'pagination')
  {
    // Get path
    $path = $this->getPath('pagination', 'view-index');
    // dump($path);
    $path = preg_replace("{pagination/index\.blade\.php}", "pagination/default.blade.php", $path);
    // dump($path);
    // Create directory
    $this->makeDirectory($path);
    if ($this->files->exists($path)) {
      if ($this->scaffoldCommandObj->confirm($path . ' already exists! Do you wish to overwrite? [yes|no]')) {
        // Put file
        $this->files->put($path, $this->compileViewStub($nameView));
      }
    } else {
      // Put file
      $this->files->put($path, $this->compileViewStub($nameView));
    }
  }






  /**
   * Compile the migration stub.
   *
   * @param $nameView
   * @return string
   * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
   */
  protected function compileViewStub($nameView)
  {

    // stub directory
    $stub_dir = __DIR__ . '/../Stubs/';

    // command line option 'stubs'
    $option_stubs = $this->scaffoldCommandObj->option('stubs');
    if ($option_stubs) {
      $option_stubs = rtrim($option_stubs, '/') . '/';
      $stub_dir = $option_stubs;
    }
    // dump( "Scaffolding stubs DIR: " . $option_stubs );


    // $stub = $this->files->get(__DIR__ . '/../Stubs/html_assets/'.$nameView.'.stub');
    $stub = $this->files->get($stub_dir . 'html_assets/' . $nameView . '.stub');

    if ($nameView == 'show') {
      // show.blade.php
      $this->replaceName($stub)
        ->replaceSchemaShow($stub);
    } elseif ($nameView == 'edit') {
      // edit.blade.php
      $this->replaceName($stub)
        ->replaceSchemaEdit($stub);
    } elseif ($nameView == 'create') {
      // edit.blade.php
      $this->replaceName($stub)
        ->replaceSchemaCreate($stub);
    } elseif ($nameView == 'pagination') {
      // pagination/index.blade.php
      $this->replaceName($stub)
        ->replaceSchemaPagination($stub);
    } elseif ($nameView == 'index_ajax') {
      // pagination/index_ajax.blade.php
      $this->replaceName($stub)
        ->replaceSchemaPagination($stub);
    } else {
      // index.blade.php
      $this->replaceName($stub)
        ->replaceSchemaIndex($stub);
    }

    // Laravel 5.4
    if (version_compare(app()->version(), '5.5', '<')) {
      $this->replaceOldBladeSyntax($stub);
    }

    return $stub;
  }



  /**
   * Replace the class name in the stub.
   *
   * @param  string $stub
   * @return $this
   */
  protected function replaceOldBladeSyntax(&$stub)
  {
    echo ("Info: You are using Laravel " . app()->version() . " . replace Blade Syntax.\n");
    $stub = str_replace('@php', '<?php ', $stub);
    $stub = str_replace('@endphp', '?>', $stub);
    $stub = str_replace('@csrf', '<input type="hidden" name="_token" value="{{ csrf_token() }}"', $stub);

    return $this;
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

    // prefix
    $prefix           = $this->scaffoldCommandObj->option('prefix');
    $prefix           = str_replace('"', '', $prefix);

    if ($prefix != null)
      $stub = str_replace('{{prefix}}', $prefix . '.', $stub);
    else
      $stub = str_replace('{{prefix}}', '', $stub);

    // extends
    $extends = $this->scaffoldCommandObj->option('extends');
    $extends = str_replace('"', '', $extends);

    if ($extends == null) {
      $extends = 'layout';
    }
    $stub = str_replace('{{extends}}', $extends, $stub);


    return $this;
  }



  /**
   * Replace the schema for the index.stub.
   *
   * @param  string $stub
   * @return $this
   */
  protected function replaceSchemaPagination(&$stub)
  {

    // Create view index header fields
    $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-index-header');
    $stub = str_replace('{{header_fields}}', $schema, $stub);


    // Create view index content fields
    $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-index-content');
    $stub = str_replace('{{content_fields}}', $schema, $stub);


    return $this;
  }



  /**
   * Replace the schema for the index.stub.
   *
   * @param  string $stub
   * @return $this
   */
  protected function replaceSchemaIndex(&$stub)
  {

    // Create view index header fields
    $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-index-header');
    $stub = str_replace('{{header_fields}}', $schema, $stub);


    // Create view index content fields
    $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-index-content');
    $stub = str_replace('{{content_fields}}', $schema, $stub);


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
   * Replace the schema for the edit.stub.
   *
   * @param  string $stub
   * @return $this
   */
  private function replaceSchemaEdit(&$stub)
  {

    // Create view index content fields
    $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-edit-content', $this->scaffoldCommandObj->option('form'));
    // dd($schema);
    $stub = str_replace('{{content_fields}}', $schema, $stub);

    return $this;
  }


  /**
   * Replace the schema for the edit.stub.
   *
   * @param  string $stub
   * @return $this
   */
  private function replaceSchemaCreate(&$stub)
  {


    // Create view index content fields
    $schema = (new SyntaxBuilder)->create($this->schemaArray, $this->scaffoldCommandObj->getMeta(), 'view-create-content', $this->scaffoldCommandObj->option('form'));
    $stub = str_replace('{{content_fields}}', $schema, $stub);

    return $this;
  }
}
