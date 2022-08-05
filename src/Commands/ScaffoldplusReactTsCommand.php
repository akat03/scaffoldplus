<?php

namespace Akat03\Scaffoldplus\Commands;

use Akat03\Scaffoldplus\Makes\MakerTrait;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ScaffoldplusReactTsCommand extends Command
{
    use MakerTrait;

    protected $signature = 'scaffoldplus:react-ts {yaml_file_name}';


    /**
     * The console command name!
     *
     * @var string
     */
    protected $name = 'scaffoldplus:react-ts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '[Front End] Create React(TypeScript) files from YAML';


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
     * @param  string   $model_name   モデル名 stubの [model_name] を置き換える
     * @param  string   $yaml         yaml
     * @return mixed
     */
    public function fire(string $model_name, $yaml)
    {
        // 0. check top /scaffoldplus_react-ts/ folder
        $baseSourceDir      = __DIR__ . '/../Stubs/react-ts';
        $baseDestinationDir = base_path('scaffoldplus_react-ts');

        if (!is_dir($baseDestinationDir)) {
            $success = File::makeDirectory($baseDestinationDir);
        }

        // 1. copy  /react-ts/components  folder
        $SourceDir      = $baseSourceDir      . '/components';
        $DestinationDir = $baseDestinationDir . '/components';

        if (!is_dir($DestinationDir)) {
            File::makeDirectory($DestinationDir);
        }
        if (!is_dir("{$DestinationDir}/{$model_name}")) {
            File::makeDirectory("{$DestinationDir}/{$model_name}");
        }

        $files  = File::allFiles($SourceDir);
        foreach ($files as $file) {
            $relpath = ($file->getRelativePath() != '') ? $file->getRelativePath() . '/' : '';
            $destinationFile = "{$DestinationDir}/{$relpath}{$file->getFilename()}";
            $destinationFile = str_replace('[model_name]', $model_name, $destinationFile);
            File::copy($file->getRealPath(), $destinationFile);
        }
        $this->info("\ncopied  scaffoldplus_react-ts/components/");



        // 2. copy  /react-ts/data  folder
        $SourceDir      = $baseSourceDir      . '/data';
        $DestinationDir = $baseDestinationDir . '/data';

        if (!is_dir($DestinationDir)) {
            File::makeDirectory($DestinationDir);
        }
        $files  = File::allFiles($SourceDir);
        foreach ($files as $file) {
            $relpath = ($file->getRelativePath() != '') ? $file->getRelativePath() . '/' : '';
            $destinationFile = "{$DestinationDir}/{$relpath}{$file->getFilename()}";
            $destinationFile = str_replace('[model_name]', $model_name, $destinationFile);
            File::copy($file->getRealPath(), $destinationFile);
        }
        $this->info("copied  scaffoldplus_react-ts/data/");


        // 3. copy  /react-ts/helpers  folder
        $SourceDir      = $baseSourceDir      . '/helpers';
        $DestinationDir = $baseDestinationDir . '/helpers';

        if (!is_dir($DestinationDir)) {
            File::makeDirectory($DestinationDir);
        }
        $files  = File::allFiles($SourceDir);
        foreach ($files as $file) {
            $relpath = ($file->getRelativePath() != '') ? $file->getRelativePath() . '/' : '';
            $destinationFile = "{$DestinationDir}/{$relpath}{$file->getFilename()}";
            $destinationFile = str_replace('[model_name]', $model_name, $destinationFile);
            File::copy($file->getRealPath(), $destinationFile);
        }
        $this->info("copied  scaffoldplus_react-ts/helpers/");


        // 4. copy  /react-ts/pages  folder
        $SourceDir      = $baseSourceDir      . '/pages';
        $DestinationDir = $baseDestinationDir . '/pages';

        if (!is_dir($DestinationDir)) {
            File::makeDirectory($DestinationDir);
        }
        if (!is_dir("{$DestinationDir}/api")) {
            File::makeDirectory("{$DestinationDir}/api");
        }
        if (!is_dir("{$DestinationDir}/{$model_name}")) {
            File::makeDirectory("{$DestinationDir}/{$model_name}");
        }
        if (!is_dir("{$DestinationDir}/api/{$model_name}")) {
            File::makeDirectory("{$DestinationDir}/api/{$model_name}");
        }
        if (!is_dir("{$DestinationDir}/api/{$model_name}/edit")) {
            File::makeDirectory("{$DestinationDir}/api/{$model_name}/edit");
        }
        $files  = File::allFiles($SourceDir);
        foreach ($files as $file) {
            $relpath = ($file->getRelativePath() != '') ? $file->getRelativePath() . '/' : '';
            $destinationFile = "{$DestinationDir}/{$relpath}{$file->getFilename()}";
            $destinationFile = str_replace('[model_name]', $model_name, $destinationFile);
            File::copy($file->getRealPath(), $destinationFile);
        }
        $this->info("copied  scaffoldplus_react-ts/pages/");


        // 5. copy  /react-ts/helpers  folder
        $SourceDir      = $baseSourceDir      . '/services';
        $DestinationDir = $baseDestinationDir . '/services';

        if (!is_dir($DestinationDir)) {
            File::makeDirectory($DestinationDir);
        }
        $files  = File::allFiles($SourceDir);
        foreach ($files as $file) {
            $relpath = ($file->getRelativePath() != '') ? $file->getRelativePath() . '/' : '';
            $destinationFile = "{$DestinationDir}/{$relpath}{$file->getFilename()}";
            $destinationFile = str_replace('[model_name]', $model_name, $destinationFile);
            File::copy($file->getRealPath(), $destinationFile);
        }
        $this->info("copied  scaffoldplus_react-ts/services/");
    }

    public function handle()
    {
        $yaml_file_name = $this->argument('yaml_file_name');
        $yaml_full_path = base_path($yaml_file_name);

        if (!is_file($yaml_full_path)) {
            echo "\n";
            echo "scaffoldplus ERROR: yaml_file_name ({$yaml_full_path}) is not exists.";
            echo "\n\n";
        } else {
            // model_name
            $model_name = Str::lower(pathinfo($yaml_file_name, PATHINFO_FILENAME));
            $model_name = Str::plural($model_name);

            // parse yaml
            $input_src = file_get_contents($yaml_file_name);
            $yaml = \Symfony\Component\Yaml\Yaml::parse($input_src);
            $yaml = json_decode(json_encode($yaml));        // array to object

            return $this->fire($model_name, $yaml);
        }
    }
}
