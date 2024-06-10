<?php

namespace Akat03\Scaffoldplus\Commands;

use Akat03\Scaffoldplus\Makes\MakeController;
use Akat03\Scaffoldplus\Makes\MakeLayout;
use Akat03\Scaffoldplus\Makes\MakeMigration;
use Akat03\Scaffoldplus\Makes\MakeModel;
use Akat03\Scaffoldplus\Makes\MakerTrait;
use Akat03\Scaffoldplus\Makes\MakeSeed;
use Akat03\Scaffoldplus\Makes\MakeView;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\File;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;


class ScaffoldplusPublishCommand extends Command
{
    use MakerTrait;

    /**
     * The console command name!
     *
     * @var string
     */
    protected $name = 'scaffoldplus:publish';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish /assets/js/ , /assets/css/ files';


    /**
     * Create a new command instance.
     *
     * @param Filesystem $files
     * @param Composer $composer
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();
        $this->files = $files;
        $this->composer = $composer;
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        // 1. copy /assets folder
        $sourceDir      = __DIR__ . '/../Stubs/assets';
        $destinationDir = public_path() . '/assets';

        if (!is_dir($destinationDir)) {
            if (File::copyDirectory($sourceDir, $destinationDir)) {
                $this->info("Success: /assets/ folder copied.");
            }
        } else {
            $sourceDir      = __DIR__ . '/../Stubs/assets/excrud';
            $destinationDir = public_path() . '/assets/excrud';
            $success = File::copyDirectory($sourceDir, $destinationDir);
            $this->info("Success: /assets/ folder updated.");
        }


        // 2. copy Stubs/resources/views/crud_components folder
        $sourceDir      = __DIR__ . '/../Stubs/resources/views/crud_components';
        $destinationDir = resource_path('views/crud_components');
        if (!is_dir($destinationDir)) {
            $success = File::copyDirectory($sourceDir, $destinationDir);
            $this->info("Success: resources/{$destinationDir}/ copied.");
        } else {
            $success = File::copyDirectory($sourceDir, $destinationDir);
            $this->info("Success: resources/{$destinationDir}/ updated.");
        }

        // 3-1. copy Stubs/resources/lang/en/excrud.php
        $sourceFile      = __DIR__ . '/../Stubs/resources/lang/en/excrud.php';
        $destinationFile = '';

        if (is_dir(base_path('/lang'))) {
            // for Laravel 9
            $destinationFile = base_path('lang/en/excrud.php');
        } else {
            $destinationFile = resource_path('lang/en/excrud.php');
        }

        if (File::copy($sourceFile, $destinationFile)) {
            $this->info("Success: resources/{$destinationFile} copied.");
        }

        // 3-2. copy Stubs/resources/lang/ja/excrud.php
        $sourceFile      = __DIR__ . '/../Stubs/resources/lang/ja/excrud.php';
        $destinationFile = resource_path('lang/ja/excrud.php');
        if (!is_dir(resource_path('lang/ja/'))) {
            File::makeDirectory(resource_path('lang/ja/'));
        }
        if (File::copy($sourceFile, $destinationFile)) {
            $this->info("Success: resources/{$destinationFile} copied.");
        }
    }

    public function handle()
    {
        return $this->fire();
    }
}
