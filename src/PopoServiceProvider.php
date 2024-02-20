<?php

declare(strict_types=1);

namespace Scrumble\Popo;

use Illuminate\Support\ServiceProvider;
use Scrumble\Popo\Commands\MakePopoCommand;

class PopoServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->commands([
            MakePopoCommand::class,
        ]);
    }
}
