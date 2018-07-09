<?php
declare(strict_types=1);
/**
 * ServiceProvider.php
 *
 * @project  laravel-twitter-text-formatter
 * @category JoePritchard\LaravelTwitterTextFormatter
 * @author   Joe Pritchard <joe@joe-pritchard.uk>
 *
 * Created:  09/07/18 17:10
 *
 */

namespace JoePritchard\LaravelTwitterTextFormatter\Providers;


/**
 * Class ServiceProvider
 * @package JoePritchard\LaravelTwitterTextFormatter
 */
class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Boot the service provider
     */
    public function boot(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../../config/twitter-formatter.php', 'twitter-formatter'
        );

        $this->publishes([
            __DIR__ . '/../../config/twitter-formatter.php' => config_path('twitter-formatter.php')
        ], 'config');
    }
}