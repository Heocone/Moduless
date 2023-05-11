<?php

namespace Modules;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use Modules\User\src\Commands\TestCommand;
use Modules\User\src\Http\Middlewares\DemoMiddleware;

class ModuleServiceProvider extends ServiceProvider
{
    private $middlewares = [
        'demo' => DemoMiddleware::class
    ];

    private $commands = [
        TestCommand::class
    ];

    public function boot()
    {
        $modules = $this->getModules();
        if (!empty($modules)) {
            foreach ($modules as $module) {
                $this->registerModule($module);
            }
        }
    }

    public function register()
    {
        //Config
        $modules = $this->getModules();
        if (!empty($modules)) {
            foreach ($modules as $module) {
               $this->registerConfigs($module);
            }
        }

        //Middlewares
        $this->registerMiddlewares();

        //commands
        $this->commands($this->commands);
    }
    private function getModules()
    {
        $directories = array_map('basename',File::directories(__DIR__));
        return $directories;
    }
    //RegisterModules
    private function registerModule( $module)
    {
        $modulePath = __DIR__ . "/{$module}";
        
        //Khai bao routes
        if (File::exists($modulePath.'/routes/routes.php')) {
            $this->loadRoutesFrom($modulePath.'/routes/routes.php');
        }
        // Khai bao migrations
        if (File::exists($modulePath.'/migrations')) {
            $this->loadMigrationsFrom($modulePath.'/migrations');
        }

        // Khai bao languages
        if (File::exists($modulePath.'/resources/languages')) {
            $this->loadTranslationsFrom($modulePath.'/resources/languages', strtolower($module));
            $this->loadJsonTranslationsFrom($modulePath.'/resources/languages');
        }

        //Khai bao views
        if (File::exists($modulePath.'/resources/views')) {
            $this->loadviewsFrom($modulePath.'/resources/views', strtolower($module));

        }

        //Helpers
        if (File::exists($modulePath.'/helpers')) {
            $helpersList = File::allFiles($modulePath.'/helpers');
            if (!empty($helpersList)) {
                foreach ($helpersList as $helper) {
                    $file = $helper->getPathName();
                    require($file);
                }
            }
        }
    }

    //register configs
    private function registerConfigs($module)
    {
        //config
        $configPath = __DIR__.'/'.$module . '/configs';
        if (File::exists($configPath)) {
            $configFile = array_map('basename',File::allFiles($configPath));
            
            foreach ($configFile as $config) {
                $alias = basename($config, '.php');
                
                $this->mergeConfigFrom($configPath.'/'.$config, $alias);
            }
        }
    }

    //register middleware
    private function registerMiddlewares()
    {
        if (!empty($this->middlewares)) {
            foreach ($this->middlewares as $key => $middleware) {
                $this->app['router']->pushMiddlewareToGroup($key, $middleware);
            }
        }
    }
}

