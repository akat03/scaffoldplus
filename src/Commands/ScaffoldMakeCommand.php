<?php

namespace Akat03\Scaffoldplus\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Composer;
use Akat03\Scaffoldplus\Makes\MakeController;
use Akat03\Scaffoldplus\Makes\MakeApiController;
use Akat03\Scaffoldplus\Makes\MakeLayout;
use Akat03\Scaffoldplus\Makes\MakeMigration;
use Akat03\Scaffoldplus\Makes\MakeModel;
use Akat03\Scaffoldplus\Makes\MakerTrait;
use Akat03\Scaffoldplus\Makes\MakeSeed;
use Akat03\Scaffoldplus\Makes\MakeView;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class ScaffoldMakeCommand extends Command
{
	use MakerTrait;

	/**
	 * The console command name!
	 *
	 * @var string
	 */
	protected $name = 'scaffoldplus:create';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Create Migration, Model, Controller, and YAML(json)';

	/**
	 * Meta information for the requested migration.
	 *
	 * @var array
	 */
	protected $meta;

	/**
	 * @var Composer
	 */
	private $composer;

	/**
	 * Views to generate
	 *
	 * @var array
	 */
	// private $views = ['index', 'create', 'show', 'edit'];
	private $views = ['index', 'show', 'edit'];

	/**
	 * Store name from Model
	 * @var string
	 */
	private $nameModel = "";

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
		// Start Scaffold
		$this->info('Configuring ' . $this->getObjName("Name") . '...');

		// Setup migration and saves configs
		$this->meta['action'] = 'create';
		$this->meta['var_name'] = $this->getObjName("name");
		$this->meta['table'] = $this->getObjName("names"); // Store table name

		// Generate files
		$this->makeMigration();
		$this->makeSeed();
		$this->makeModel();
		$this->makeController();
		$this->makeApiController();
		$this->makeViewLayout();
		$this->makeViews();
	}


	/**
	 * Generate the desired migration.
	 */
	protected function makeMigration()
	{
		new MakeMigration($this, $this->files);
	}

	/**
	 * Generate an Eloquent model, if the user wishes.
	 */
	protected function makeModel()
	{
		new MakeModel($this, $this->files);
	}

	/**
	 * Generate a Seed
	 */
	private function makeSeed()
	{
		new MakeSeed($this, $this->files);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['name', InputArgument::REQUIRED, 'The name of the model. (Ex: Post)'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['schema', 's', InputOption::VALUE_REQUIRED, 'Schema to generate scaffold files. (Ex: --schema="title:string")', null],
			['form', 'f', InputOption::VALUE_OPTIONAL, 'Use Illumintate/Html Form facade to generate input fields', false],
			['prefix', 'p', InputOption::VALUE_OPTIONAL, 'Generate schema with prefix', false],

			['extends', 'e', InputOption::VALUE_OPTIONAL, 'Generate view files with extends', false],
			['crud_format', 'c', InputOption::VALUE_OPTIONAL, 'Select CRUD option file from one of the following (json or yaml)', false],
			['stubs', 'stubs', InputOption::VALUE_OPTIONAL, 'Set the stub directory', false],
			['addapi', 'addapi', InputOption::VALUE_OPTIONAL, 'Generate API Controller', 'not generate'],
		];
	}

	/**
	 * Make a Controller with default actions
	 */
	private function makeController()
	{

		new MakeController($this, $this->files);
	}

	/**
	 * Make a APIController with default actions
	 */
	private function makeApiController()
	{

		new MakeApiController($this, $this->files);
	}

	/**
	 * Setup views and assets
	 *
	 */
	private function makeViews()
	{

		foreach ($this->views as $view) {
			// index, create, show, edit
			new MakeView($this, $this->files, $view);
		}

		$this->info('Views created successfully.');

		$this->info('Dump-autoload...');
		$this->composer->dumpAutoloads();

		$this->info("\n==================== Add this to ./routes/web.php");
		$this->info('use App\\Http\\Controllers\\' . $this->getObjName("Name") .  'Controller;' . "\n");

		$this->info('Route::get("' . $this->getObjName("names") . '/dl_delete_submit", "' . $this->getObjName("Name") . 'Controller@dl_delete_submit")->name("' . $this->getObjName("names") . '.dl_delete_submit"); // multiple delete');
		$this->info('Route::post("' . $this->getObjName("names") . '/sort_exec_ajax", "' . $this->getObjName("Name") . 'Controller@sort_exec_ajax")->name("' . $this->getObjName("names") . '.sort_exec_ajax"); // sort exec');
		$this->info('Route::get("' . $this->getObjName("names") . '/sort", "' . $this->getObjName("Name") . 'Controller@sort")->name("' . $this->getObjName("names") . '.sort"); // sort view');
		$this->info('Route::delete("' . $this->getObjName("names") . '/destroy_ajax", "' . $this->getObjName("Name") . 'Controller@destroy_ajax")->name("' . $this->getObjName("names") . '.destroy_ajax"); // ajax delete');
		$this->info('Route::get("' . $this->getObjName("names") . '/index_ajax", "' . $this->getObjName("Name") . 'Controller@index_ajax")->name("' . $this->getObjName("names") . '.index_ajax"); // ajax index');
		$this->info('Route::get("' . $this->getObjName("names") . '/search", "' . $this->getObjName("Name") . 'Controller@search")->name("' . $this->getObjName("names") . '.search");');
		$laravel_major_version = preg_replace("{([0-9]+)\.([0-9]+)\.([0-9]+)}", "$1", app()->version());
		if ($laravel_major_version >= 8) {
			$this->info('Route::resource("' . $this->getObjName("names") . '", ' . $this->getObjName("Name") . '\App\Http\Controllers\Controller::class);');
		} else {
			$this->info('Route::resource("' . $this->getObjName("names") . '","' . $this->getObjName("Name") . 'Controller");');
		}
		$this->info("==================== Add this\n");

		$addapi = $this->option('addapi');
		if ($addapi !== 'not generate') {
			$this->info("\n==================== Add this to ./routes/api.php");
			$this->info('use App\\Http\\Controllers\\' . $this->getObjName("Name") .  'ApiController;' . "\n");
			if ($laravel_major_version >= 8) {
				$this->info('Route::apiResource("' . $this->getObjName("names") . '", ' .$this->getObjName("Name")  . 'ApiController::class);');
			} else {
				$this->info('Route::apiResource("' . '","' . $this->getObjName("Name") . $this->getObjName("names")  .  'ApiController");');
			}
			$this->info("==================== Add this\n");
		}


	}

	/**
	 * Make a layout.blade.php with bootstrap
	 *
	 * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
	 */
	private function makeViewLayout()
	{
		new MakeLayout($this, $this->files);
	}

	/**
	 * Get access to $meta array
	 * @return array
	 */
	public function getMeta()
	{
		return $this->meta;
	}

	/**
	 * Generate names
	 *
	 * @param string $config
	 * @return mixed
	 * @throws \Exception
	 */
	public function getObjName($config = 'Name')
	{

		$names = [];
		$args_name = $this->argument('name');

		// Name[0] = Tweet
		$names['Name'] = str_singular(ucfirst($args_name));
		// Name[1] = Tweets
		$names['Names'] = str_plural(ucfirst($args_name));
		// Name[2] = tweets
		$names['names'] = str_plural(strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $args_name)));
		// Name[3] = tweet
		$names['name'] = str_singular(strtolower(preg_replace('/(?<!^)([A-Z])/', '_$1', $args_name)));

		if (!isset($names[$config])) {
			throw new \Exception("Position name is not found");
		};

		return $names[$config];
	}

	public function handle()
	{
		return $this->fire();
	}
}
