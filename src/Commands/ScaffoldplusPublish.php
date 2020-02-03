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
use Illuminate\Console\DetectsApplicationNamespace;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ScaffoldplusPublishCommand extends Command
{
    use DetectsApplicationNamespace, MakerTrait;

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

        if ( ! is_dir($destinationDir) ){
            $success = \File::copyDirectory($sourceDir, $destinationDir);        
            $this->info("Success: /assets/ folder copied.");
        }
        else {
            $success = \File::copyDirectory($sourceDir, $destinationDir);        
            $this->info("Success: /assets/ folder updated.");
        }

        // 2. copy Stubs/resources/lang/ja/excrud.php
        $sourceFile      = __DIR__ . '/../Stubs/resources/lang/en/excrud.php';
        $destinationFile = resource_path('lang/en/excrud.php');
        if ( \File::copy($sourceFile, $destinationFile) ){
            $this->info("Success: resources/lang/en/excrud.php copied.");            
        }

        $sourceFile      = __DIR__ . '/../Stubs/resources/lang/ja/excrud.php';
        $destinationFile = resource_path('lang/ja/excrud.php');
        if ( \File::copy($sourceFile, $destinationFile) ){
            $this->info("Success: resources/lang/en/excrud.php copied.");            
        }

    }

    public function handle()
    {
        return $this->fire();
    }

}
