<?php

namespace Mugen\EloquentValidation;

use Event;
use Illuminate\Support\ServiceProvider;
use Mugen\EloquentValidation\Contracts\ShouldValidate;

/**
 * Class EloquentServiceProvider
 * @package Mugen\EloquentValidation
 */
class EloquentServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/eloquent.php', 'eloquent');
    }

    /**
     * Boot the provider.
     */
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/eloquent.php' => config_path('eloquent.php')]);

        $this->listen();
    }

    protected function listen(): void
    {
        Event::listen('eloquent.*', function (string $eventName, array $data) {
            $action = $this->getAction($eventName);

            $eloquent = $data[0];

            if (
                in_array($action, $this->app['config']->get('eloquent.events'))
                && $eloquent instanceof ShouldValidate
                && !in_array(get_class($eloquent), $this->app['config']->get('eloquent.skip'))
            )
                return $eloquent->validator($action)->validate();

            return true;
        });
    }

    /**
     * @param string $eventName
     * @return string
     */
    protected function getAction(string $eventName): string
    {
        preg_match('/^eloquent\.([a-z]*)\: ([A-Za-z\\\]*)$/', $eventName, $match);

        return $match[1];
    }
}
