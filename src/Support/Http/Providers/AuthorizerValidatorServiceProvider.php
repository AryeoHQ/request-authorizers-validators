<?php

declare(strict_types=1);

namespace Support\Http\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Support\Http\Commands\MakeAuthorizer;
use Support\Http\Commands\MakeValidator;

class AuthorizerValidatorServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function boot(): void
    {
        $this->bootCommands();
        $this->bootViews();
    }

    protected function bootViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../../../resources/views/rector/rules', 'request-authorizers-validators.rector.rules.samples');
    }

    protected function bootCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeAuthorizer::class,
                MakeValidator::class,
            ]);
        }
    }

    /**
     * @return array<string>
     */
    public function provides(): array
    {
        return [
            MakeAuthorizer::class,
            MakeValidator::class,
        ];
    }
}
