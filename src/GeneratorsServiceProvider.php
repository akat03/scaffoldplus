<?php

namespace Akat03\Scaffoldplus;

use Illuminate\Support\ServiceProvider;

class GeneratorsServiceProvider extends ServiceProvider {

	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//

	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{

		$this->registerScaffoldGenerator();

	}


	/**
	 * Register the make:scaffold generator.
	 */
	private function registerScaffoldGenerator()
	{
		$this->app->singleton('command.larascaf.scaffold', function ($app) {
			return $app['Akat03\Scaffoldplus\Commands\ScaffoldMakeCommand'];
		});
		$this->commands('command.larascaf.scaffold');

		$this->app->singleton('command.scaffoldplus.publish', function ($app) {
			return $app['Akat03\Scaffoldplus\Commands\ScaffoldplusPublishCommand'];
		});
		$this->commands('command.scaffoldplus.publish');
	}


}
