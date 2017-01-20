<?php

namespace Holaluz\Masvoz;

use Illuminate\Support\ServiceProvider;

class MasvozServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/config.php' => config_path('masvoz.php'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/config/config.php';
        $this->mergeConfigFrom($configPath, 'masvoz');

        $this->app->singleton('masvoz', function($app) {
            return new Masvoz($app['config']->get('masvoz'));
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['masvoz'];
    }
}
