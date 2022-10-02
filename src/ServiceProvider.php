<?php

declare(strict_types=1);

namespace Karacweb\InfomaniakNewsletter;

use Infomaniak\ClientApiNewsletter\Client;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = false;
    public function boot(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/infomaniak-newsletter.php', 'infomaniak-newsletter');
        $this->publishes([
            __DIR__.'/../config/infomaniak-newsletter.php' => config_path('infomaniak-newsletter.php'),
        ]);
    }

        public function register(): void
        {
            $this->app->singleton(Newsletter::class, function () {
                $infomaniakApi = new Client(config('infomaniak-newsletter.apiKey'), config('infomaniak-newsletter.secretKey'));
                $lists = Newsletter::createLists(config('infomaniak-newsletter'));
                return new Newsletter($infomaniakApi, $lists, config('infomaniak-newsletter.defaultListName'));
            });
            $this->app->alias(Newsletter::class, 'infomaniak-newsletter');
        }
}
