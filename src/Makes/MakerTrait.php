<?php

namespace Akat03\Scaffoldplus\Makes;

use Illuminate\Support\Str;
use Illuminate\Filesystem\Filesystem;
use Akat03\Scaffoldplus\Commands\ScaffoldMakeCommand;

trait MakerTrait
{

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected $files;
    protected $scaffoldCommandM;

    /**
     * @param ScaffoldMakeCommand $scaffoldCommand
     * @param Filesystem $files
     */
    public function __construct(ScaffoldMakeCommand $scaffoldCommand, Filesystem $files)
    {
        $this->files = $files;
        $this->scaffoldCommandM = $scaffoldCommand;

        $this->generateNames($scaffoldCommand);
    }


    /**
     * Get the path to where we should store the controller.
     *
     * @param $file_name
     * @param string $path
     * @return string
     */
    protected function getPath($file_name, $path = 'controller')
    {
        $laravel_major_version = preg_replace("{([0-9]+)\.([0-9]+)\.([0-9]+)}", "$1", app()->version());

        if ($path == "controller") {
            $option_prefix = $this->scaffoldCommandObj->option('prefix');
            $prefix_path = $option_prefix ? Str::studly($option_prefix) . '/' : ''; // パスカルケースにする
            return './app/Http/Controllers/' . $prefix_path . $file_name . '.php';
        } elseif ($path == "model") {
            if ($laravel_major_version >= 8) {
                return './app/Models/' . $file_name . '.php';
            } else {
                return './app/' . $file_name . '.php';
            }
        } elseif ($path == "yml" || $path == "json") {
            $ext = $path;
            if ($laravel_major_version >= 8) {
                return './app/Models/' . $file_name . ".{$ext}";
            } else {
                return './app/' . $file_name . ".{$ext}";
            }
        } elseif ($path == "seed") {
            return './database/seeders/' . $file_name . '.php';
        } elseif ($path == "view-index") {
            return './resources/views/' . $file_name . '/index.blade.php';
        } elseif ($path == "view-edit") {
            return './resources/views/' . $file_name . '/edit.blade.php';
        } elseif ($path == "view-show") {
            return './resources/views/' . $file_name . '/show.blade.php';
        } elseif ($path == "view-create") {
            return './resources/views/' . $file_name . '/create.blade.php';
        }
    }


    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {

        if (!$this->files->isDirectory(dirname($path))) {
            $this->files->makeDirectory(dirname($path), 0777, true, true);
        }
    }
}
