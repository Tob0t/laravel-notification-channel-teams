<?php

namespace NotificationChannels\MicrosoftTeams;

use GuzzleHttp\Client as HttpClient;
use Illuminate\Support\ServiceProvider;

class MicrosoftTeamsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        // Bootstrap code here.

        $this->app->when(MicrosoftTeamsChannel::class)
            ->needs(MicrosoftTeams::class)
            ->give(static function () {
                return new MicrosoftTeams(
                    new HttpClient()
                );
            });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}
