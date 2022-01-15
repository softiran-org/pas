<?php

namespace Pas;

use Illuminate\Support\ServiceProvider;


class PasServiceProvider extends ServiceProvider
{
    /**
     * Register default config
     * and main class instance.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/config/pas.php', 'pas');
        $this->app->instance(Pas::class, $this->getInstance());
    }

    private function getInstance()
    {
        $TERMINALID = (string) config('pas.TERMINALID', 'xxxxxxxx');
        return new Pas($TERMINALID, true);
    }
}
